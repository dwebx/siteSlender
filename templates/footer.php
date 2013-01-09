<?php ?>

	<div id="push"></div> 
</div>

</div>

<div class="clear"></div>

<div id="footer">
	<div class="inside">

		<div id="navService">
			<a class="first" href="<?php echo getLink('kontakt') ?>" title="Kontakt">Kontakt</a> |
			<a href="<?php echo getLink('faq') ?>" title="FAQ">FAQ</a> |
			<a href="<?php echo getLink('agb') ?>" title="AGB">AGB</a> |
			<a class="last" href="<?php echo getLink('impressum') ?>" title="Impressum">Impressum</a> 
			<!--<a class="last" href="<?php echo getLink('agb') ?>" title="AGB">AGB</a>//-->
			<span>
			&copy;<?php if ( $GLOBALS['company']['founding'] == date("Y") ) : ?>
				<?php echo $GLOBALS['company']['founding']; ?>
			<?php else : ?>
				<?php echo $GLOBALS['company']['founding']; ?> - <?php echo date("Y"); ?>
			<?php endif; ?> 
			<?php echo $GLOBALS['company']['firm_short']; ?>  
			<?php echo $GLOBALS['company']['firm2']; ?></span>
		</div>

		
	</div>
</div>

<?php /* Lightbox Module bof */ ?>
<?php if( "bestellung" == PAGE_ID ) { include ( DIR_INCLUDES . PAGE_ID . '-formular' . FILE_EXT ); }  ?>
<?php if( "preisliste" == PAGE_ID ) { include ( DIR_INCLUDES . PAGE_ID . FILE_EXT ); }  ?>
<?php /* Lightboxmodule eof */ ?>

<?php 
$timeEnd = getmicrotime();
define('RENDER_TIME', number_format($timeEnd - $timeStart, 4, ",", ".") );
?>
<!-- site generated with <?php echo CMS_NAME ; ?> <?php echo CMS_VERSION; ?> <?php echo CMS_DESC; ?> in <?php echo RENDER_TIME; ?> seconds //-->

</body>

</html>
<?php ?>