<?php
/**
 * orderform/send.php
 *
 * This script is based on 'contactform' module to handle contact forms
 * of web pages.
 *
 * This script will be used for sending mails to recipient and autor.
 *
 * @package orderform
 * @uses core functions of siteSlender includes/functions.php
 * @author Torsten Alrecht (Teo) <teo@dwebx.de>
 * @version 1.03.00 2011-11-13 15:00 CET
 * @copyright 2007 dwebx.de
 */


// Pruefung auf korrekten Aufruf innerhalb der Webseite
if (!defined('INDEX') && !defined('FEEDBACK'))
{
	header("Location: http://www.foerst-step.com/kontakt.html" );
	die;
}

// Config Feedback einbinden
include( $GLOBALS['config']['base'].DIR_MODULES.'orderform/config'.FILE_EXT );

// Script Feedback einbinden
include( $GLOBALS['config']['orderform']['work_dir'] . $GLOBALS['config']['orderform']['mailscript'] );


// env vars
$strDomain = substr($_SERVER['SERVER_NAME'],4); //
$strEmail  = ( $GLOBALS['company']['email'] ) ? $GLOBALS['company']['email'] : "postmaster@{domain}" ;
$strOwner  = "{$GLOBALS['company']['firm_short']} {$GLOBALS['company']['firm2']}";
$strSign   = $strOwner ;


#print prePrint($_POST);
$bolCustomerMailAlreadySent = false;

/**
 * wenn PayPal-Zahlung abgeschlossen 
 */
