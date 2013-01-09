/**
 * contactform/js/orderform.js
 * 
 * This script is a part of the 'contactform' module of siteSlender to handle 
 * contact forms of web pages. 
 * 
 * The script checks the existence of one of the javascript frameworks and 
 * loads the according javascript files containig the functions
 * 
 * @package contactform
 * @subpackage javascript
 * @uses core function IncludeJavaScript of siteSlender includes/functions.js
 * @author Torsten Alrecht (Teo) <teo@dwebx.de>
 * @version 1.00.00 2011-05-28 03:15 CEST
 * @copyright 2011 dwebx.de
 */

/* prototype functions */
if ( typeof Prototype != 'undefined' ) 
{
	IncludeJavaScript( strWorkDir + 'js/ptOrderForm.js');
} /* END OF prototype functions */

/* mootools functions */
else if ( typeof MooTools != 'undefined' ) 
{
	IncludeJavaScript( strWorkDir + 'js/mtOrderForm.js');
} /* END OF mootools functions */
else 
{
	
}