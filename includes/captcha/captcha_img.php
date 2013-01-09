<?php
/**
 * includes/captcha/captcha_img.php
 * 
 * grafische Sicherheitsabfrage fuer Formulare
 *
 * @package siteSlender
 * @subpackage Core 
 * @author Torsten Albrecht <t.albrecht@dwebx.com>
 * @version v0.49a - 2005-09-03 - 10:43 CET
 */

/**
 * env
 * 
 * only necessary for stand alone usage without framework 
 */
if ( !defined('GDLIB') ) { define('GDLIB', '2'); } 
if ( !defined('INDEX') ) { header('Content-type: image/png'); }

/**
 * configuration
 */
require ('config.php');

/**
 *  generator 
 */
$strText = trim( htmlspecialchars( $_GET['wert'] ) );

if ( 0 >= GDLIB )
{
	die($textstring);
}
elseif ( 2 > GDLIB )
{
	$im = @ImageCreate($intImageWidth, $intImageHeight)
		or die($textstring);
}
else
{
	$im = @ImageCreateTrueColor($intImageWidth, $intImageHeight)
		or die($textstring);
}

list($red, $green, $blue) = $arrBackgroundColor;
$imgBackgroundColor = ImageColorAllocate($im, $red, $green, $blue);

list($red, $green, $blue) = $arrTextColor;
$imgTextColor = ImageColorAllocate($im, $red, $green, $blue);

ImageFilledRectangle ($im, 0, 0, $intImageWidth - 1, $intImageHeight - 1, $imgBackgroundColor);

if ( $bolImageBorder ) 
{
	list($red, $green, $blue) = $arrBorderColor;
	$imgBorderColor = ImageColorAllocate($im, $red, $green, $blue);
	ImageRectangle($im, 0, 0, $intImageWidth - 1, $intImageHeight - 1, $imgBorderColor);
}

imagettftext ( $im , $fltFontSize , $intFontAngle , $intTextLeft , $intFontBaseline , $imgTextColor, $strFontFile , $strText );
#ImageString($im, $intTextLeft, $intTextTop, $intFontType,  $strText, $imgTextColor);
ImagePNG($im);

?>