if ( post('txn_id') )
{
	$bolCustomerMailAlreadySent = true;
	
	/**
	 * PayPal - prepare  email content
	 */
	$arrMail['Name']      = sprintf( "%s %s", post('first_name'), post('last name') );
	$arrMail['From']      = post('payer_email');
	$arrMail['To']        = post('receiver_email');
	$arrMail['Subject']   = post('transaction_subject') . " [PayPal Bezahlung]";
	$arrMail['Text']      = wordwrap(post('Inhalt'), 70);

	$arrMail['Content']   = "Ein Artikel wurde per PayPal bezahlt\n\n";
	$arrMail['Content']  .= parseContent("transaction_subject", "Artikel: ##\n\n", "post");
	$arrMail['Content']  .= parseContent("txn_id", "Transaktions-ID: ##\n\n", "post");
	$arrMail['Content']  .= parseContent("payment_date", "Zeitpunkt: ##\n\n", "post");
	$arrMail['Content']  .= parseContent("mc_gross", "Summe: ## ", "post");
	$arrMail['Content']  .= parseContent("mc_currency", "##\n\n", "post");
	$arrMail['Content']  .= parseContent("payment_status", "Zahlungsstatus: ##\n\n", "post");
	$arrMail['Content']  .= parseContent("payer_email", "von PayPal-Konto: ##\n", "post");
	$arrMail['Content']  .= parseContent("first_name", "## ", "post");
	$arrMail['Content']  .= parseContent("last_name", "## ", "post");
	$arrMail['Content']  .= parseContent("residence_country", "(##)\n\n", "post");
	$arrMail['Content']  .= parseContent("payer_id", "(ID: ##)\n\n", "post");
	$arrMail['Content']  .= parseContent("verify_sign", "Transaktionssignatur: ##\n\n", "post");

	/* Signatur */
	$arrMail['Signatur']  = "
	<p>--</p>
	<p>Diese Information wurde nach Abschluss einer PayPal-Zahlung von ".$GLOBALS['company']['url']." geschickt.<p>";

/*Array
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
)*/
		
}
else 
{
	/**
	 *  Dateianhang
	 */
	if ( $_FILES && $_FILES['Datei']['name'])
	{
		$strErrors = "";

		$strFileName = basename($_FILES['Datei']['name']);
		$strFileType = substr($strFileName, strrpos($strFileName, '.') + 1);
		$intFileSize = $_FILES["Datei"]["size"]/1024;

		// settings
		$intMaxFileSize = 2048; // size in KB
		$arrAllowedExtensions = array("jpg", "jpeg", "png", "gif", "pdf", "zip");
		$strUploadFolder = DIR_UPLOADS;

		// validations
		// file size
		if( $intFileSize > $intMaxFileSize )
		{
			// $strErrors .= "\n Maximale Größe für Dateianhänge $intMaxFileSize kB";
			return "\n Maximale Größe für Dateianhänge $intMaxFileSize kB";
		}

		// file extension
		if( !in_array( strtolower( $strFileType ), $arrAllowedExtensions ) )
		{
			// $strErrors .= "\n Datei $strFileType nicht vom Typ der zugelassenen Dateitypen (" . implode(", ", $intMaxFileSize) . ")";
			return "\n Datei $strFileType nicht vom Typ der zugelassenen Dateitypen (" . implode(", ", $intMaxFileSize) . ")";
		}

		// copy the temp. uploaded file to uploads folder
		$strFilePath = $strUploadFolder . $strFileName;
		$strTempPath = $_FILES["Datei"]["tmp_name"];

		if( is_uploaded_file($strTempPath) )
		{
			if( !copy( $strTempPath, $strFilePath ) )
			{
				$strErrors .= '\n Fehler beim Hochladen des Dateianhangs';
			}
		}
	}
	
	/**
	 * Bestellung - prepare  email content
	 */
	$arrMail['Name']      = sprintf( "%s", $GLOBALS['company']['firm_short2'] );
	$arrMail['From']      = $strEmail;
	$arrMail['To']        = post('Empfaenger');
	$arrMail['Subject']   = post('Formulartitel');
	$arrMail['Text']      = wordwrap(post('Inhalt'), 70);

	$arrMail['Content']   = "";
	$arrMail['Content']  .= parseContent($strOwner, "An: ## ");
	$arrMail['Content']  .= parseContent("Empfaenger", "<##>\n", "post");
	$arrMail['Content']  .= parseContent("\nVon:\n");
	$arrMail['Content']  .= parseContent("Anrede", "## ", "post");
	$arrMail['Content']  .= parseContent("Vorname", "## ", "post");
	$arrMail['Content']  .= parseContent("Name", "##\n", "post");
	$arrMail['Content']  .= parseContent("Anschrift", "##\n", "post");
	$arrMail['Content']  .= parseContent("PLZ", "## ", "post");
	$arrMail['Content']  .= parseContent("Ort", "## ", "post");
	$arrMail['Content']  .= parseContent("Land", "(##)\n", "post");
	$arrMail['Content']  .= parseContent("\n");
	$arrMail['Content']  .= parseContent("Email", "Email: ##\n", "post");
	$arrMail['Content']  .= parseContent("Tel", "Telefon: ##\n", "post");
	$arrMail['Content']  .= parseContent("Handy", "Mobil: ##\n", "post");
	$arrMail['Content']  .= parseContent("\n");
	if ( post("Lieferadresse") )
	{
		$arrMail['Content']  .= parseContent("Lieferadresse:\n");
		$arrMail['Content']  .= parseContent("LAnrede", "## ", "post");
		$arrMail['Content']  .= parseContent("LVorname", "## ", "post");
		$arrMail['Content']  .= parseContent("LName", "##\n", "post");
		$arrMail['Content']  .= parseContent("LAnschrift", "##\n", "post");
		$arrMail['Content']  .= parseContent("LPLZ", "## ", "post");
		$arrMail['Content']  .= parseContent("LOrt", "## ", "post");
		$arrMail['Content']  .= parseContent("LLand", "(##)\n\n", "post");
	}
	$arrMail['Content']  .= parseContent("Bestellung:\n");
	$arrMail['Content']  .= parseContent("Menge", "##\n", "post");
	if ( post("Gutschein") )
	{
		$arrMail['Content']  .= parseContent("\nEs wird ein Gutschein benötigt: ja\n");
		$arrMail['Content']  .= parseContent("\nBeschenkter:\n");
		$arrMail['Content']  .= parseContent("GAnrede", "## ", "post");
		$arrMail['Content']  .= parseContent("GVorname", "## ", "post");
		$arrMail['Content']  .= parseContent("GName", "##\n", "post");
		$arrMail['Content']  .= parseContent("GAnschrift", "##\n", "post");
		$arrMail['Content']  .= parseContent("GPLZ", "## ", "post");
		$arrMail['Content']  .= parseContent("GOrt", "## ", "post");
		$arrMail['Content']  .= parseContent("GLand", "(##)\n\n", "post");
	}
	else
	{
		$arrMail['Content']  .= parseContent("\nEs wird ein Gutschein benötigt: nein\n");
	}
	$arrMail['Content']  .= parseContent("\nZahlungsart: ");
	$arrMail['Content']  .= parseContent("Zahlungsart", "##\n", "post");
	if ( post("Thema") )
	{
		$arrMail['Content']  .= parseContent("Thema", "Thema: ##\n", "post");
	}
	if ( post('Inhalt') !== $GLOBALS['config']['orderform']['inhalt_default'] )
	{
		$arrMail['Content']  .= parseContent("Inhalt", "\nBemerkung:\n##\n", "post");
	}

	/* Zusammenfassung */
	$arrMail['Content']  .= parseContent("\n-----------------------------\n");
	$arrMail['Content']  .= parseContent("Zusammenfassung\n");
	$arrMail['Content']  .= parseContent("-----------------------------\n");
	$arrMail['Content']  .= parseContent("orderItem", "\nArtikel: ## EUR\n", "post");

	if ( post('orderPayment') && 0 < post('orderPayment') )
	{
		$arrMail['Content']  .= parseContent("orderPayment", "Aufpreis für Zahlverfahren: ## EUR\n", "post");
	}

	$arrMail['Content']  .= parseContent("orderShipping", "Versandkosten: ## EUR\n", "post");

	if ( post('orderTax') && 0 < post('orderTax') )
	{
		$arrMail['Content']  .= parseContent("orderTax", "enthaltene Mehrwertsteuer: ##%\n", "post");
	}

	$arrMail['Content']  .= parseContent("-----------------------------\n");
	$arrMail['Content']  .= parseContent("orderTotal", "Gesamt: ## EUR\n\n", "post");

	/* Zahlungsart */
	include($GLOBALS['config']['orderform']['work_dir'] . 'payment/payment' . FILE_EXT);
	$arrMail['Content']  .= parseContent( paymentOutput( post('Zahlungsart') ) );


	/* Dateianhangsname */
	if ( $_FILES && 0 == strlen($strErrors) )
	{
		$arrMail['Content']  .= parseContent($strFileName, "Dateianhang: ##\n");
	}

	/* Signatur */
	$arrMail['Signatur']  = "
	<p>--</p>
	<p>Diese Bestellung wurde per Bestellformular von ".$GLOBALS['company']['url']." geschickt.<p>";

} // eof order total mail



