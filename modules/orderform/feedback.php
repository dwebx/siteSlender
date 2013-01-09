<?php
/**
 * contactform/feedback.php 
 * 
 * This script is a part of the 'm_formular' module to handle contact forms 
 * of web pages. 
 * 
 * This script is the formular itself. 
 *  
 * @package contactform
 * @uses core functions of siteSlender includes/functions.php
 * @author Torsten Alrecht (Teo) <teo@dwebx.de>
 * @version 1.03.00 2011-11-13 15:00 CET
 * @copyright 2007 dwebx.de
 */

// Pruefung auf korrekten Aufruf innerhalb der Webseite
if (!defined('INDEX') && !defined('FEEDBACK'))
{
	header("Location: http://www.foerst-step.com/kontakt.html");
	die;
}

// Config Feedback einbinden
include( $GLOBALS['config']['base'].DIR_MODULES.'orderform/config'.FILE_EXT );

// Script Feedback einbinden
include( $GLOBALS['config']['orderform']['work_dir'] . "script_feedback" . FILE_EXT);

// Script Seite fuer den Versand festlegen falls nicht uebergeben
if ( !post('send', 0) || !get('send', 0) ) { $_POST['send'] = getLink($GLOBALS['config']['orderform']['send_page']); }
if ( get('send', 0) ) { $_POST['send'] = get('send'); }

