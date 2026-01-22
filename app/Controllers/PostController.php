<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\PostService;

class PostController extends BaseController
{
    private PostService $postService;

    /**
     * Конструктор контроллера страницы статьи
     * 
     * @param \Smarty $smarty Объект Smarty для работы с шаблонами
     */
    public function __construct(\Smarty $smarty)
    {
        parent::__construct($smarty);
        $this->postService = new PostService();
    }

    /**
     * Отображение страницы статьи
     * 
     * Получает данные статьи, увеличивает счётчик просмотров и получает похожие статьи.
     * Поддерживает GET-параметр:
     * - id (обязательный) - ID статьи
     * 
     * При отсутствии или невалидном ID статьи отображает страницу 404.
     * 
     * @return void
     */
    public function show(): void
    {
        // Валидация и получение ID статьи
        $postId = $this->validateId($_GET['id'] ?? null);

        $data = $this->postService->getPostPageData($postId);
        
        if (!$data) {
            $this->show404();
            return;
        }

        $this->smarty->assign('post', $data['post']);
        $this->smarty->assign('similarPosts', $data['similarPosts']);
        $this->smarty->assign('title', $data['post']['title']);
        $this->smarty->display('pages/post.tpl');
    }
}
