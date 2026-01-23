<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PostRepository;

class PostService
{
    private const SIMILAR_POSTS_LIMIT = 3;

    private PostRepository $postRepository;

    public function __construct()
    {
        $this->postRepository = new PostRepository();
    }

    /**
     * Получить данные статьи для страницы статьи
     * 
     * @param int $postId ID статьи
     * @return array|null Массив с данными статьи и похожими статьями, или null если статья не найдена
     */
    public function getPostPageData(int $postId): ?array
    {
        $post = $this->postRepository->getById($postId);
        
        if (!$post) {
            return null;
        }

        // Получаем категории статьи
        $categories = $this->postRepository->getCategoriesByPostId($postId);
        
        // Собираем финальную структуру для шаблона
        $postData = $post->toArray();
        $postData['categories'] = $categories;

        // Получаем похожие статьи
        $similarPosts = $this->postRepository->getSimilar($postId, self::SIMILAR_POSTS_LIMIT);
        
        // Конвертируем объекты в массивы для шаблона
        $similarPostsData = array_map(fn($post) => $post->toArray(), $similarPosts);

        return [
            'post' => $postData,
            'similarPosts' => $similarPostsData
        ];
    }

    /**
     * увеличить счётчик просмотров
     * 
     * @param int $postId ID статьи
     * @return void
     */
    public function trackView(int $postId): void
    {
        $this->postRepository->incrementViews($postId);
    }

}