/**
 * attach_mailer start
 */
$arrMail['ContentSend'] = $arrMail['Content'] . $arrMail['Signatur'];

#$mail = new attach_mailer(name, from, to, cc, bcc, subject);
$objMail = new attach_mailer( $arrMail['Name'], $arrMail['From'], $arrMail['To'], '', 'webmaster@dwebx.com', $arrMail['Subject'] );
$objMail->text_body = strip_tags( $arrMail['ContentSend'] );
$objMail->html_body = nl2br( $arrMail['ContentSend'] );
if ( $_FILES && 0 == strlen($strErrors) )
{
	$objMail->add_attach_file($strFilePath);
}
$bolStatus = $objMail->process_mail();
/**
 * attach_mailer end
 */


/**
 * EMAIL KOPIE AN ABSENDER
 */

// check for email address
if ( post('Email') && !$bolCustomerMailAlreadySent )
{
	// prepare email content
	$arrMail['From']         = post('Empfaenger');
	$arrMail['To']           = post('Email');
	$arrMail['Subject']      = "{$arrMail['Subject']}";

	$arrMail['CopyContent']  = "Vielen Dank für Ihre Bestellung.\n";
	$arrMail['CopyContent'] .= "\n";
	$arrMail['CopyContent'] .= "Anbei eine Kopie Ihres Auftrags.\n";
	$arrMail['CopyContent'] .= "----------------------------------------\n";
	$arrMail['CopyContent'] .= $arrMail['Content'];
	$arrMail['CopyContent'] .= "----------------------------------------\n";
	$arrMail['CopyContent'] .= "\nHerzlichen Dank für Ihre Bestellung.\n";
	$arrMail['CopyContent'] .= "Wir kümmern uns darum, daß Sie Ihre Bestellung so zügig wie möglich erhalten.\n";
	$arrMail['CopyContent'] .= "\nMit besten Grüßen\n";
	$arrMail['CopyContent'] .= "{$GLOBALS['company']['owner']}\n";
	$arrMail['CopyContent'] .= "{$GLOBALS['company']['firm_short']} {$GLOBALS['company']['firm2']}\n";

	$arrMail['Signatur']  = "
	<p>--</p>
	<p>
	{$GLOBALS['company']['firm_short']}<br />
	{$GLOBALS['company']['street']}, {$GLOBALS['company']['zip']} {$GLOBALS['company']['city']}</p>
	<p>
	Telefon:  {$GLOBALS['company']['phone']}<br />
	Fax:  {$GLOBALS['company']['fax']}<br />
	Email:    {$GLOBALS['company']['email']}<br />
	{$GLOBALS['company']['homepage']}</p>";

	$arrMail['ContentSend'] = $arrMail['CopyContent'].$arrMail['Signatur'];

	/**
	 * attach_mailer start
	 */
	$objMail = new attach_mailer( $arrMail['Name'], $arrMail['From'], $arrMail['To'], '', '', $arrMail['Subject'] );
	$objMail->text_body = strip_tags( $arrMail['ContentSend'] );
	$objMail->html_body = nl2br( $arrMail['ContentSend'] );
	if ( $_FILES && 0 == strlen($strErrors) )
	{
		$objMail->add_attach_file($strFilePath);
	}
	$bolStatus = $objMail->process_mail();
	/**
	 * attach_mailer end
	 */
}


