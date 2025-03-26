{if $top_selling_products}
    <div class="top-selling-products">
        <h3>{l s='Top Selling Products'}</h3>
        <ul>
            {foreach from=$top_selling_products item=product}
                <li>
                    <a href="{$link->getProductLink($product.id_product)}">
                        <img src="{$link->getImageLink($product.id_product, 'home_default')}" alt="{$product.name}" />
                        <p>{$product.name}</p>
                    </a>
                    <span>{l s='Sold'}</span>: {$product.total_sales}
                </li>
            {/foreach}
        </ul>
    </div>
{else}
    <p>{l s='No top-selling products at the moment.'}</p>
{/if}
