<?php
/**
 * includes/functions.php
 *
 * Sammlung von Funktionen
 *
 * @package siteSlender
 * @subpackage Core
 * @author Torsten Albrecht <t.albrecht@dwebx.de>
 * @version 1.5.2
 * @copyright 2003
 */


/**
 * core functions
 */


/**
 * get()
 *
 * Ermittelt Existenz eines $_GET Index ($key), validiert die Eingabe und liefert
 * bei $bolOutput = 1 (default) den Wert.
 *
 * Hinweis: bei String- oder Wertvergleichen ist $bolOutput=0 zu verwenden, 
 * wobei auf korrekte Typisierung zu achten ist (Vergleichsoperator) => '' / 0 / NULL / FALSE
 *
 * @param string $strKey
 * @param bool $bolOutput ( 0 / 1, default 1)
 * @param string $strType ( "get" / "post", default "get")
 * @return mixed $bolReturn / $strValue
 */
function get( $strKey, $bolOutput = 1, $strType = "get" )
{
	$strValue = "";

	switch ($strType)
	{
		case "post":
			if ( true === isset($_POST[$strKey] ) )
			{
				$strValue = trim( htmlspecialchars( $_POST[$strKey] ) );
			}
			break;		
			
		default:
			if ( true === isset($_GET[$strKey] ) )
			{
				$strValue = trim( htmlspecialchars( $_GET[$strKey] ) );
			}
			break;		
	}

	if ( "0" < strlen( $strValue ) )
	{
		$bolReturn = TRUE;
	}
	else
	{
		$bolReturn =  FALSE;
	}

	if ( $bolOutput )
	{
		return $strValue;
	}
	else
	{
		return $bolReturn;
	}
}


/**
 * post()
 *
 * Ermittelt Existenz eines $_POST Index ($key), validiert die Eingabe und liefert
 * bei $bolOutput = 1 (default) den Wert.
 *
 * Hinweis: bei String- oder Wertvergleichen ist $bolOutput=0 zu verwenden, 
 * wobei auf korrekte Typisierung zu achten ist (Vergleichsoperator) => '' / 0 / NULL / FALSE
 *
 * @param string $strKey
 * @param bool $bolOutput ( 0 / 1, default 1)
 * @uses get()
 * @return mixed
 */
function post($strKey, $bolOutput = 1)
{
	return get( $strKey, $bolOutput, "post");
}


/**
 * getHeadline()
 * 
 * liefert grafische Überschrift aus dem Style-Ordner. 
 * Bei Nichtexistenz der Datei (PNG) wird der alternative Text (ohne Angabe 
 * die PAGE_ID) zurueckgegeben.
 * 
 * @param string $strAlternative Textversion 
 * @param string $strType HTML-Headline Kategorie (bspw. h1, h4; default = h1)
 * @param string $strOptions Zusatzparameter (bspw. class="first")
 * @return string HTML-Output
 */
function getHeadline( $strAlternative = PAGE_ID, $strType = "h1", $strOptions = "" ) {

	$strFilename = $GLOBALS['config']['base'] . 'themes/' . STYLE . '/hl_' . PAGE_ID . '.png';
	$strAlternative = str_replace( '"', '&quot;', strip_tags( $strAlternative ) );

	if ( file_exists($strFilename) )
	{
		$strImageSize = "";
		
		if ( 0 < GDLIB )
		{
			$arrImageSize = getimagesize($strFilename);
			$strImageSize = sprintf('width="%s" height="%s" ', $arrImageSize[0], $arrImageSize[1] );
		}
		
		return sprintf('<img alt="%s" src="%s" %s %s/>', $strAlternative, $strFilename, $strImageSize, $strOptions); 
	}
	else
	{
		return sprintf("<%s>%s</%s>", $strType, $strAlternative, $strType);
	}
}


/**
 * getLink()
 *
 * liefert den korrekten bei Links zu verwendenten String eines Seitennames (bspw. 'about_us'), 
 * abhängig von Konfiguration ('real_url' ja/nein) und Existenz der Datei ('about_us.php'). 
 * Ist der Dateiname nicht vorhanden, wird der Zielstring für die 404-Seite geliefert. 
 * Für Ankernavigation innerhalb der Seite ist der optionale Parameter $strAnchor 
 * ohne führende # zu verwenden
 * 
 * TODO: lightbox support
 *
 * @param string $strPage
 * @param string $strAnchor (default "")
 */
