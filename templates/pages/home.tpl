{extends file="../layouts/main.tpl"}

{* Блок контента главной страницы *}
{block name="content"}
    <div class="home-page">
        <h1 class="page-title">Главная страница</h1>
        
        {* Список категорий с постами *}
        {if $categories && count($categories) > 0}
            {* Цикл по категориям *}
            {foreach $categories as $category}
                <section class="category-section">
                    <h2 class="category-section__title">
                        <a href="/category?id={$category.id}">{$category.name|escape}</a>
                    </h2>
                    {* Описание категории *}
                    {if $category.description}
                        <p class="category-section__description">{$category.description|escape}</p>
                    {/if}
                    
                    {* Список постов категории *}
                    {if $category.posts && count($category.posts) > 0}
                        <div class="category-section__posts">
                            {* Цикл по постам категории *}
                            {foreach $category.posts as $post}
                                {include file="../components/post_card.tpl" post=$post}
                            {/foreach}
                        </div>
                    {/if}
                    
                    {* Кнопка перехода к категории *}
                    <a href="/category?id={$category.id}" class="btn btn--primary">
                        Все статьи категории
                    </a>
                </section>
            {/foreach}
        {else}
            {* Сообщение об отсутствии категорий *}
            <p class="empty-message">Пока нет категорий с статьями.</p>
        {/if}
    </div>
{/block}
