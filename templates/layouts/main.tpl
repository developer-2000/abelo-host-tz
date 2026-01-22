<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>{$title|default:$smarty.config.site_name}</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>

    {include file="../components/header.tpl"}

    {block name="content"}{/block}

    {include file="../components/footer.tpl"}
</body>
</html>