function getLink ( $strPage, $strAnchor = "" ) {

	if ($strAnchor)
	{
		$strAnchor = "#".$strAnchor;
	}
		
	if (!file_exists( $strPage.FILE_EXT ) )
	{
		$strPage = "404";
	}

	if ( $GLOBALS['config']['real_url'] )
	{
		return $GLOBALS['config']['base'].$strPage.$GLOBALS['config']['url_suffix'].$strAnchor;
	}
	else
	{
		return "?".PID_IDENT."=" . $strPage.$strAnchor;
	}
}


/**
 * theLink()
 *
 * gibt den String des per getLink() erzeugten Strings direkt aus
 *
 * @uses getLink()
 * @param string $strPage
 */
function theLink ( $strPage ) {

	print getLink( $strPage );
} 


/**
 * makeLink()
 *
 * erzeugt HTML-Links aus URL-formatiertem Text. 
 * 
 * Email-Links ($bolMail = 1) werden 
 * kodiert wiedergegeben um automatisches Auslesen (durch Bots/Crawler) zu erschweren. 
 * 
 * Hinweis: diese Funktion einget sich zur Erzeugung von Links aus den Strings der
 * Funktionen getLink() und niceDomain() 
 * 
 * => Beispiel intern: <?php echo makeLink( getLink('about_us'), 'über uns' ); ?>
 * => Beispiel extern: <?php echo makeLink( 'www.domain.tld', niceDomain('www.domain.tld', 1); ?>
 * => Beispiel EMail:  <?php echo makeLink( 'info@domain.tld', 'Zentrale', 1, 1  );?>
 * 
 * @param string $strURL
 * @param string $strTitle
 * @param bool $bolExtern
 * @uses txt2ascii
 * @return string $strLink HTML-Output
 */
function makeLink($strURL, $strTitle = false, $bolExtern = 0, $bolMail = 0, $strOptions = '' )
{ 
	if (!$strTitle)
	{
		$strTitle = $strURL;
	}

	$strOptions = " " . $strOptions;
	
	if ( $bolMail )
	{
		$strURLEncoded = txt2ascii( $strURL );
		$strTitleEncoded = txt2ascii( $strTitle );
		$strLink = preg_replace('#[\w\d-_.]*@{1}[\w\d-_.]*#', "<a href=\"mailto:{$strURLEncoded}\" title=\"Email an {$strTitle}\"{$strOptions}>{$strTitleEncoded}</a>", $strURL);
	}
	else if ( $bolExtern )
	{
		$strLink = preg_replace('%^([a-zA-Z]*://)?([\w\./=\-?#]*)%', "<a href=\"#{$strTitle}\" title=\"\\0\" rel=\"extern\"{$strOptions}>{$strTitle}</a>", $strURL);
	}
	else
	{
		$strLink = preg_replace('%^([a-zA-Z]*://)?([\w\./=\-?#]*)%', "<a title=\"{$strTitle}\" href=\"\\0\"{$strOptions}>{$strTitle}</a>", $strURL);
	}

	return $strLink;
}


/**
 * niceDomain()
 * 
 * 
 * 
 * @param string $strDomain
 * @return string 
 */
function niceDomain( $strDomain )
{
	if ( preg_match( "#www\.#", $strDomain ) )
	{
		return string_rest( $strDomain, "www." );
	}
	else if ( preg_match( "#://#", $strDomain ) )
	{
		return string_rest( $strDomain, "://" );
	}
	else
	{
		return false;
	}
}


/**
 * getMetaTitle()
 *
 * liefert den HTML-Titel aus der Konfiguration, entsprechend der 
 * Seiten-ID oder eines Standardtitels für die gesamte Seite
 *
 * @uses get()
 * @return string $strMetaTitle HTML-Output
 */
function getMetaTitle()
{
	$strMetaTitle = "";

	if ( defined(PAGE_ID) )
	{
		$strPage = PAGE_ID;
	}
	else if ( get( PID_IDENT , 0 ) && 0 < strlen( get( PID_IDENT ) ) && $GLOBALS['meta']['seo'][get( PID_IDENT )]['title'] )
	{
		$strPage = get( PID_IDENT );
	}
	else
	{
		$strPage = 'default';
	}

	$strMetaTitle = sprintf('<title>%s</title>'."\n",
		$GLOBALS['meta']['seo'][$strPage]['title']
	);

	return $strMetaTitle;
}


