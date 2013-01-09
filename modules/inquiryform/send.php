<?php
/**
 * contactform/send.php
 *
 * This script is a part of the 'm_formular' module to handle contact forms
 * of web pages.
 *
 * This script will be used for sending mails to recipient and autor.
 *
 * @package contactform
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
include( $GLOBALS['config']['base'].DIR_MODULES.'inquiryform/config'.FILE_EXT );

// Script Feedback einbinden
include( $GLOBALS['config']['inquiryform']['work_dir'] . $GLOBALS['config']['inquiryform']['mailscript'] );


// env vars
$strDomain = substr($_SERVER['SERVER_NAME'],4); //
$strEmail  = ( $GLOBALS['company']['email'] ) ? $GLOBALS['company']['email'] : "postmaster@{domain}" ;
$strOwner  = ( $GLOBALS['company']['firm'] ) ? $GLOBALS['company']['firm'] : "der Domaininhaber" ;
$strSign   = ( $GLOBALS['company']['firm'] ) ? strip_tags($GLOBALS['company']['firm_short']) : "der Domaininhaber" ;

// file
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

// prepare  email content
$arrMail['Name']      = sprintf( "%s Anfrageformular", $GLOBALS['company']['firm_short'] );
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
$arrMail['Content']  .= parseContent("Ort", "##\n", "post");
$arrMail['Content']  .= parseContent("\n");
$arrMail['Content']  .= parseContent("Tel", "Telefon: ##\n", "post");
$arrMail['Content']  .= parseContent("Handy", "Mobil: ##\n", "post");
$arrMail['Content']  .= parseContent("Email", "Email: ##\n", "post");
$arrMail['Content']  .= parseContent("\n");
$arrMail['Content']  .= parseContent("Schuh-Gr", "Schuhgröße: ##\n", "post");
$arrMail['Content']  .= parseContent("Anzahl", "Anzahl: ##\n", "post");
if ( $_FILES && 0 == strlen($strErrors) )
{
	$arrMail['Content']  .= parseContent($strFileName, "Dateianhang: ##\n");
}
$arrMail['Content']  .= parseContent("Veredelungsart", "Veredelungsart: ##\n", "post");
$arrMail['Content']  .= parseContent("\n");
if ( ( post("Tel") || post("Handy") ) && ( post('von') || post('bis') ) )
{
	$arrMail['Content']  .= parseContent("beste Erreichbarkeit: ");
	$arrMail['Content']  .= parseContent("von", "von ## ", "post");
	$arrMail['Content']  .= parseContent("bis", "bis ##\n", "post");
	$arrMail['Content']  .= parseContent("\n");
}
$arrMail['Content']  .= parseContent("Thema", "Thema: ##\n", "post");
$arrMail['Content']  .= parseContent("Inhalt", "\n##\n", "post");

$arrMail['Signatur']  = "
<p>--</p>
<p>Diese Email wurde per Kontaktformular von ".$GLOBALS['company']['url']." geschickt.<p>";

$arrMail['ContentSend'] = $arrMail['Content'] . $arrMail['Signatur'];

/**
 * attach_mailer start
 */
#$mail = new attach_mailer(name, from, to, cc, bcc, subject);
$objMail = new attach_mailer( $arrMail['Name'], $arrMail['From'], $arrMail['To'], '', '', $arrMail['Subject'] );
$objMail->text_body = strip_tags( $arrMail['ContentSend'] );
$mail->html_body = nl2br( $arrMail['ContentSend'] );
if ( $_FILES && 0 == strlen($strErrors) )
{
	$objMail->add_attach_file($strFilePath);
}
$bolStatus = $objMail->process_mail();
/**
 * attach_mailer end
 */


/* EMAIL KOPIE AN ABSENDER */

// check for email address
if ( post('Email') )
{
	// prepare email content
	$arrMail['From']         = post('Empfaenger');
	$arrMail['To']           = post('Email');
	$arrMail['Subject']      = "Kopie: {$arrMail['Subject']}";

	$arrMail['CopyContent']  = "Vielen Dank für Ihre Anfrage.\n";
	$arrMail['CopyContent'] .= "\n";
	$arrMail['CopyContent'] .= "Anbei eine Kopie Ihrer Nachricht an uns.\n";
	$arrMail['CopyContent'] .= "----------------------------------------\n";
	$arrMail['CopyContent'] .= $arrMail['Content'];
	$arrMail['CopyContent'] .= "----------------------------------------\n";
	$arrMail['CopyContent'] .= "\nWir werden uns schnellstmöglich bei Ihnen melden.\n";

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

/* AUSGABE */

// output result
if (true === $bolStatus)
{
	print outputMessage();
}
else
{
	print outputError();
}

// clear array
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

	if ( $GLOBALS['config']['inquiryform']['show_form_title'] )
	{
		$stroutput .= sprintf('<h2 class="line">%s</h2>', post('Formulartitel') );
	}

	$strOutput = "
		<h2>Vielen Dank</h2>

		<p>
			Die Daten wurden an uns übermittelt.<br />
			Bei Angabe einer gültigen Emailadresse wird
			automatisch eine Kopie dieser Nachricht versand.<br />
		<br />
		<span class=\"quote\">
		<strong>Emailtext:</strong><br />
		{$strMailContent}
		</span>
		<br />
		Wir werden uns schnellstmöglich bei Ihnen melden.<br />
		<br />
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

	if ( $GLOBALS['config']['inquiryform']['show_form_title'] )
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
		<strong>Ihre Mitteilung:</strong><br />
		{$strMailContent}
		</span>
		<br />
		Wir bitten die Unannehmlichkeit zu entschuldigen.<br />
		<br />
		{$strSign}<br /></p>

		<p style=\"text-align: center;\">
			<a title=\"Email an {$strSign}\" href=\"mailto:{$strEmail}?Subject=Kontaktformular\">Email senden</a>
		</p>\n";

    return $strOutput;

} // END OF outputMessage()

// END OF * * * Functions * * *
?>