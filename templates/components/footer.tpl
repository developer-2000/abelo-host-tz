{* Компонент подвала сайта *}
<footer>
    {* Копирайт с текущим годом и названием сайта *}
    <p>&copy; {$smarty.now|date_format:"%Y"} {$smarty.config.site_name|default:"Cursor Blog"}</p>
</footer>
