<?php
/**
 * includes/init.php
 * 
 * @package siteSlender
 * @subpackage Core
 * @author Torsten Albrecht <t.albrecht@dwebx.de>
 * @version 1.0.2
 * @copyright 2003
 */

// Debug
if ( DEBUG ) { error_reporting(E_ALL); ini_set('display_errors','On'); }


/**
 * __autoload()
 * 
 * instant loading of classes on call
 * 
 * @param string $strClassName
 * @return bool
 */
function __autoload( $strClassName ) {

	$strClassFileName = strtolower($strClassName);
	$strClassFileName = str_replace('_', '/', $strClassFileName);
	
	$arrIncludePaths = array (
		DIR_INCLUDES,
		DIR_MODULES
	);
	
	foreach ( $arrIncludePaths AS $strValue ) 
	{
		if( file_exists( $strValue . $strClassFileName . FILE_EXT ) ) 
		{
			require_once( $strValue . $strClassFileName . FILE_EXT );
		}
	}
	
	return null;

} // END OF __autoload()


// framework functions
require ( DIR_INCLUDES . 'functions' . FILE_EXT );


// determine GDlib (version)
$arrGDlib = @gd_info();
$strGDVersion = preg_replace('/\D+/', '', ( $arrGDlib ? $arrGDlib['GD Version'] : 0 ) );
$intGDVersion = substr($strGDVersion, 0, 1);
define( "GDLIB", $intGDVersion );
# print "\n\n<!-- GDlib v{$intGDVersion} //-->\n\n";


// define page ID constant
if ( get( PID_IDENT, 0) )
{
	$pageID = get( PID_IDENT );

	/**
	 * redirect if defined
	 */
	if ( $GLOBALS['config']['redirects'] && 0 < count ($GLOBALS['config']['redirects'] ) )
	{
		foreach ( $GLOBALS['config']['redirects'] AS $key => $val )
		{
			$pageID = ( $key === $pageID ) ? $val : $pageID;
		}
	}

	// check file
	if ( !is_file( $pageID . FILE_EXT ) )
	{
		// error page
		$pageID = '404';
	}
}
else
{
	// default page
	$pageID = $GLOBALS['config']['default_page'];
}

define( 'PAGE_ID', $pageID );

// END OF define page ID constant

?>