/**
 *  AUSGABE 
 */
if (true === $bolStatus)
{
	if ( post('txn_id') )
	{
		print outputPayPal();
	}
	else
	{
		print outputMessage();
	}
}
else
{
	print outputError();
}

unset($strDomain, $strOwner, $strSign, $strEmail, $arrMail);

// END OF script



/* * * * Functions * * * */

/**
 * parseCOntent()
 *
 * generating blocks of text from strings/vars and/or form inputs
 *
 * @param mixed $var variable/string oder $_POST-Index
 * @param mixed $wrap
 * @param string $mode default 'var' ('var', 'post')
 * @return string $strReturn
 */
function parseContent($var, $wrap = 0, $strMode = "var")
{
	$strReturn = "";

	/* type: var, post */
	switch ($strMode) {

		case "post":
			if ( post($var, 0) )
			{
				$strReturn = trim( htmlspecialchars( post($var) ) );

			}
			break;

		case "var":

		default:
			$strReturn = $var;
	}

	/* wrap text yes/no */
	if ( $wrap ) {
		/* do wrap only if return value != "" */
		if ( "0" < strlen( $strReturn ) )
		{
			$strReturn = str_replace ("##", $strReturn, $wrap );
		}
	}

	return $strReturn;

} // END OF function parseContent()


// sendMail()
function sendMail($Recipient, $Subject, $Header, $Parameter)
{

    if (!@mail($Recipient, $Subject, $Header, $Parameter))
    {
        $bolStatus = false ;
    }
    else
    {
        $bolStatus = true ;
    }

 return $bolStatus;

} // END OF sendail($Recipient, $Subject, $Header)


/**
 * outputMessage()
 *
 * confirmation send mail
 *
 * @global string $strOwner
 * @global array $arrMail
 * @return string
 */
function outputMessage()
{
	global $strSign, $arrMail;

	$strMailContent = nl2br( $arrMail['Content'] );
	
	$strOutput = "";

	if ( $GLOBALS['config']['orderform']['show_form_title'] )
	{
		$stroutput .= sprintf('<h2 class="line">%s</h2>', post('Formulartitel') );
	}

	$strOutput = "
		<h2>Vielen Dank für Ihre Bestellung!</h2>

		<p>
			Wir haben Ihre nachstehende Bestellung entgegengenommen.<br />
			Diese Nachricht wird auch an die von Ihnen angegebene Emailadresse versandt.<br />
			<br />
			<span class=\"quote\">
			<strong>Ihre Bestellung:</strong><br />
			{$strMailContent}
			</span>
			<br />
			Herzlichen Dank für Ihre Bestellung.<br />
			Wir kümmern uns darum, daß Sie Ihre Bestellung so zügig wie möglich erhalten.<br />
			<br />
			Mit besten Grüßen<br />
			{$GLOBALS['company']['owner']}<br />
			{$strSign}<br />
		</p>

		<p style=\"text-align: center;\">
			<a title=\"zurück\" href=\"".post('Umleitung')."\">zurück zur Startseite</a>
		</p>\n";

    return $strOutput;

} // END OF outputMessage()


