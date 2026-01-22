<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Post;

class PostService
{
    private Post $postModel;

    public function __construct()
    {
        $this->postModel = new Post();
    }

    /**
     * Получить данные статьи для страницы статьи
     * 
     * @param int $postId ID статьи
     * @return array|null Массив с данными статьи и похожими статьями, или null если статья не найдена
     */
    public function getPostPageData(int $postId): ?array
    {
        $post = $this->postModel->getById($postId);
        
        if (!$post) {
            return null;
        }

        // Увеличиваем счётчик просмотров
        $this->postModel->incrementViews($postId);
        $post['views']++;

        // Получаем похожие статьи
        $similarPosts = $this->postModel->getSimilar($postId, 3);

        return [
            'post' => $post,
            'similarPosts' => $similarPosts
        ];
    }

}
