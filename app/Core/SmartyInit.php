<?php

declare(strict_types=1);

$smarty = new Smarty();

// Пути
$smarty->setTemplateDir(__DIR__ . '/../../templates/');
$smarty->setCompileDir(__DIR__ . '/../../var/templates_c/');
$smarty->setCacheDir(__DIR__ . '/../../var/cache/');
$smarty->setConfigDir(__DIR__ . '/../../configs/');

// Настройки
$smarty->setCaching(false);        // dev-режим
$smarty->setCompileCheck(true);    // пересобирать шаблоны при изменениях
$smarty->setForceCompile(false);

// Безопасность
$smarty->escape_html = true;

// Конфиги Smarty
$smarty->configLoad('site.conf');

// Глобальные переменные
$smarty->assignGlobal('app_name', 'AbeloHost Blog');
$smarty->assignGlobal('base_url', '/');

return $smarty;
