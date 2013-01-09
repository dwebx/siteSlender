<?php
/**
 * contactform/config.php
 *
 * This script is a part of the 'contactform' module to handle contact forms
 * of web pages.
 *
 * @package contactform
 * @author Teo <teo@dwebx.de>
 * @version 1.03.00 2011-11-13 15:00 CET
 * @copyright 2007 dwebx.de
 */

/* env */
$GLOBALS['config']['orderform']['work_dir'] = $GLOBALS['config']['base'] . DIR_MODULES . "orderform/";
#$GLOBALS['config']['orderform']['mailscript'] = "class.phpmailer-lite" . FILE_EXT;
$GLOBALS['config']['orderform']['mailscript'] = "attach_mailer_class" . FILE_EXT;
$GLOBALS['config']['orderform']['form_page'] = "bestellung";
$GLOBALS['config']['orderform']['send_page'] = "bestellung-send";
if (!defined( "DIR_UPLOADS")) define("DIR_UPLOADS", "upload/"); // temp folder for attachements

/* text settings */
$GLOBALS['config']['mandatory_color'] = "#0d2c80"; // color of obligation field marks
$GLOBALS['config']['mandatory_char']  = "*"; // color of obligation field marks
$GLOBALS['config']['mandatory_explain'] = "&gt;&nbsp;benötigte Angaben sind mit einem";
$GLOBALS['config']['mandatory_explain'] = sprintf('<span style="color: %s">%s</span> Pflichtangaben',
$GLOBALS['config']['mandatory_color'],
$GLOBALS['config']['mandatory_char']);

$GLOBALS['config']['orderform']['form_title'] = "Ihre Bestellung bei ".$GLOBALS['company']['firm_short2'];
$GLOBALS['config']['orderform']['inhalt_default'] = "Hier können Sie uns eine zusätzliche Bemerkung zu Ihrer Bestellung hinterlassen.";
$GLOBALS['config']['orderform']['label_terms'] = sprintf("ich habe die %s gelesen und akzeptiere sie", makeLink(getLink('agb_plain'), 'AGB', 1) );

/* transaction */
$GLOBALS['config']['orderform']['bank_text'] = "Sie haben als Zahlungsart Überweisung gewählt. Bitte überweisen Sie den Gesamtbetrag von %s auf das Konto der folgenden Bankverbindung.\n\n%s";
$GLOBALS['config']['orderform']['bank_account'] = "JT Kobberstad, Kto.-Nr. 921 57 16, BLZ 100 777 77, Norisbank";
$GLOBALS['config']['orderform']['paypal_text'] = "Sie haben als Zahlungsweise Paypal gewählt. Durch Klick auf den folgenden Button, können Sie Ihre Transaktion abschließen.\n\n%s";
$GLOBALS['config']['orderform']['paypal_account'] = $GLOBALS['company']['email'];
$GLOBALS['config']['orderform']['paypal_return'] = "http://www.foerst-step.com/bestellung-send.html";
$GLOBALS['config']['orderform']['paypal_cancelreturn'] = "http://www.foerst-step.com/bestellung-send.html";


/* define requiered fields. one out of the contact fields and the message are always 
 * mandatory and it's not recommened to define separately */
$GLOBALS['config']['orderform']['mandatory_fields'] = array("Name","Vorname","Anschrift","PLZ","Ort","Land","Email");
$GLOBALS['config']['orderform']['mandatory_shipto_fields'] = array("LName","LVorname","LAnschrift","LPLZ","LOrt","LLand");
$GLOBALS['config']['orderform']['mandatory_coupon_fields'] = array("GName","GVorname","GAnschrift","GPLZ","GOrt","GLand");
$GLOBALS['config']['orderform']['contact_fieldgroup_title'] = "Kontakt";

$GLOBALS['config']['orderform']['show_page_title'] = false;
$GLOBALS['config']['orderform']['show_form_title'] = false;
$GLOBALS['config']['orderform']['show_title'] = true;
$GLOBALS['config']['orderform']['show_name'] = true;
$GLOBALS['config']['orderform']['show_forename'] = true;
$GLOBALS['config']['orderform']['show_address'] = true;
$GLOBALS['config']['orderform']['show_phone'] = true;
$GLOBALS['config']['orderform']['show_mobile'] = false;
$GLOBALS['config']['orderform']['show_email'] = true;
$GLOBALS['config']['orderform']['show_availability'] = false;
$GLOBALS['config']['orderform']['show_subject'] = false;
$GLOBALS['config']['orderform']['show_disclaimer'] = true;


$GLOBALS['config']['orderform']['country_default'] = "DE";
$GLOBALS['config']['orderform']['euro27'] = array (
	"AT" => "(AT) Austria",
	"BE" => "(BE) Belgium",
	"BG" => "(BG) Bulgaria",
	"CH" => "(CH) Switzerland",
	"CY" => "(CY) Cyprus",
	"CZ" => "(CZ) Czech Republic",
	"DK" => "(DK) Denmark",
	"DE" => "(DE) Germany",
	"EE" => "(EE) Estonia",
	"ES" => "(ES) Spain",
	"FI" => "(FI) Finland",
	"FR" => "(FR) France",
	"GB" => "(GB) Great Britain",
	"GR" => "(GR) Greece",
	"HU" => "(HU) Hungary",
	"IE" => "(IE) Ireland",
	"IT" => "(IT) Italy",
	"LV" => "(LV) Latvia",
	"LT" => "(LT) Lithuania",
	"LU" => "(LU) Luxembourg",
	"MT" => "(MT) Malta",
	"NL" => "(NL) Netherlands",
	"NO" => "(NO) Norway",
	"PL" => "(PL) Poland",
	"PT" => "(PT) Portugal",
	"RO" => "(RO) Romania",
	"SE" => "(SE) Sweden",
	"SI" => "(SI) Slovenia",
	"SK" => "(SK) Slovakia"
);

$GLOBALS['config']['orderform']['currency_default'] = "EUR";

?>