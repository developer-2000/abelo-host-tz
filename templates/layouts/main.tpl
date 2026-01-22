<!DOCTYPE html>
<html lang="ru">
<head>
    {* Мета-теги и заголовок страницы *}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title|default:$smarty.config.site_name|default:"AbeloHost Blog"}</title>
    {* Подключение стилей *}
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

    {* Подключение шапки сайта *}
    {include file="../components/header.tpl"}

    {* Основной контент страницы *}
    <main class="main-content">
        <div class="container">
            {block name="content"}{/block}
        </div>
    </main>

    {* Подключение подвала сайта *}
    {include file="../components/footer.tpl"}
</body>
</html>