?>
		<?php if (!post('Seitentitel', 0)) $_POST['Seitentitel'] = "Kontakt"; ?>
		<?php if ($GLOBALS['config']['orderform']['show_page_title']) echo sprintf("<h1>%s</h1>", post('Seitentitel') ); ?>

		<form id="fb" action="<?php echo post('send')?>" onsubmit="return fbcheck();" method="post" enctype="multipart/form-data">

		<div class="hidden">
			<input type="hidden" name="Formulartitel" value="<?php if (post('Formulartitel', 0)) echo post('Formulartitel'); else { $_POST['Formulartitel'] = $GLOBALS['config']['orderform']['form_title']; echo post('Formulartitel'); }?>" />
			<input type="hidden" name="mandatory" value="<?php if (post('mandatory', 0)) echo post('mandatory'); else { $_POST['mandatory'] = implode(", ", array_merge($GLOBALS['config']['orderform']['mandatory_fields'], $GLOBALS['config']['orderform']['mandatory_shipto_fields'], $GLOBALS['config']['orderform']['mandatory_coupon_fields'] ) ); echo post('mandatory'); }?>" />
			<input type="hidden" name="Empfaenger" value="<?php if (post('Empfaenger', 0)) echo post('Empfaenger'); else echo $GLOBALS['company']['email']; ?>" />
			<input type="hidden" name="Umleitung" value="<?php if (post('Umleitung', 0)) echo post('Umleitung'); else echo $GLOBALS['company']['homepage']; ?>" />
		</div>

			<?php if ($GLOBALS['config']['orderform']['show_form_title']) echo sprintf("<h3>%s</h3>", post('Formulartitel') ); ?>

		<div class="floatleft w50p lcol">
			<div class="inside">

			<h3>Meine Daten:</h3>
			<?php if ($GLOBALS['config']['orderform']['show_title']) : ?>
			<!-- ** ANREDE ** //-->
			<div class="anrede">
				<div class="floatleft w33p">
					<label style="display: block; margin-top: 10px;">Anrede</label>
				</div>
				<div class="floatleft w33p">
					<input type="radio" name="Anrede" value="Frau" id="Aradio1" class="radio" <?php if ( "Frau" == post('Anrede') ) echo 'checked="checked"'; ?> />
					<label for="Aradio1">Frau</label>
				</div>
				<div class="w33p">
					<input type="radio" name="Anrede" value="Herr" id="Aradio2" class="radio" <?php if ( "Herr" == post('Anrede') ) echo 'checked="checked"'; ?> />
					<label for="Aradio2">Herr</label>
				</div>
			</div>
			<?php endif; ?>
			<!-- ** NAME ** //-->
			<input type="text" id="Name" name="Name" value="<?php echo post('Name'); ?>" class="text" /><br />
			<label for="Name"><?php echo check_required('Name', 'Nachname'); ?></label><br />
			<?php if ($GLOBALS['config']['orderform']['show_forename']) : ?>
			<!-- ** VORNAME ** //-->
			<input type="text" id="Vorname" name="Vorname" value="<?php echo post('Vorname'); ?>" class="text" /><br />
			<label for="Vorname"><?php echo check_required('Vorname', 'Vorname'); ?></label><br />
			<?php endif; ?>
			<?php if ($GLOBALS['config']['orderform']['show_address']) : ?>
			<!-- ** ANSCHRIFT ** //-->
			<input type="text" id="Anschrift" name="Anschrift" value="<?php echo post('Anschrift'); ?>" class="text" /><br />
			<label fir="Anschrift"><?php echo check_required('Anschrift', 'Anschrift'); ?></label><br />
			<!-- ** PLZ/ORT ** //-->
			<input type="text" id="PLZ" name="PLZ" size="5" value="<?php echo post('PLZ'); ?>" maxlength="5" class="text zip" />&nbsp;
			<input type="text" id="Ort" name="Ort" size="15" value="<?php echo post('Ort'); ?>" class="text city" /><br />
			<label for="PLZ"><?php echo check_required('PLZ', 'PLZ'); ?></label><label> und </label><label for="Ort"><?php echo check_required('Ort', 'Ort'); ?></label><br />
			<?php echo getCountries("Land"); ?><br />
			<label for="Land"><?php echo check_required('Land', 'Land'); ?></label><br />
			<?php endif; ?>

			<?php if ($GLOBALS['config']['orderform']['show_email']) : ?>
			<!-- ** Email ** //-->
			<input type="text" id="Email" name="Email" value="<?php echo post('Email'); ?>" class="text" /><br />
			<label for="Email"><?php echo check_required('Email', 'Emailadresse'); ?></label><br />
			<?php endif; ?>
			<?php if ($GLOBALS['config']['orderform']['show_phone']) : ?>
			<!-- ** TELEFON ** //-->
			<input type="text" id="Tel" name="Tel" value="<?php echo post('Tel'); ?>" class="text" /><br />
			<label for="Tel"><?php echo check_required('Tel', 'Telefon (für Rückfragen)'); ?></label><br />
			<?php endif; ?>
			<?php if ($GLOBALS['config']['orderform']['show_mobile']) : ?>
			<!-- ** HANDY ** //-->
			<input type="text" id="Handy" name="Handy" value="<?php echo post('Handy'); ?>" class="text" /><br />
			<label for="Handy"><?php echo check_required('Handy', 'Mobil'); ?></label><br />
			<?php endif; ?>
			<?php if ($GLOBALS['config']['orderform']['show_availability']) : ?>
			<!-- ** ERREICHBARKEIT ** //-->
			<span class="label">Erreichbarkeit</span>
			<span class="label">
				<input type="text" name="von" value="<?php echo post('von'); ?>" maxlength="5" class="text time" />
				<input type="text" name="bis" value="<?php echo post('bis'); ?>" class="text time" />&nbsp;Uhr<br />
				<label>von</label>&nbsp;<label>bis</label><br />
			<?php endif; ?>

			<input type="checkbox" id="Lieferadresse" name="Lieferadresse" value="Lieferadresse" class="checkbox" <?php if ( "Lieferadresse" == post('Lieferadresse') ) echo 'checked="checked"'; ?>/>
			<label for="Lieferadresse" class="highlight">Lieferadresse, falls abweichend</label><br />
			<br />
			<div id="shipto" style="display: none;">
				<h3>Lieferadresse:</h3>
				<!-- ** ANREDE ** //-->
				<div class="anrede">
					<div class="floatleft w33p">
						<label style="display: block; margin-top: 10px;">Anrede</label>
					</div>
					<div class="floatleft w33p">
						<input type="radio" name="LAnrede" value="Frau" id="LAradio1" class="radio" <?php if ( "Frau" == post('LAnrede') ) echo 'checked="checked"'; ?> />
						<label for="LAradio1">Frau</label>
					</div>
					<div class="w33p">
						<input type="radio" name="LAnrede" value="Herr" id="LAradio2" class="radio" <?php if ( "Herr" == post('LAnrede') ) echo 'checked="checked"'; ?> />
						<label for="LAradio2">Herr</label>
					</div>
				</div>
				<!-- ** NAME ** //-->
				<input type="text" id="LName" name="LName" value="<?php echo post('LName'); ?>" class="text" /><br />
				<label for="LName"><?php echo check_required('LName', 'Nachname'); ?></label><br />
				<!-- ** VORNAME ** //-->
				<input type="text" id="LVorname" name="LVorname" value="<?php echo post('LVorname'); ?>" class="text" /><br />
				<label for="LVorname"><?php echo check_required('LVorname', 'Vorname'); ?></label><br />
				<!-- ** ANSCHRIFT ** //-->
				<input type="text" id="LAnschrift" name="LAnschrift" value="<?php echo post('LAnschrift'); ?>" class="text" /><br />
				<label fir="LAnschrift"><?php echo check_required('LAnschrift', 'Anschrift'); ?></label><br />
				<!-- ** PLZ/ORT ** //-->
				<input type="text" id="LPLZ" name="LPLZ" size="5" value="<?php echo post('LPLZ'); ?>" maxlength="5" class="text zip" />&nbsp;
				<input type="text" id="LOrt" name="LOrt" size="15" value="<?php echo post('LOrt'); ?>" class="text city" /><br />
				<label for="LPLZ"><?php echo check_required('LPLZ', 'PLZ'); ?></label><label> und </label><label for="Ort"><?php echo check_required('LOrt', 'Ort'); ?></label><br />
				<?php echo getCountries("LLand"); ?><br />
				<label for="LLand"><?php echo check_required('LLand', 'Land'); ?></label><br />
			</div>
			<br />

			<p class="explanation">
				<?php echo $GLOBALS['config']['mandatory_explain']; ?> 
			</p>

			</div>
		</div>
		<div class="w50p rcol">
			<div class="inside">

			<h3>Bestellung und Zahlung:</h3>
			<!-- SCHUHDETAILS //-->
			<div class="schuh">
				<br />
				<label><b>Versilberung</b></label><br />
				<input type="radio" id="Mradio1" name="Menge" value="Versilberung eines Kinderschuhs" class="radio" <?php if ( "Versilberung eines Kinderschuhs" == post('Menge') ) echo 'checked="checked"'; ?>/> 
				<label for="Mradio1">eines Kinderschuhs zum Preis von <span class="price">110</span> EUR (inkl. <span class="tax">19% MwSt u. </span>Versand)</label><br />
				<input type="radio" id="Mradio2" name="Menge" value="Versilberung zweier Kinderschuhe" class="radio" <?php if ( "Versilberung zweier Kinderschuhe" == post('Menge') ) echo 'checked="checked"'; ?>/>
				<label for="Mradio2">zweier Kinderschuhe zum Preis von <span class="price">200</span> EUR (inkl. <span class="tax">19% MwSt u. </span>Versand)</label><br />
				<br />
				<br />
				<label><b>Vergoldung</b></label><br />
				<input type="radio" id="Mradio3" name="Menge" value="Vergoldung eines Kinderschuhs" class="radio" <?php if ( "Vergoldung eines Kinderschuhs" == post('Menge') ) echo 'checked="checked"'; ?>/> 
				<label for="Mradio3">eines Kinderschuhs zum Preis von <span class="price">300</span> EUR (inkl. <span class="tax">19% MwSt u. </span>Versand)</label><br />
				<input type="radio" id="Mradio4" name="Menge" value="Vergoldung zweier Kinderschuhe" class="radio" <?php if ( "Vergoldung zweier Kinderschuhe" == post('Menge') ) echo 'checked="checked"'; ?>/>
				<label for="Mradio4">zweier Kinderschuhe zum Preis von <span class="price">500</span> EUR (inkl. <span class="tax">19% MwSt u. </span>Versand)</label><br />
				<br />
				<input type="checkbox" id="Gutschein" name="Gutschein" value="Gutschein" class="checkbox" <?php if ( "Gutschein" == post('Gutschein') ) echo 'checked="checked"'; ?>/>
				<label for="Gutschein" class="highlight">Ich benötige hierüber vorab einen Geschenkgutschein.</label>
			</div>
			<div class="clear"></div>
			<br />

			<div id="coupon" style="display: none;">
				<h3>Angaben zum Beschenkten:**</h3>
				<!-- ** ANREDE ** //-->
				<div class="anrede">
					<div class="floatleft w33p">
						<label style="display: block; margin-top: 10px;">Anrede</label>
					</div>
					<div class="floatleft w33p">
						<input type="radio" name="GAnrede" value="Frau" id="GAradio1" class="radio" <?php if ( "Frau" == post('GAnrede') ) echo 'checked="checked"'; ?> />
						<label for="GAradio1">Frau</label>
					</div>
					<div class="w33p">
						<input type="radio" name="GAnrede" value="Herr" id="GAradio2" class="radio" <?php if ( "Herr" == post('GAnrede') ) echo 'checked="checked"'; ?> />
						<label for="GAradio2">Herr</label>
					</div>
				</div>
				<!-- ** NAME ** //-->
				<input type="text" id="GName" name="GName" value="<?php echo post('GName'); ?>" class="text" /><br />
				<label for="GName"><?php echo check_required('GName', 'Nachname'); ?></label><br />
				<!-- ** VORNAME ** //-->
				<input type="text" id="GVorname" name="GVorname" value="<?php echo post('GVorname'); ?>" class="text" /><br />
				<label for="GVorname"><?php echo check_required('GVorname', 'Vorname'); ?></label><br />
				<!-- ** ANSCHRIFT ** //-->
				<input type="text" id="GAnschrift" name="GAnschrift" value="<?php echo post('GAnschrift'); ?>" class="text" /><br />
				<label fir="GAnschrift"><?php echo check_required('GAnschrift', 'Anschrift'); ?></label><br />
				<!-- ** PLZ/ORT ** //-->
				<input type="text" id="GPLZ" name="GPLZ" size="5" value="<?php echo post('GPLZ'); ?>" maxlength="5" class="text zip" />&nbsp;
				<input type="text" id="GOrt" name="GOrt" size="15" value="<?php echo post('GOrt'); ?>" class="text city" /><br />
				<label for="GPLZ"><?php echo check_required('GPLZ', 'PLZ'); ?></label><label> und </label><label for="Ort"><?php echo check_required('GOrt', 'Ort'); ?></label><br />
				<?php echo getCountries("GLand"); ?><br />
				<label for="GLand"><?php echo check_required('GLand', 'Land'); ?></label><br />
				<br />
				<p class="explanation">
				<span class="highlight">** Damit wir die reibungslose Einlösung des Geschenkgutscheins 
				sicherstellen können, bitten wir Sie um Namen und Anschrift 
				des/r Beschenkten.</span></p>
			</div>
			<br />

			<h4>ich zahle per</h4>
			<!-- ZAHLUNGSART //-->
			<div class="zahlung">
				<input type="radio" name="Zahlungsart" value="PayPal" id="Zradio1" class="radio" <?php if ( "Paypal" == post('Zahlungsart') ) echo 'checked="checked"'; ?> />
				<label for="Zradio1">Paypal</label><br />
				<input type="radio" name="Zahlungsart" value="Überweisung" id="Zradio2" class="radio" <?php if ( "Überweisung" == post('Zahlungsart') ) echo 'checked="checked"'; ?> />
				<label for="Zradio2">Überweisung (Vorkasse)</label><br />
				<input type="radio" name="Zahlungsart" value="Nachnahme" id="Zradio3" class="radio" <?php if ( "Nachnahme" == post('Zahlungsart') ) echo 'checked="checked"'; ?> />
				<label for="Zradio3">Nachnahme (zzgl. 7,80 EUR Nachnahmeaufwand)</label>
			</div>
			<br />

			<?php if ($GLOBALS['config']['orderform']['show_subject']) : ?>
			<p>
			<input type="text" name="Thema" value="<?php echo post('Thema'); ?>" class="text subject" /><br />
			<label>Thema</label>
			</p>
			<?php endif; ?>
			<p>
			<textarea id="Inhalt" name="Inhalt" cols="50" rows="3" class="textarea message <?php if (!post('Inhalt') ||  post('Inhalt') == $GLOBALS['config']['orderform']['inhalt_default']  ) echo "ghost"; ?>"><?php if ( post('Inhalt') ) : ?>
