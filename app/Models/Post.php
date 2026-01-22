<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class Post
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Получить статью по ID с категориями
     * 
     * @param int $id ID статьи
     * @return array|null Данные статьи с категориями или null, если не найдена
     */
    public function getById(int $id): ?array
    {
        // Загрузка статьи
        $sql = "SELECT id, title, description, content, image, views, created_at
                FROM posts
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $post = $stmt->fetch();

        if (!$post) {
            return null;
        }

        // Загрузка категорий статьи
        $sql = "SELECT c.id, c.name
                FROM categories c
                INNER JOIN post_category pc ON c.id = pc.category_id
                WHERE pc.post_id = :post_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['post_id' => $id]);
        $post['categories'] = $stmt->fetchAll();

        return $post;
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
     * @return array Массив похожих статей
     */
    public function getSimilar(int $postId, int $limit = 3): array
    {
        $sql = "SELECT DISTINCT p.id, p.title, p.description, p.image, p.views, p.created_at
                FROM posts p
                INNER JOIN post_category pc ON p.id = pc.post_id
                WHERE pc.category_id IN (
                    SELECT category_id 
                    FROM post_category 
                    WHERE post_id = :post_id_sub
                )
                AND p.id != :post_id
                ORDER BY p.created_at DESC
                LIMIT :limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':post_id_sub', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
