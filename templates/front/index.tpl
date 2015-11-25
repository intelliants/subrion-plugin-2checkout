<form name="2checkout_form" id="2checkout_form" action="https://www.2checkout.com/checkout/purchase" method="post">
	<input type="hidden" name="item_number" value="{$plan.title|escape:'html'}">
	<input type="hidden" name="sid" value="{$config['2checkout_id']}">
	<input type="hidden" name="credit_card_processed" value="Y">
	<input type="hidden" name="cart_order_id" value="{$smarty.server.REQUEST_TIME}">
	<input type="hidden" name="total" value="{$plan.cost}">
	<input type="hidden" name="x_Receipt_Link_URL" value="{$return_url}">
	<input type="hidden" name="id_type" value="1">
	<input type="hidden" name="quantity" value="1">
	<input type="hidden" name="fixed" value="Y">
	<input type="hidden" name="pay_method" value="CC">
	{if $config['2checkout_demo']}
		<input type="hidden" name="demo" value="Y">
	{/if}
</form>