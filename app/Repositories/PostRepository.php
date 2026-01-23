<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\Post;
use PDO;

class PostRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Получить статью по ID
     * 
     * @param int $id ID статьи
     * @return Post|null Объект статьи или null, если не найдена
     */
    public function getById(int $id): ?Post
    {
        $sql = "SELECT id, title, description, content, image, views, created_at
                FROM posts
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $postRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$postRow) {
            return null;
        }

        return Post::fromArray($postRow);
    }

    /**
     * Получить категории статьи
     * 
     * @param int $postId ID статьи
     * @return array Массив категорий
     */
    public function getCategoriesByPostId(int $postId): array
    {
        $sql = "SELECT c.id, c.name
                FROM categories c
                INNER JOIN post_category pc ON c.id = pc.category_id
                WHERE pc.post_id = :post_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['post_id' => $postId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Увеличить счётчик просмотров статьи
     * 
     * @param int $id ID статьи
     * @return void
     */
    public function incrementViews(int $id): void
    {
        $sql = "UPDATE posts SET views = views + 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    /**
     * Получить похожие статьи (из тех же категорий)
     *
     * @param int $postId ID текущей статьи
     * @param int $limit Количество похожих статей
     * @return Post[] Массив объектов похожих статей
     */
    public function getSimilar(int $postId, int $limit = 3): array
    {
        $sql = "SELECT DISTINCT p.id, p.title, p.description, p.image, p.views, p.created_at
                FROM posts p
                INNER JOIN post_category pc ON p.id = pc.post_id
                INNER JOIN post_category pc2 ON pc.category_id = pc2.category_id
                WHERE pc2.post_id = :post_id
                AND p.id != :exclude_post_id
                ORDER BY p.created_at DESC
                LIMIT :limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':exclude_post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => Post::fromArray($row), $rows);
    }
}
