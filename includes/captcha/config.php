<?php
/**
 * includes/captcha/config.php
 * 
 * grafische Sicherheitsabfrage fuer Formulare
 *
 * @package siteSlender
 * @subpackage Core 
 * @author Torsten Albrecht <t.albrecht@dwebx.com>
 * @version v0.49a - 2005-09-03 - 10:43 CET
 */

/**
 * captcha settings 
 */

/**
 * background color of the image in rgb
 */
$arrBackgroundColor = array("255","255","255");

/**
 * text color of the displayed characters in rgb
 */
$arrTextColor = array("50","50","50");

/**
 * border color of the image in rgb 
 * 
 * affects only if $bolImageBorder is set totrue
 */
$arrBorderColor = array("200","200","220");

/**
 * x value of start coordinate
 */
$intTextLeft = 8; 

/**
 * y value of start coordinante
 */
$intTextTop = 20;

/**
 * anlge of the font
 * 
 * a value of 90 will display an upward wrtitten text
 * affects only if using true type fonts
 */
$intFontAngle = 0; // angle in degrees

/**
 * baseline of the font
 * 
 * affects only if using true type fonts
 */
$intFontBaseline = 34;

/**
 * true type font file 
 * 
 * this font will be used instead of the internal gdlib fonts ( type 1 - 5 )
 */
$strFontFile = "fonts/captcha_font.ttf";

/**
 * font size in pixel using gdlib internal fonts (1 - 5 )
 * 
 * font size in point (pica) using true type fonts
 */
$fltFontSize = 25; // GDlib v1.x = pixels / GBlib v2.x = point

/**
 * 
 */
$intFontType = 2; // values 1, 2, 3, 4, 5

/**
 * display border around the captcha image (true/false)
 */
$bolImageBorder = true;

/**
 * height of the captcha image in pixel
 */
$intImageHeight = 44;

/**
 * width of the captcha image in pixel
 */
$intImageWidth = 110;

?>