<?php
/**
 * includes/captcha/captcha.php
 * 
 * grafische Sicherheitsabfrage fuer Formulare
 *
 * @package siteSlender
 * @subpackage Core 
 * @author Torsten Albrecht <t.albrecht@dwebx.com>
 * @version v0.49a - 2005-09-03 - 10:43 CET
 */

/**
 * TODO: klassenbasierter Aufbau
 */

/**
 * @uses md5() from includes/md5.js
 */
global $wert;

srand( (double)microtime() * 1000000 );

for ( $i = 1; $i <= 4; $i++ )
{
	$rand[$i] = rand() % 123 + 48;
	$rand[$i] = $rand[$i];

	if (
			( $rand[$i] > 57 && $rand[$i] < 65 )
		||
			( $rand[$i] > 90 && $rand[$i] < 97 )
		||
			( $rand[$i] > 122 )
		||
			( $rand[$i] == 73 ) // grosses i auslassen (Verwechslungsgefahr)
		||
			( $rand[$i] == 108 ) // kleines L auslassen (Verwechslungsgefahr)
	)
	{
		--$i;
		continue;
	}
	
    $char = chr( $rand[$i] );
    $wert = $wert.$char;
}

?>

<div id="Captcha" class="captcha">
<? if( function_exists( 'gd_info') ): ?>
	<img alt="" src="<?php echo DIR_INCLUDES; ?>captcha/captcha_img.php?wert=<?php echo urldecode(txt2ascii($wert)); ?>" class="floatleft" />
<? else : ?>
<p><span style="font-size: 16px; border: solid 1px #efefef; background-color: #606060; padding: 6px;"><?php echo $wert; ?></span></p>
<? endif; ?>
	<div class="caption">
		<label for="Sicherheitsabfrage">Bitte geben Sie diesen Code ein:</label><br />
		<input type="hidden" id="Encoded" name="wert" value="<?php echo md5($wert); ?>" />
		<input type="text" id="Sicherheitsabfrage" name="Sicherheitsabfrage" value="<?php echo post('Sicherheitsabfrage'); ?>" class="text" />
	</div>
</div>

<?php  ?>