/**
 * getMetaDescription()
 *
 * liefert die HTML-Meta-Description aus der Konfiguration, entsprechend der 
 * Seiten-ID oder einer Standardbeschreibung für die gesamte Seite
 *
 * @uses get()
 * @return string $strMetaDescription HTML-Output
 */
function getMetaDescription()
{
	$strMetaDescription = "";

	if ( defined(PAGE_ID) )
	{
		$strPage = PAGE_ID;
	}
	else if ( get( PID_IDENT , 0 ) && 0 < strlen( get( PID_IDENT ) ) && $GLOBALS['meta']['seo'][get( PID_IDENT )]['desc'] )
	{
		$strPage = get( PID_IDENT );
	}
	else
	{
		$strPage = 'default';
	}

	$strMetaDescription = sprintf('<meta name="Description" content="%s" />'."\n",
		$GLOBALS['meta']['seo'][$strPage]['desc']
	);

	return $strMetaDescription;
}


/**
 * getMetaKeys()
 *
 * liefert die HTML-Meta-Keywords aus der Konfiguration, entsprechend der 
 * Seiten-ID oder der Standardschlüssel für die gesamte Seite
 * 
 * @uses get()
 * @return string $strMetaKeys HTML-Output
 */
function getMetaKeywords()
{
	$strMetaKeywords = "";

	if ( defined(PAGE_ID) )
	{
		$strPage = PAGE_ID;
	}
	else if ( get( PID_IDENT , 0 ) && 0 < strlen( get( PID_IDENT ) ) && $GLOBALS['meta']['seo'][get( PID_IDENT )]['keys'] )
	{
		$strPage = get( PID_IDENT );
	}
	else
	{
		$strPage = 'default';
	}

	$strMetaKeywords = sprintf('<meta name="Keywords" content="%s" />'."\n",
		$GLOBALS['meta']['seo'][$strPage]['keys']
	);

	return $strMetaKeywords;
}


function getSelectList( $strFieldName="selectList", $arrOptions = array(), $blnNumericIndex = 0, $strDefault = false, $strJScript = "" ) 
{
	$strOptions = "";
	foreach ( $arrOptions AS $key => $value )
	{
		$strPreselect = "";
		if ( post($strFieldName) && array_key_exists( post($strFieldName), $arrOptions ) )
		{
			$strPreselect = post($strFieldName);
		}
		elseif ( $strDefault && array_key_exists( $strDefault, $arrOptions ) )
		{
			$strPreselect = $strDefault;
		}

		if ( $strPreselect === $key )
		{
			$strSelected = 'selected="selected"';
		}
		else
		{
			$strSelected = "";
		}
		
		$strOptions .= sprintf('<option value="%s" %s>%s</option>', $key, $strSelected, $value);
	}
	
	$strOutput = sprintf('<select id="%s" name="%s" %s class="select">	%s\n</select>', $strFieldName, $strFieldName, $strJScript, $strOptions);
	
	return $strOutput;
}


/**
 * navActive()
 * 
 * liefert die CSS-Klasse für Navigationspunkte sofern aktiv oder Elternknoten
 * von aktivierten Unterpunkten
 *
 * @param string $strCat
 * @return string $strReturn HTML-Output
 */
function navActive( $strCat = "" ) {

	$strReturn = "";

	if ( $strCat === PAGE_ID )
	{
		$strReturn = 'active';
	}
	else if ( $strCat === array_shift( explode( "_", PAGE_ID ) ) )
	{
		$strReturn = 'trail';
	}
	else if ( $strCat === array_shift( explode( "-", PAGE_ID ) ) )
	{
		$strReturn = 'trail';
	}


	return $strReturn;
}


/**
 * prePrint()
 *
 * Funktion um Array formatiert auszugeben
 *
 * @param array $array Array welches ausgegeben werden soll
 * @return string HTML Output, formatierte Ausgabe eines Arrays
 */
function prePrint( $array = array() ) {

	ob_start();

		print_r( $array );
		$printed_array = ob_get_contents();

	ob_end_clean();

	return sprintf("\n\n<br />\n<div style='background: gray; border: solid 1px black; text-align: left; margin: 10px; padding: 10px;'>\n<h2>Debug-Info:</h2>\n<pre>\n%s\n</pre>\n</div>\n<br />\n\n",
		$printed_array
	);
}


/**
 * linkLanguage()
 *
 * @param string $strLang
 */
