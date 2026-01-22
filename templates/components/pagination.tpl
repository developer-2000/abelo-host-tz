{* Универсальный компонент пагинации *}
{* Параметры: pagination (currentPage, totalPages, startPage, endPage, perPage), baseUrl, queryParams, perPageOptions *}
<div class="pagination-wrapper">
    {* Пагинация отображается только если страниц больше одной *}
    {if $pagination.totalPages > 1}
        <nav class="pagination" aria-label="Навигация по страницам">
            {* Кнопка "Предыдущая страница" *}
            {if $pagination.currentPage > 1}
                <a href="{buildPageLink queryParams=$queryParams baseUrl=$baseUrl page=$pagination.currentPage - 1}" 
                   class="pagination__link pagination__link--prev"
                   aria-label="Предыдущая страница"
                   title="Предыдущая страница">
                    <i class="fas fa-chevron-left"></i>
                </a>
            {/if}
            
            {* Блок номеров страниц *}
            <div class="pagination__pages">
                {* Показываем первую страницу *}
                {if $pagination.currentPage > 3}
                    <a href="{buildPageLink queryParams=$queryParams baseUrl=$baseUrl page=1}" 
                       class="pagination__link">
                        1
                    </a>
                    {if $pagination.currentPage > 4}
                        <span class="pagination__ellipsis">...</span>
                    {/if}
                {/if}
                
                {* Показываем страницы вокруг текущей *}
                {for $i=$pagination.startPage to $pagination.endPage}
                    <a href="{buildPageLink queryParams=$queryParams baseUrl=$baseUrl page=$i}" 
                       class="pagination__link {if $i == $pagination.currentPage}pagination__link--active{/if}">
                        {$i}
                    </a>
                {/for}
                
                {* Показываем последнюю страницу *}
                {if $pagination.currentPage < $pagination.totalPages - 2}
                    {if $pagination.currentPage < $pagination.totalPages - 3}
                        <span class="pagination__ellipsis">...</span>
                    {/if}
                    <a href="{buildPageLink queryParams=$queryParams baseUrl=$baseUrl page=$pagination.totalPages}" 
                       class="pagination__link">
                        {$pagination.totalPages}
                    </a>
                {/if}
            </div>
            
            {* Кнопка "Следующая страница" *}
            {if $pagination.currentPage < $pagination.totalPages}
                <a href="{buildPageLink queryParams=$queryParams baseUrl=$baseUrl page=$pagination.currentPage + 1}" 
                   class="pagination__link pagination__link--next"
                   aria-label="Следующая страница"
                   title="Следующая страница">
                    <i class="fas fa-chevron-right"></i>
                </a>
            {/if}
        </nav>
    {/if}
    
    {* Выбор количества постов на странице *}
    {if isset($perPageOptions)}
        <div class="pagination__per-page">
            {assign var="perPageBaseUrl" value="{buildPageLink queryParams=$queryParams baseUrl=$baseUrl page=1}"}
            <select id="perPageSelect" class="pagination__per-page-select"
                    onchange="var url = '{$perPageBaseUrl}'; url = url.replace(/perPage=\d+/, 'perPage=' + this.value); window.location.href = url;">
                {* Генерируем опции из массива perPageOptions *}
                {foreach $perPageOptions as $option}
                    <option value="{$option}" {if $pagination.perPage == $option}selected{/if}>{$option}</option>
                {/foreach}
            </select>
        </div>
    {/if}
</div>
