<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\Category;
use App\Models\Post;
use PDO;

class CategoryRepository
{
    private PDO $pdo;

    private const SORT_MAP = [
        'date'  => 'p.created_at DESC',
        'views' => 'p.views DESC',
    ];

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Получить все категории с последними постами (для главной страницы).
     *
     * Для каждой категории выбираются не более $limit последних постов:
     * - связи берутся через таблицу post_category (многие-ко-многим);
     * - подзапрос считает, сколько постов в этой категории новее (created_at > текущего)
     *   или с той же датой, но с меньшим id (чтобы разрулить дубликаты по времени);
     * - в основную выборку попадают только те строки, где это количество < :limit,
     *   то есть максимум $limit записей на категорию;
     * - итоговый результат отсортирован по id категории и дате поста по убыванию.
     *
     * @param int $limit Количество постов на категорию
     * @return array Массив категорий с постами
     */
    public function getAllWithPosts(int $limit = 3): array
    {
        $sql = "
            SELECT 
                c.id   AS category_id,
                c.name AS category_name,
                c.description AS category_description,
                p.id   AS post_id,
                p.title,
                p.description AS post_description,
                p.image,
                p.views,
                p.created_at
            FROM categories c
            INNER JOIN post_category pc ON pc.category_id = c.id
            INNER JOIN posts p ON p.id = pc.post_id
            WHERE (
                SELECT COUNT(*)
                FROM posts p2
                INNER JOIN post_category pc2 ON pc2.post_id = p2.id
                WHERE pc2.category_id = c.id
                AND (p2.created_at > p.created_at OR (p2.created_at = p.created_at AND p2.id < p.id))
            ) < :limit
            ORDER BY c.id, p.created_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->groupCategoriesWithPosts($rows);
    }

    /**
     * Получить категорию по ID
     * 
     * @param int $id ID категории
     * @return Category|null Объект категории или null, если не найдена
     */
    public function getById(int $id): ?Category
    {
        $sql = "SELECT id, name, description, created_at
                FROM categories
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? Category::fromArray($result) : null;
    }

    /**
     * Получить посты категории с сортировкой и пагинацией
     * 
     * @param int $categoryId ID категории
     * @param string $sort Тип сортировки: 'date' или 'views'
     * @param int $page Номер страницы
     * @param int $perPage Количество постов на странице
     * @return array Массив постов
     */
    public function getPosts(int $categoryId, string $sort, int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $orderBy = self::SORT_MAP[$sort] ?? self::SORT_MAP['date'];

        $sql = "SELECT p.id, p.title, p.description, p.image, p.views, p.created_at
                FROM posts p
                INNER JOIN post_category pc ON p.id = pc.post_id
                WHERE pc.category_id = :category_id
                ORDER BY {$orderBy}
                LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => Post::fromArray($row)->toArray(), $rows);
    }

    /**
     * Подсчитать количество постов в категории
     * 
     * @param int $categoryId ID категории
     * @return int Количество постов
     */
    public function countPosts(int $categoryId): int
    {
        $sql = "SELECT COUNT(*)
                FROM posts p
                INNER JOIN post_category pc ON p.id = pc.post_id
                WHERE pc.category_id = :category_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['category_id' => $categoryId]);
        
        return (int)$stmt->fetchColumn();
    }

    /**
     * Группирует результаты запроса по категориям
     *
     * @param array $rows Строки результата запроса
     * @return array Массив категорий с постами
     */
    private function groupCategoriesWithPosts(array $rows): array
    {
        $result = [];

        foreach ($rows as $row) {
            $catId = (int)$row['category_id'];

            if (!isset($result[$catId])) {
                $category = Category::fromArray([
                    'id' => $row['category_id'],
                    'name' => $row['category_name'],
                    'description' => $row['category_description'],
                ]);
                $result[$catId] = $category->toArray();
                $result[$catId]['posts'] = [];
            }

            $post = Post::fromArray([
                'id' => $row['post_id'],
                'title' => $row['title'],
                'description' => $row['post_description'],
                'image' => $row['image'],
                'views' => $row['views'],
                'created_at' => $row['created_at'],
            ]);
            $result[$catId]['posts'][] = $post->toArray();
        }

        return array_values($result);
    }

}
