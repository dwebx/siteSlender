<?php 

function paypalOutput()
{
	$strBusiness = $GLOBALS['config']['orderform']['paypal_account'];
	$strItemName = post('Menge');
	$strCurrency = post('orderCurrency');
	$fltAmount = post('orderTotal');
	$fltShipping = post('orderShipping');
	$strReturn = $GLOBALS['config']['orderform']['paypal_return'];
	$strCancelReturn = $GLOBALS['config']['orderform']['paypal_cancelreturn'];

	/* if coupon */
	if ( post('Gutschein') )
	{
		$strItemName = "Gutschein: " . $strItemName; 
	}

	
	$strButton = '';
	#$strButton .= '<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">';
	$strButton .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">';
	$strButton .= '<input type="hidden" name="cmd" value="_xclick">';
	$strButton .= '<input type="hidden" name="business" value="%s">';
	$strButton .= '<input type="hidden" name="lc" value="DE">';
	$strButton .= '<input type="hidden" name="item_name" value="%s">';
	$strButton .= '<input type="hidden" name="amount" value="%s">';
	$strButton .= '<input type="hidden" name="currency_code" value="%s">';
	$strButton .= '<input type="hidden" name="button_subtype" value="services">';
	$strButton .= '<input type="hidden" name="no_note" value="0">';
	$strButton .= '<input type="hidden" name="shipping" value="%s">';
	$strButton .= '<input type="hidden" name="no_shipping" value="1">';
	$strButton .= '<input type="hidden" name="return" value="%s">';
	$strButton .= '<input type="hidden" name="rm" value="2">';
	#$strButton .= '<input type="hidden" name="on0" value="Handarbeit">';
	$strButton .= '<input type="hidden" name="cancel_return" value="%s">';
	$strButton .= '<input type="hidden" name="bn" value="'.$GLOBALS['config']['company']['firm_short'].'">';
	$strButton .= '<input type="image" src="https://www.paypal.com/de_DE/i/btn/btn_paynow_LG.gif" border="0" name="submit" alt="Jetzt einfach, schnell und sicher online bezahlen – mit PayPal.">';
	$strButton .= '<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">';
	$strButton .= '</form>';
	
	$strButton = sprintf( $strButton, $strBusiness, $strItemName, $fltAmount, $strCurrency, $fltShipping, $strReturn, $strCancelReturn);
	
	return sprintf($GLOBALS['config']['orderform']['paypal_text'], $strButton);
}

/*

Paypal rebound:

Array
(
    [transaction_subject] => Versilberung eines Kinderschuhs
    [txn_type] => web_accept
    [payment_date] => 20:19:19 Sep 28, 2012 PDT
    [last_name] => Ohh
    [option_selection1] => 
    [residence_country] => DE
    [pending_reason] => unilateral
    [item_name] => Versilberung eines Kinderschuhs
    [payment_gross] => 
    [mc_currency] => EUR
    [payment_type] => instant
    [protection_eligibility] => Ineligible
    [payer_status] => unverified
    [verify_sign] => AFcWxV21C7fd0v3bYYYRCpSSRl31AlFEatngeDfabQGwNU3oL-Zl3DDn
    [test_ipn] => 1
    [tax] => 0.00
    [payer_email] => teo@teo.de
    [txn_id] => 3SN376707E054460W
    [quantity] => 1
    [receiver_email] => mail@foerst-step.com
    [first_name] => Teh
    [option_name1] => Handarbeit
    [payer_id] => UQ737PR8HY8L6
    [item_number] => 
    [handling_amount] => 0.00
    [payment_status] => Pending
    [shipping] => 0.00
    [mc_gross] => 110.00
    [custom] => 
    [charset] => windows-1252
    [notify_version] => 3.7
)


Parameter reference:

 https://www.sandbox.paypal.com/de_DE/html/IntegrationCenter/ic_std-variable-reference.html


PayPal button:

 https://www.paypal.com/de/cgi-bin/webscr?cmd=_button-designer&factory_type=buynow


PayPal developer integration center

 https://www.paypalobjects.com/de_DE/html/IntegrationCenter/ic_home.html


*/
?>

