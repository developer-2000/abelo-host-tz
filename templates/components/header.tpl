{* Компонент шапки сайта *}
<header class="header">
    <div class="container">
        {* Навигационное меню *}
        <nav class="header__nav">
            {* Логотип сайта *}
            <a href="/" class="header__logo">{$smarty.config.site_name|default:"AbeloHost Blog"}</a>
            {* Ссылки навигации *}
            <div class="header__links">
                <a href="/" class="header__link">Главная</a>
            </div>
        </nav>
    </div>
</header>
