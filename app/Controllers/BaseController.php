<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ValidatorService;
use Smarty;

/**
 * Базовый контроллер для всех контроллеров приложения
 * Содержит общие методы и свойства, используемые всеми контроллерами.
 */
abstract class BaseController
{
    protected Smarty $smarty;
    protected ValidatorService $validator;

    /**
     * @param Smarty $smarty Объект Smarty для работы с шаблонами
     */
    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->validator = new ValidatorService();
    }

    /**
     * Устанавливает HTTP статус 404 и отображает шаблон страницы ошибки.
     * 
     * @return void
     */
    protected function show404(): void
    {
        http_response_code(404);
        $this->smarty->assign('title', '404 - Страница не найдена');
        $this->smarty->display('pages/404.tpl');
    }
}
