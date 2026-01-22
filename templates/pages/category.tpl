{extends file="../layouts/main.tpl"}

{* Блок контента страницы категории *}
{block name="content"}
    <div class="category-page">
        {if $category}
            {* Кнопка возврата на главную *}
            <a href="/" class="btn btn--back">← Назад на главную</a>
            
            <h1 class="page-title">{$category.name|escape}</h1>
            
            {* Описание категории *}
            {if $category.description}
                <p class="category-description">{$category.description|escape}</p>
            {/if}
            
            {* Блок управления сортировкой *}
            <div class="category-controls">
                <span class="category-controls__label">Сортировать:</span>
                <a href="/category?id={$category.id}&sort=date" 
                   class="category-controls__link {if $sort == 'date'}category-controls__link--active{/if}">
                    По дате
                </a>
                <a href="/category?id={$category.id}&sort=views" 
                   class="category-controls__link {if $sort == 'views'}category-controls__link--active{/if}">
                    По просмотрам
                </a>
            </div>
            
            {* Список постов категории *}
            {if $posts && count($posts) > 0}
                <div class="posts-list">
                    {* Цикл по постам *}
                    {foreach $posts as $post}
                        {include file="../components/post_card.tpl" post=$post}
                    {/foreach}
                </div>
            {else}
                <p class="empty-message">В этой категории пока нет статей.</p>
            {/if}
            
            {* Блок пагинации *}
            {include file="../components/pagination.tpl" pagination=$pagination perPageOptions=$perPageOptions}
        {/if}
    </div>
{/block}
