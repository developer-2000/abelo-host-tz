{extends file="../layouts/main.tpl"}

{* Блок контента страницы поста *}
{block name="content"}
    <div class="post-page">

        {if $post}
            {* Кнопка возврата к категории *}
            {if $post.categories && count($post.categories) > 0}
                {assign var="firstCategory" value=$post.categories[0]}
                <a href="/category?id={$firstCategory.id}" class="btn btn--back">← Назад к Категории</a>
            {else}
                <a href="/" class="btn btn--back">← На главную</a>
            {/if}
            
            {* Основной блок статьи *}
            <article class="post">
                {* Изображение поста *}
                {if $post.image}
                    <div class="post__image-wrapper">
                        <img src="{$post.image}" alt="{$post.title|escape}" class="post__image">
                    </div>
                {/if}
                
                <h1 class="post__title">{$post.title|escape}</h1>
                
                {* Мета-информация о посте *}
                <div class="post__meta">
                    <span class="post__views">Просмотров: {$post.views}</span>
                    {if $post.created_at}
                        <span class="post__date">{$post.created_at|date_format:"%d.%m.%Y %H:%M"}</span>
                    {/if}
                    {* Категории поста *}
                    {if $post.categories && count($post.categories) > 0}
                        <div class="post__categories">
                            <span class="post__categories-label">Категории:</span>
                            {* Цикл по категориям поста *}
                            {foreach $post.categories as $category}
                                <a href="/category?id={$category.id}" class="post__category-link">
                                    {$category.name|escape}
                                </a>
                            {/foreach}
                        </div>
                    {/if}
                </div>
                
                {* Описание поста *}
                {if $post.description}
                    <p class="post__description">{$post.description|escape}</p>
                {/if}
                
                {* Содержимое поста *}
                <div class="post__content">
                    {$post.content|escape|nl2br}
                </div>
            </article>
            
            {* Блок похожих статей *}
            {if $similarPosts && count($similarPosts) > 0}
                <section class="similar-posts">
                    <h2 class="similar-posts__title">Похожие статьи</h2>
                    <div class="similar-posts__list">
                        {* Цикл по похожим постам *}
                        {foreach $similarPosts as $similarPost}
                            {include file="../components/post_card.tpl" post=$similarPost}
                        {/foreach}
                    </div>
                </section>
            {/if}
        {/if}
    </div>
{/block}
