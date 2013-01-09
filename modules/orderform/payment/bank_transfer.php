<?php 

function bankOutput()
{
	return sprintf( $GLOBALS['config']['orderform']['bank_text'], post('orderTotal')." ".post('orderCurrency'), $GLOBALS['config']['orderform']['bank_account'] );
}

?>