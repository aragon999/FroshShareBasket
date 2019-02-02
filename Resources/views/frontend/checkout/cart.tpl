{namespace name="frontend/share_basket/checkout/cart"}
{extends file="parent:frontend/checkout/cart.tpl"}

{block name='frontend_checkout_actions_checkout'}
    <div class="frosh-share-basket--wrapper left">
        {if $shareBasketState == 'basketloaded'}
            {include file="frontend/_includes/messages.tpl" type="success" content="{s name="basketloaded"}{/s}"}
        {/if}
        {if $shareBasketState == 'basketnotfound'}
            {include file="frontend/_includes/messages.tpl" type="warning" content="{s name="basketnotfound"}{/s}"}
        {/if}
        <div class="frosh-share-basket--response"></div>
        <form action="{url controller=ShareBasket action=save}" method="post" class="frosh-share-basket--form">
            <button class="btn is--primary" type="submit" name="Submit" value="submit">
                <i class="icon--basket"></i> {s name="savebasket"}{/s}
            </button>
        </form>
    </div>
    {$smarty.block.parent}
{/block}
