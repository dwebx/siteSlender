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
$GLOBALS['config']['inquiryform']['work_dir'] = $GLOBALS['config']['base'] . DIR_MODULES . "inquiryform/";
#$GLOBALS['config']['inquiryform']['mailscript'] = "class.phpmailer-lite" . FILE_EXT;
$GLOBALS['config']['inquiryform']['mailscript'] = "attach_mailer_class" . FILE_EXT;
$GLOBALS['config']['inquiryform']['form_page'] = "anfrage";
$GLOBALS['config']['inquiryform']['send_page'] = "anfrage-send";
if (!defined( "DIR_UPLOADS")) define("DIR_UPLOADS", "upload/"); // temp folder for attachements

/* text settings */
$GLOBALS['config']['mandatory_color'] = "#0d2c80"; // color of obligation field marks
$GLOBALS['config']['mandatory_char']  = "*"; // color of obligation field marks
$GLOBALS['config']['mandatory_explain'] = "&gt;&nbsp;benötigte Angaben sind mit einem";
$GLOBALS['config']['mandatory_explain'] = sprintf('<span style="color: %s">%s</span> Pflichtangaben',
		$GLOBALS['config']['mandatory_color'],
		$GLOBALS['config']['mandatory_char']);

$GLOBALS['config']['inquiryform']['form_title'] = "Anfrage";
$GLOBALS['config']['inquiryform']['inhalt_default'] = "Kurze Beschreibung von Material und Oberfläche des Schuhs.";
$GLOBALS['config']['inquiryform']['inhalt_default'] = "Bitte beschreiben Sie uns Material und Oberfläche des Schuhs (Lackleder, Glattleder, Wildleder etc.). Diese Beschreibung und eventuell auch ein Bild des betreffenden Schuhs hilft uns dabei, Ihnen die am besten geeignete Veredelungsvariante vorzuschlagen.";

/* define requiered fields. one out of the contact fields and the message are always 
 * mandatory and it's not recommened to define separately */
$GLOBALS['config']['inquiryform']['mandatory_fields'] = array("Name,Schuh-Gr,Anzahl,Inhalt,Sicherheitsabfrage");
$GLOBALS['config']['inquiryform']['contact_fieldgroup_title'] = "Kontakt";

$GLOBALS['config']['inquiryform']['show_page_title'] = false;
$GLOBALS['config']['inquiryform']['show_form_title'] = false;
$GLOBALS['config']['inquiryform']['show_title'] = true;
$GLOBALS['config']['inquiryform']['show_name'] = true;
$GLOBALS['config']['inquiryform']['show_forename'] = true;
$GLOBALS['config']['inquiryform']['show_address'] = true;
$GLOBALS['config']['inquiryform']['show_phone'] = true;
$GLOBALS['config']['inquiryform']['show_mobile'] = false;
$GLOBALS['config']['inquiryform']['show_email'] = true;
$GLOBALS['config']['inquiryform']['show_availability'] = false;
$GLOBALS['config']['inquiryform']['show_subject'] = false;
$GLOBALS['config']['inquiryform']['show_disclaimer'] = true;

?>