function linkLanguage( $strLang = DEFAULT_LANG ) {

	 $strURI = $_SERVER['REQUEST_URI'];
	 if ( 2 == strlen( $strLang ) )
	 {
		if ($GLOBALS['config']['real_url'])
		{
			$strPattern = "/^(.*\/)[a-z]{2}(\/.*)$/m";
			$strURI = preg_replace( $strPattern, "$1$strLang$2", $strURI );
		}
		else
		{
			$strPattern = "/^(.*ln=)[a-z]{2}(.*)$/m";
			$strURI = preg_replace( $strPattern, "$1$strLang$2", $strURI );
		}
	 }

	 print $strURI;

	 /**
	  * TODO: Funktion fuer Verwendung ohne 'mod_rewrite' erweitern (URL param ln= )
	  */
}


/**
 * getImage()
 *
 * liefert den HTML-String zur Darstellung eines Bildes (auch mit Lightbox)
 * 
 * @param string $imgSource (source file)
 * @param string $imgFolder (default DIR_IMAGES defined in config.php)
 * @param string $imgClass (CSS class)
 * @param string $imgAlternative (HTML-Attribute alt="")
 * @param mixed $lightbox (fasle or string)
 * @param string $lightboxTarget (linked image file)
 * @param string $imgOptions (additional options like style="" width="" etc.)
 * @return string $image HTML-Output
 */
function getImage($imgSource, $imgFolder='', $imgClass='', $imgAlternative='', $lightbox=false, $lightboxTarget="", $imgOptions='') {

	// check image folder for closing slash (if not, add slash)
	if ($imgFolder && "/" !== substr($imgFolder, -1, 1))
	{
		$imgFolder .= "/";
	}

	// check if image file exists (if not, change to transparent dummy gif)
	if (false === file_exists( DIR_IMAGES . $imgFolder . $imgSource))
	{
		$imgFolder = "";
		$imgSource = "dummy.gif";
	}

	$image = sprintf('<img alt="%s" src="%s" class="%s" %s />',
		$imgAlternative,
		DOC_ROOT . DIR_IMAGES . $imgFolder . $imgSource,
		$imgClass,
		$imgOptions
	);

	if ( $lightbox )
	{
		if( 1 != $lightbox )
		{
			$strLightboxString = "lightbox[$lightbox]";
		}
		else
		{
			$strLightboxString = "lightbox";
		}

		if ( 0 < strlen( $lightboxTarget ) )
		{
			$imgSource = DOC_ROOT . DIR_IMAGES.$imgFolder.$lightboxTarget;
		}
		else
		{
			$imgSource = DOC_ROOT . DIR_IMAGES.$imgFolder.$imgSource;
		}

		$image = sprintf('<a title="%s" href="%s" rel="%s">%s</a>',
			$imgAlternative,
			$imgSource,
			$strLightboxString,
			$image
		);

	}
	return $image;
}

/**
 * showIimage()
 * 
 * stellt ein Bild per HTML dar (auch mit Lightbox)
 * 
 * @param type $imgSource
 * @param type $imgFolder
 * @param type $imgClass
 * @param type $imgAlternative
 * @param type $lightbox
 * @param type $lightboxTarget
 * @param type $imgOptions 
 * @uses getImage()
 */
function showImage($imgSource, $imgFolder='', $imgClass='', $imgAlternative='', $lightbox=false, $lightboxTarget="", $imgOptions='') {
	
	print getImage($imgSource, $imgFolder, $imgClass, $imgAlternative, $lightbox, $lightboxTarget, $imgOptions);
}

/**
 * showGallery()
 * 
 * Stellt eine Bildergalerie dar (auch mit Lightbox). 
 * Die Bildquellen werden als assoziatives Array uebergeben, wobei der Schluessel 
 * das Bild selbst und der Wert die Bildunterschrift enthaelt.
 * 
 * Mit dem Parameter $strHeading kanndie Galerie mit einer Ueberschrift versehen 
 * werden.
 * 
 * @param type $imgSource
 * @param type $imgFolder
 * @param type $imgClass
 * @param type $strHeading
 * @param type $lightbox 
 */
function showGallery($imgSource, $imgFolder='', $imgClass='', $strHeading='', $lightbox=false) {
	
	$strOutput = "";
	
	if (is_array($imgSource) && 0 < count($imgSource))
	{
		$strOutput .= '<div class="mod_gallery">';
		$strOutput .= '<div class="heading">'.$strHeading.'</div>';

		foreach( $imgSource AS $strImg => $strCaption )
		{
			$strOutput .= '<div class="image">';
			$strOutput .= '<div class="thumbnail">';
			$strOutput .= getImage($strImg, $imgFolder, $imgClass, strip_tags($strCaption), $lightbox);
			$strOutput .= '</div>';
			$strOutput .= '<span class="caption">'.$strCaption.'</span>';
			$strOutput .= '</div>';
		}

		$strOutput .= '</div>';
	}
	
	print $strOutput;
}



