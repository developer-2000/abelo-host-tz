<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\PostService;

class PostController extends BaseController
{
    private PostService $postService;

    /**
     * @param \Smarty $smarty Объект Smarty для работы с шаблонами
     */
    public function __construct(\Smarty $smarty)
    {
        parent::__construct($smarty);
        $this->postService = new PostService();
    }

    /**
     * Отображение страницы статьи
     * Получает данные статьи, увеличивает счётчик просмотров и получает похожие статьи.
     * При отсутствии или невалидном ID статьи отображает страницу 404.
     * 
     * @return void
     */
    public function show(): void
    {
        // Валидация ID до вызова сервиса
        $postId = $this->validator->validateIdOrNull($_GET['id'] ?? null);
        
        if ($postId === null) {
            $this->show404();
            return;
        }

        // Получаем данные статьи (чтение)
        $data = $this->postService->getPostPageData($postId);
        
        if (!$data) {
            $this->show404();
            return;
        }

        // Увеличиваем счётчик просмотров только для существующей статьи
        $this->postService->trackView($postId);
        
        // Обновляем счётчик в данных для отображения
        $data['post']['views']++;

        $this->smarty->assign('post', $data['post']);
        $this->smarty->assign('similarPosts', $data['similarPosts']);
        $this->smarty->assign('title', $data['post']['title']);
        $this->smarty->display('pages/post.tpl');
    }
}