<?php echo post('Inhalt'); ?>
<?php else : ?>
<?php echo $GLOBALS['config']['orderform']['inhalt_default']; ?>
<?php endif; ?></textarea><br />
			<label>Nachricht an uns</label>

			<p>
			<input type="checkbox" name="confirmTerms" id="confirmTerms" class="inline" />&nbsp;&nbsp;
			<label for="confirmTerms"><?php echo $GLOBALS['config']['orderform']['label_terms']; ?></label>
			</p>

			</div>
		</div>

		<div class="clear"></div>
		<hr />

		<div class="floatleft w50p lcol">
			<div class="inside">

			<?php if ($GLOBALS['config']['orderform']['show_disclaimer']) : ?>
			<h3>Vertraulichkeit und Datenschutz</h3>
			<p class="explanation">
				Der Anbieter geht verantwortungsvoll mit den ihm
				überlassenen persönlichen Daten um. Personenbezogene
				Daten werden unter Beachtung der geltenden nationalen und
				europäischen Datenschutzvorschriften, insbesondere des
				Telemediengesetzes, erhoben, verarbeitet und
				genutzt. Einzelheiten zu den datenschutzrelevanten
				Vorgängen sind dem
				<a href="<?php echo getLink('impressum') ?>#datenschutz">Datenschutzhinweis</a>
				des Anbieters zu entnehmen.
			</p>
			<?php endif; ?>
			
			</div>
		</div>
		<div class="w50p rcol">
			<div class="inside">
			<!-- CAPTCHA //-->
			<?php include( DIR_INCLUDES . 'captcha/captcha.php'); ?>
			
			<p></p>
			
			<input type="hidden" id="orderCurrency" name="orderCurrency" value="<?php echo $GLOBALS['config']['orderform']['currency_default']; ?>" />
			<input type="hidden" id="orderItem" name="orderItem" value="0.00" />
			<input type="hidden" id="orderPayment" name="orderPayment" value="0.00" />
			<input type="hidden" id="orderShipping" name="orderShipping" value="0.00" />
			<input type="hidden" id="orderTax" name="orderTax" value="0.00" />
			<input type="hidden" id="orderTotal" name="orderTotal" value="0.00" />
			<div id="tableSummary"></div>
			<div class="clear"></div>
			
			<p>
				<input type="submit" name="submit" value="KAUFEN" class="button submit" />
			</p>

			</div>
		</div>

		</form>