/**
 * outputPayPal()
 *
 * confirmation send mail
 *
 * @global string $strOwner
 * @global array $arrMail
 * @return string
 */
function outputPayPal()
{
	global $strSign;

	$arrMail['Content']   = "";
	$arrMail['Content']  .= parseContent("transaction_subject", "Artikel: ##\n\n", "post");
	$arrMail['Content']  .= parseContent("txn_id", "Transaktions-ID: ##\n\n", "post");
	$arrMail['Content']  .= parseContent("payment_date", "Zeitpunkt: ##\n\n", "post");
	$arrMail['Content']  .= parseContent("mc_gross", "Summe: ## ", "post");
	$arrMail['Content']  .= parseContent("mc_currency", "##\n\n", "post");
	$arrMail['Content']  .= parseContent("payment_status", "Zahlungsstatus: ##\n\n", "post");

	$strMailContent = nl2br( $arrMail['Content'] );

	$strOutput = "";

	if ( $GLOBALS['config']['orderform']['show_form_title'] )
	{
		$stroutput .= sprintf('<h2 class="line">%s</h2>', post('Formulartitel') );
	}

	$strOutput = "
		<h2>Vielen Dank für Ihre Bestellung!</h2>

		<p>
			Der Artikel wurde mit PayPal bezahlt.<br />
			<br />
			<span class=\"quote\">
			<strong>Transaktionsdaten:</strong><br />
			<br />
			{$strMailContent}
			</span>
			<br />
			Herzlichen Dank für Ihre Bestellung.<br />
			Wir kümmern uns darum, daß Sie Ihre Bestellung so zügig wie möglich erhalten.<br />
			<br />
			Mit besten Grüßen<br />
			{$GLOBALS['company']['owner']}<br />
			{$strSign}<br />
		</p>

		<p style=\"text-align: center;\">
			<a title=\"zurück\" href=\"".post('Umleitung')."\">zurück zur Startseite</a>
		</p>\n";

    return $strOutput;

} // END OF outputMessage()


/**
 * outputError()
 *
 * failed send mail
 *
 * @global string $strEmail
 * @global string $strOwner
 * @global array $arrMail
 * @return string HTML Output
 */
function outputError()
{
	global $strEmail, $strSign, $arrMail;

	$strMailContent = nl2br( $arrMail['Content'] );

	$strOutput = "";

	if ( $GLOBALS['config']['orderform']['show_form_title'] )
	{
		$stroutput .= sprintf('<h2 class="line">%s</h2>', post('Formulartitel') );
	}

	$strOutput = "
		<h3 class=\"error\">Es ist ein Fehler aufgetreten</h3>
		<p>
			Wir können nicht sicherstellen,
			dass uns die übermittelten Daten erreichen.<br />
			<br />
			Setzen Sie sich deshalb bitte direkt per Email mit
			uns in Verbindung!<br />
			<br />
			<span class=\"quote\">
			<strong>Ihre Bestellung:</strong><br />
			{$strMailContent}
			</span>
			<br />
			Wir bitten die Unannehmlichkeit zu entschuldigen.<br />
			<br />
			{$GLOBALS['company']['owner']}<br />
			{$strSign}<br />
		</p>

		<p style=\"text-align: center;\">
			<a title=\"Email an {$GLOBALS['company']['firm_short']}\" href=\"mailto:{$strEmail}?Subject=Kontaktformular\">Email senden</a>
		</p>\n";

    return $strOutput;

} // END OF outputMessage()

// END OF * * * Functions * * *
?>

<?php ?>