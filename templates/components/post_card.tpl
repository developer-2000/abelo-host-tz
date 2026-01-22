{* Компонент карточки поста *}
<article class="post-card">
    {* Изображение поста *}
    {if $post.image}
        <div class="post-card__image-wrapper">
            <img src="{$post.image}" alt="{$post.title|escape}" class="post-card__image">
        </div>
    {/if}
    
    {* Контент карточки *}
    <div class="post-card__content">
        <h3 class="post-card__title">
            <a href="/post?id={$post.id}">{$post.title|escape}</a>
        </h3>
        
        {* Описание поста *}
        {if $post.description}
            <p class="post-card__description">{$post.description|escape}</p>
        {/if}
        
        {* Мета-информация карточки *}
        <div class="post-card__meta">
            <span class="post-card__views">Просмотров: {$post.views}</span>
            {if $post.created_at}
                <span class="post-card__date">{$post.created_at|date_format:"%d.%m.%Y"}</span>
            {/if}
        </div>
    </div>
</article>