<!-- JavaScript FORMCHECK - BEGIN //-->
<script type="text/javascript">
<!--//--><![CDATA[//><!--

strWorkDir = '<?php echo $GLOBALS['config']['orderform']['work_dir']; ?>';

IncludeJavaScript( strWorkDir + 'js/orderform.js');
IncludeJavaScript( '<?php echo $GLOBALS['config']['base'] . DIR_INCLUDES; ?>md5.js');

var strTextDefault = "<?php echo $GLOBALS['config']['orderform']['inhalt_default']; ?>";


function fbcheck() {

	errors = "";
	
	if (document.forms) f = eval ("document.forms['fb']");

<?php
		$arrFields  = $GLOBALS['config']['orderform']['mandatory_fields'];
		$intArrSize = sizeof($arrFields);

		for ($i = 0; $i < $intArrSize; $i++)
		{
			$arrFields[$i] = ltrim($arrFields[$i]);
			$strLoop = 'if';
			echo "	$strLoop (f['{$arrFields[$i]}'].value == '') { errors = errors + '- {$arrFields[$i]}\\n'; }\n";
		}


		echo "if (f['Lieferadresse'].checked == true) {\n";
		echo "var shiptoError = false\n";		
		$arrFields  = $GLOBALS['config']['orderform']['mandatory_shipto_fields'];
		$intArrSize = sizeof($arrFields);

		for ($i = 0; $i < $intArrSize; $i++)
		{
			$arrFields[$i] = ltrim($arrFields[$i]);
			echo "	if (f['{$arrFields[$i]}'].value == '') { if( shiptoError == false) { errors = errors + '\\nAngaben zur Lieferadresse\\n'; } errors = errors + '- " . substr($arrFields[$i], 1) . "\\n'; shiptoError = true; }\n";
		}
		echo "}\n\n";


		echo "if (f['Gutschein'].checked == true) {\n";
		echo "var couponError = false\n";
		$arrFields  = $GLOBALS['config']['orderform']['mandatory_coupon_fields'];
		$intArrSize = sizeof($arrFields);

		for ($i = 0; $i < $intArrSize; $i++)
		{
			$arrFields[$i] = ltrim($arrFields[$i]);
			echo "	if (f['{$arrFields[$i]}'].value == '') { if( couponError == false) { errors = errors + '\\nAngaben zum Gutschein\\n'; } errors = errors + '- " . substr($arrFields[$i], 1) . "\\n'; couponError = true; }\n";
		}
		echo "}\n\n";

		echo "	if( shiptoError == true || couponError == true ) { errors = errors + '\\n'; }\n";

?>
<?php
		$arrContactFormFields = array(
			'phone'=>'Tel', 
			'mobile'=>'Handy', 
			'email'=>'Email'
		);
		$arrCompareContactFormFields = array();
		foreach ( $arrContactFormFields AS $strKey => $strValue )
		{
			if ( $GLOBALS['config']['orderform']['show_'.$strKey] )
			{
				$arrCompareContactFormFields[] = "f['$strValue'].value == ''";
			}
		}
		$strCompareContactFormFields = implode( " && ", $arrCompareContactFormFields );
?>
	<?php if ( 0 < strlen($strCompareContactFormFields) ) :	
	?>if (<?php echo $strCompareContactFormFields; ?>) { errors = errors + '- Kontaktmöglichkeit\n'; }
	<?php endif ; 
	?>

	var val = f.getInputs('radio', 'Menge').find(function(r){return r.checked});
	if ( undefined == val )
	{
		errors = errors + '- Artikel\n'; 
	}

	val = f.getInputs('radio', 'Zahlungsart').find(function(r){return r.checked});
	if ( undefined == val )
	{
		errors = errors + '- Zahlungsart\n'; 
	}

	if ( document.getElementById('confirmTerms') )
	{
		if ( !f['confirmTerms'].checked ) { errors = errors + '- AGB bestätigen\n'; }
	}

	if ( document.getElementById('Captcha') )
	{
		if ( "" == f['Sicherheitsabfrage'].value ) { errors = errors + '- Sicherheitsabfrage\n'; }
		else if ( MD5 ( f['Sicherheitsabfrage'].value ) != f['Encoded'].value ) { errors = errors + '- Sicherheitsabfrage inkorrekt\n'; }
	}

	if ( 0 < errors.length ) 
	{ 
		alert( 'Folgende Felder müssen noch ausgefüllt werden:\n\n' + errors + '\nVielen Dank.'); 
		return false;	
	}
	else 
	{ 
		return true;
	}
}

//--><!]]>
</script>
<!-- JavaScript FORMCHECK - END //-->

<?php ?>