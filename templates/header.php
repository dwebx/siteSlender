<?php
function getmicrotime() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}
$timeStart = getmicrotime();

header('Content-type: text/html; charset=utf-8');
/*echo '<?xml version="1.0" encoding="utf-8"?>'."\n";*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<?php echo getMetaTitle(); ?>

	<?php echo getMetaDescription(); ?>
	<?php echo getMetaKeywords(); ?>
	<meta name="Author" content="<?php echo $GLOBALS['company']['owner']; ?>, <?php echo $GLOBALS['company']['firm_short']; ?>, <?php echo $GLOBALS['company']['homepage']; ?>" />
	<meta name="Generator" content="<?php echo CMS_NAME;?> <?php echo CMS_VERSION; ?> <?php echo CMS_DESC; ?>" />
	<meta name="Robots" content="index, follow" />

	<link rel="Stylesheet" type="text/css" href="<?php echo DOC_ROOT; ?>themes/<?php echo STYLE ;?>/style.css" />
	<?php
		/* check for Internet Explorer 6 or lower to load css fix */
		$strBrowser = $_SERVER['HTTP_USER_AGENT']; 
		$strPattern = '/.*MSIE ([0-9])\..*/';
		$strMatch = preg_match($strPattern, $strBrowser);
		
		if ( $strMatch ) 
		{
			$strMatch = preg_replace($strPattern, '\1', $strBrowser);
			
			switch ( (int)$strMatch )
			{
				case ( 8 > (int)$strMatch ) :
						echo sprintf('<link rel="Stylesheet" type="text/css" href="%sthemes/%s/style_ie7fix.css" />'."\n", DOC_ROOT, STYLE);
						break;
				default:
			}
		}
	?>
	<link rel="stylesheet" href="<?php echo DOC_ROOT . DIR_INCLUDES; ?>opentip/opentip_custom.css" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo DOC_ROOT . DIR_INCLUDES; ?>functions.js" ></script>	

	<link rel="SHORTCUT ICON" type="image/x-icon" href="<?php echo DOC_ROOT . DIR_IMAGES; ?>favicon.ico" />

	<!-- Google Analytics start //-->
	<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-32126429-1']);
	_gaq.push(['_trackPageview']);
	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	</script>
	<!-- Google Analytics end //-->
	
</head>

<body<?php echo ' class="'.PAGE_ID.'"'; ?>>
<div class="anchor"><a name="top" class="anchor"></a></div>

<div id="wrapper">

	<div id="header">
		<div class="inside">
		
		<div id="navService">
			<span class="socialize">
				<a title="auf facebook teilen" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $GLOBALS['company']['url']; ?><?php echo urlencode( str_replace( "./", "/", getLink(PAGE_ID) ) ); ?>&t=<?php echo urlencode( getMetaDescription() ); ?>" target="_blank" rel="nofollow">
					<?php echo showImage('fb_like.png'); ?>
				</a>
				<a title="Google +1" href="https://plusone.google.com/_/+1/confirm?hl=de&url=<?php echo $GLOBALS['company']['url']; ?><?php echo urlencode( str_replace( "./", "/", getLink(PAGE_ID) ) ); ?>" target="_blank" rel="nofollow">
					<?php echo showImage('google+1.png'); ?>
				</a>
				</span>
		</div>
			
		<div id="logo">
			<a title="Home" href="<?php echo getLink($GLOBALS['config']['default_page']) ?>">
				<img alt="Logo" src="<?php echo DOC_ROOT; ?>images/logo.png" />
			</a>
			<img id="flag" alt="Flagge" src="<?php echo DOC_ROOT; ?>images/flag.png" />
		</div>


		<?php if ($GLOBALS['config']['menu_languages']) : ?>
		<div id="navLanguage">
			<a title="DE" href="<?php echo linkLanguage('de'); ?>"><img alt="de" src="<?php echo DOC_ROOT; ?>images/de.gif" /></a>
			<a title="EN" href="<?php echo linkLanguage('en'); ?>"><img alt="en" src="<?php echo DOC_ROOT; ?>images/en.gif" /></a>
		</div>
		<?php endif ; ?>


		</div>
	</div>
	
	<div id="container">
	
		<div id="nav">
			<ul class="lev_1">
				<li class="first"><a title="Über FØRST" href="<?php echo getLink('about') ?>" class="<?php echo navActive('about'); ?>">Über FØRST</a></li>
				<li><a title="Schenken" href="<?php echo getLink('schenken') ?>" class="<?php echo navActive('schenken'); ?>">Schenken</a></li>
				<li><a title="Gutschein" href="<?php echo getLink('gutschein') ?>" class="<?php echo navActive('gutschein'); ?>">Gutschein</a></li>
				<li><a title="Galerie" href="<?php echo getLink('galerie') ?>" class="<?php echo navActive('galerie'); ?>">Galerie</a></li>
				<li class="last"><a title="Bestellen" href="<?php echo getLink('bestellen') ?>" class="<?php echo navActive('bestellen'); ?>">Bestellen</a></li>
			</ul>
		</div>
		<div class="clear"></div>

<?php ?> 