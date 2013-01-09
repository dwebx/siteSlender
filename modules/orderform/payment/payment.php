<?php 

/**
 * 
 */
function paymentOutput( $strTransactionType )
{
	$strOutput = "";
				
	switch( $strTransactionType )
	{
		case "PayPal":

			include( $GLOBALS['config']['orderform']['work_dir'] . 'payment/paypal' . FILE_EXT );

			$strOutput = paypalOutput();
			
			break;


		case "Überweisung":

			include( $GLOBALS['config']['orderform']['work_dir'] . 'payment/bank_transfer' . FILE_EXT );

			$strOutput = bankOutput();
			
			break;


		case "Nachnahme":

		default:

			break;
	}
	
	return $strOutput;
}

?>