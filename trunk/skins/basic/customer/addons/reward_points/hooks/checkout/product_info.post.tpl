{* $Id$ *}

{if !$cart.products.$key.extra.configuration}
	{if $cart.products.$key.extra.points_info.price}
	<div class="product-list-field">
		<label for="price_in_points_{$key}" class="valign">{$lang.price_in_points}:</label>
		{* [andyye] *}
		<a href="{$addons.sdeep.cod_explanation_url}">
		{* [/andyye] *}
		<span id="price_in_points_{$key}">{$cart.products.$key.extra.points_info.price}&nbsp;{$lang.points_lower}</span>
		{* [andyye] *}
		</a>
		{* [/andyye] *}
		
	</div>
	{/if}
	{if $cart.products.$key.extra.points_info.reward}
	<div class="product-list-field">
		<label for="reward_points_{$key}" class="valign">{$lang.reward_points}:</label>
		<span id="reward_points_{$key}">{$cart.products.$key.extra.points_info.reward}&nbsp;{$lang.points_lower}</span>
	</div>
	{/if}
{/if}