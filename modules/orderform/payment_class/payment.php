<?php 

/**
 * 
 */
class OrderPayment 
{
	
var $strTransactionType;


/**
 * 
 */
function __construct() {}


/**
 * 
 */
public function getOutput()
{
	switch( $strTransactionType )
	{
		case "PayPal":

			$obj = paypal();
			break;


		case "Überweisung":

			include( $GLOBALS['config']['orderform']['work_dir']['payment'] . 'bank_transfer' . FILE_EXT );
			break;


		case "Nachnahme":

		default:

			break;
	}
}


function __desctruct() {}

}
?>