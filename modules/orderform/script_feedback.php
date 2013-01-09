<?php
/**
 * contactform/script_feedback.php
 *
 * This script is a part of the 'contactform' module to handle contact forms
 * of web pages.
 *
 * 'script_feedback.php' is the file with the init and function sections.
 *
 * @package contactform
 * @uses core functions of siteSlender includes/functions.php
 * @author Torsten Albrecht (Teo) <teo@dwebx.de>
 * @version 1.03.00 2011-11-13 15:00 CET
 * @copyright 2007 dwebx.de
 */


/**
 * br2nl()
 *
 * replace <br[*]> to \n
 *
 * @param string $str Text
 * @return string HTML-Output
 */
function br2nl($str) {

	return preg_replace("=<br(>|([\s/][^>]*)>)\r?\n?=i", "\n", $str);
}


/**
 * check_required()
 *
 * @param string $param field list
 * @uses explode_trim()
 * @return string HTML-Output
 */
function check_required( $strField, $strLabel, $blnTrailed = 1 ) {

	$strOutput = $strLabel;
	$arrFields = explode_trim(",", post('mandatory') );

	if( in_array( $strField, $arrFields ) )
	{
		$strMandatory = sprintf ('<span style="color: %s;">%s</span>',
			$GLOBALS['config']['mandatory_color'],
			$GLOBALS['config']['mandatory_char']
		);

		$arrOutput = array($strLabel, $strMandatory);

		if( !$blnTrailed )
		{
			$arrOutput = array_reverse($arrOutput);
		}

		$strOutput = implode("", $arrOutput);
	}
	return $strOutput;
}

/**
 *
 * @param type $strFieldName
 * @param type $arrCountries
 * @param type $blnNumericIndex
 * @param type $strDefault
 * @return type 
 */
function getCountries( $strFieldName="countrySelect", $strDefault = false, $blnNumericIndex = false, $strJScript = "" ) {
	
	$arrCountries = $GLOBALS['config']['orderform']['euro27'];

	if ( !$strDefault && $GLOBALS['config']['orderform']['country_default'] )
	{
		 $strDefault = $GLOBALS['config']['orderform']['country_default'];
	}

	return getSelectList($strFieldName, $arrCountries, $blnNumericIndex, $strDefault, $strJScript);
}

?>