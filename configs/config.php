<?php

// env

define('CMS_NAME', 'siteSlender'); 
define('CMS_HTML', sprintf("<i>%s<b>%s</b>%s</i>", "site", "S", "lender") );
define('CMS_DESC', 'PHP Website Framework by dwebx.com - Torsten Albrecht'); 
define('CMS_VERSION', '1.0.2'); 

define('DOC_ROOT', '/');
define('DIR_INCLUDES', 'includes/');
define('DIR_IMAGES', 'images/');
define('DIR_MODULES', 'modules/');
define('DIR_TEMPLATES', 'templates/');
define('DIR_UPLOADS', 'upload/'); // temp folder for attachements
define('FILE_EXT', '.php');
define('PID_IDENT', 'p'); // key for the page url parameter
define('STYLE', 'default'); // folder name of used style out of the templates folder
define('DEBUG', '1'); // displaying debug infos 0/1

$GLOBALS['config']['base']		= "./";
#$GLOBALS['config']['admin_base']	= "./../"; // necessary if admin panel is used
$GLOBALS['config']['real_url']		= true;
$GLOBALS['config']['url_suffix']	= ".html";
$GLOBALS['config']['default_page']	= "start"; // page displayed if no page url parameter is given
$GLOBALS['config']['menu_languages']	= false; // show language changer (true/false)


// general

$GLOBALS['company']['firm']		= "digital web experience";
$GLOBALS['company']['firm_short']	= "dwebx";
$GLOBALS['company']['firm_desc']	= "IT aus einer Hand";
$GLOBALS['company']['owner']		= "Torsten Albrecht";
$GLOBALS['company']['founding']		= "2000";
$GLOBALS['company']['register']		= "";
$GLOBALS['company']['regcourt']		= "";
$GLOBALS['company']['regid']		= "";
$GLOBALS['company']['url']		= "http://www.dwebx.com";
$GLOBALS['company']['homepage']		= $GLOBALS['company']['url'];
$GLOBALS['company']['email']		= "kontakt@dwebx.com";
$GLOBALS['company']['phone']		= "+49 (0) 365 7129000";
$GLOBALS['company']['fax']		= "";
$GLOBALS['company']['mobile']		= "+49 (0) 151 27557707";
$GLOBALS['company']['street']		= "";
$GLOBALS['company']['zip']		= "";
$GLOBALS['company']['city']		= "";
$GLOBALS['company']['tax_id']		= "";

$GLOBALS['company']['webmaster']	= "Torsten Albrecht";
$GLOBALS['company']['webmaster_email']	= "webmaster@dwebx.com";


// gdlib jpeg settings

#$GLOBALS['config']['jpeg_quality']           = "85";
#$GLOBALS['config']['image_car_width']        = "600";
#$GLOBALS['config']['image_offer_width']      = "480";
#$GLOBALS['config']['image_cache_width']['m'] = "160";
#$GLOBALS['config']['image_cache_width']['s'] = "75";


// META data

$GLOBALS['meta']['seo'] = array (
	'default' => array (
		'title' => '',
		'desc'	=> '',
		'keys'	=> ''
	)
);


// pages to redirect

$GLOBALS['config']['redirects'] = array (
	#'' => ''
); // page aliases, use key for old and value for new page e.g. "old_page" => "new_page"

?>
