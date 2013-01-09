<?php 


$strBusiness = $GLOBALS['config']['orderform']['paypal_account'];
$strItemName = post('Menge');
$fltAmount = post('orderTotal');
$fltShipping = post('orderShipping');

/* if coupon */
if ( post('Gutschein') )
{
	$strItemName = "Gutschein für " . $strItemName; 
}

return sprintf('<p>%s</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="%s">
<input type="hidden" name="lc" value="DE">
<input type="hidden" name="item_name" value="%s">
<input type="hidden" name="amount" value="%s">
<input type="hidden" name="currency_code" value="EUR">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="shipping" value="%s">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
<input type="image" src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="Jetzt einfach, schnell und sicher online bezahlen – mit PayPal.">
<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
</form>
',
$GLOBALS['config']['orderform']['paypal_text'],
$strBusiness,
$strItemName,
$fltAmount,
$fltShipping
);

?>