/**
 * STRING FUNCTIONS
 */


/**
 * txt2acsii
 * 
 * konvertiert Strings aus Buchstaben zu Srtings in HTML-ASCII Code ( &#73; )
 * 
 * @param string $string
 * @return string $strEncoded 
 */
function txt2ascii( $string ) {
	
	$strEncoded = "";
	$strPattern = "&#%s;";
	
	$intStrLen = strlen( $string );
	
	for ( $i = 0 ; $i < $intStrLen ; $i++ )
	{
		$strChar = substr($string, $i, 1 );
		$intChar = ord($strChar);
		$strEncoded .= sprintf( $strPattern, $intChar );
	}

	return $strEncoded;
}


/**
 * ascii2txt
 * 
 * konvertiert Strings aus HTML-ASCII Codes (format -> &#73; ) in Strings aus Buchstaben
 * 
 * @param string $string
 * @return string $strDencoded 
 */
function acsii2txt( $string ) {
	
	$strDecoded = ""; 
	$strPattern = "/\&\#(\d+)\;/";

	preg_match_all( $strPattern, $string, $arrMatches );

	if ( $arrMatches )
	{
		foreach ( $arrMatches[1] AS $intVal )
		{
			$strChar = chr( $intVal );
			$strDecoded .= $strChar;
		}
	}

	return $strDecoded;
}


/**
 * escapeQuotes
 * 
 * konvertiert Strings aus Buchstaben zu Srtings in HTML-ASCII Code ( &#73; )
 * 
 * @param string $string
 * @return string $strEncoded 
 */
function escapeQuotes( $string ) {
	
	$strUnescaped = $string;
	
	$strEscaped = str_replace('"', '\"', $strUnescaped);
	$strEscaped = str_replace("'", "\'", $strEscaped);

	return $strEscaped;
}


/**
 * explode_trim()
 *
 * modified Version with switched parameters and return value equal to the
 * explode() function
 *
 * @author Aram Kocharyan 2011-02-28 03:04
 * @author Teo 2011-04-04 02:44
 * @param string $delimiter
 * @param string $str
 * @return array
 */
function explode_trim( $delimiter = ',', $str ) {

	if ( is_string($delimiter) )
	{
		$str = trim(preg_replace('|\\s*(?:' . preg_quote($delimiter) . ')\\s*|', $delimiter, $str));

		return explode($delimiter, $str);
	}

	return array();
}


/**
 * string_rest()
 *
 * @param string $strString
 * @param string $strNeedle
 * @return string
 */
function string_rest( $strString, $strNeedle ) {

	$intPos = strpos( $strString, $strNeedle );
	
	if ( $strNeedle )
	{
		$intNeedleLength = strlen($strNeedle);
		$intPos = $intPos + strlen($strNeedle);
	}

	return substr( $strString, $intPos );
}


/**
 * string_start()
 *
 * @param string $strString
 * @param string $strNeedle
 * @return string
 */
function string_start( $strString, $strNeedle ) {

	$intPos = strpos( $strNeedle, $strString );
	
	return substr( $strString, 0, $intPos );
}



/**
 * SPECIAL FUNCTIONS
 */


/**
 * getBreadcrumb()
 *
 * @param string $strCat
 */
function getBreadcrumb() {

	$strReturn = ucfirst(PAGE_ID);

	/*
	if ( $strCat === PAGE_ID )
	{
		$strReturn = 'active';
	}
	else if ( $strCat === array_shift( explode( "_", PAGE_ID ) ) )
	{
		$strReturn = 'trail';
	}
	*/
	return $strReturn;
}


/**
 * doIMG
 * 
 * Alias fuer Kompatibilitaet aelterer Versionen von siteSlender
 * 
 * Detaillierte Informationen zur Nutzung und der neuen Version im Kommentar 
 * der Funktion showImage()
 *  
 * @param string $filename
 * @param string $alternative
 * @param string $style 
 * @uses showImage()
 * @return string HTML-Output from showImage()
 */
function doIMG($filename = '', $alternative = '', $style = '') {
    
    return showImage($filename, '', $style, $alternative);
}


?>