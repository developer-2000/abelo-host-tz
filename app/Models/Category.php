<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class Category
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Получить все категории с последними постами (для главной страницы)
     * 
     * @param int $limit Количество постов на категорию
     * @return array Массив категорий с постами
     */
    public function getAllWithPosts(int $limit = 3): array
    {
        // Получаем категории, в которых есть статьи
        $sql = "SELECT DISTINCT c.id, c.name, c.description
                FROM categories c
                INNER JOIN post_category pc ON c.id = pc.category_id
                ORDER BY c.id";
        
        $stmt = $this->pdo->query($sql);
        $categories = $stmt->fetchAll();

        // Для каждой категории загружаем последние посты
        $stmtPosts = $this->pdo->prepare("
            SELECT p.id, p.title, p.description, p.image, p.views, p.created_at
            FROM posts p
            INNER JOIN post_category pc ON p.id = pc.post_id
            WHERE pc.category_id = :category_id
            ORDER BY p.created_at DESC
            LIMIT :limit
        ");

        foreach ($categories as &$category) {
            $stmtPosts->bindValue(':category_id', $category['id'], PDO::PARAM_INT);
            $stmtPosts->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmtPosts->execute();
            $category['posts'] = $stmtPosts->fetchAll();
        }

        return $categories;
    }

    /**
     * Получить категорию по ID
     * 
     * @param int $id ID категории
     * @return array|null Данные категории или null, если не найдена
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT id, name, description, created_at
                FROM categories
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $result = $stmt->fetch();
        return $result ?: null;
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
        $orderBy = $sort === 'views' ? 'p.views DESC' : 'p.created_at DESC';

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

        return $stmt->fetchAll();
    }

    /**
     * Подсчитать количество постов в категории
     * 
     * @param int $categoryId ID категории
     * @return int Количество постов
     */
    public function countPosts(int $categoryId): int
    {
        $sql = "SELECT COUNT(*) as count
                FROM posts p
                INNER JOIN post_category pc ON p.id = pc.post_id
                WHERE pc.category_id = :category_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['category_id' => $categoryId]);
        
        $result = $stmt->fetch();
        return (int)($result['count'] ?? 0);
    }
}
