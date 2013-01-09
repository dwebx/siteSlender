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
include( $GLOBALS['config']['base'].DIR_MODULES.'inquiryform/config'.FILE_EXT );

// Script Feedback einbinden
include( $GLOBALS['config']['inquiryform']['work_dir'] . "script_feedback" . FILE_EXT);

// Script Seite fuer den Versand festlegen falls nicht uebergeben
if ( !post('send', 0) || !get('send', 0) ) { $_POST['send'] = getLink($GLOBALS['config']['inquiryform']['send_page']); }
if ( get('send', 0) ) { $_POST['send'] = get('send'); }

?>
		<?php if (!post('Seitentitel', 0)) $_POST['Seitentitel'] = "Kontakt"; ?>
		<?php if ($GLOBALS['config']['inquiryform']['show_page_title']) echo sprintf("<h1>%s</h1>", post('Seitentitel') ); ?>

		<form id="fb" action="<?php echo post('send')?>" onsubmit="return fbcheck();" method="post" enctype="multipart/form-data">

		<div class="hidden">
			<input type="hidden" name="Formulartitel" value="<?php if (post('Formulartitel', 0)) echo post('Formulartitel'); else { $_POST['Formulartitel'] = $GLOBALS['config']['inquiryform']['form_title']; echo post('Formulartitel'); }?>" />
			<input type="hidden" name="mandatory" value="<?php if (post('mandatory', 0)) echo post('mandatory'); else { $_POST['mandatory'] = implode(", ", $GLOBALS['config']['inquiryform']['mandatory_fields']); echo post('mandatory'); }?>" />
			<input type="hidden" name="Empfaenger" value="<?php if (post('Empfaenger', 0)) echo post('Empfaenger'); else echo $GLOBALS['company']['email']; ?>" />
			<input type="hidden" name="Umleitung" value="<?php if (post('Umleitung', 0)) echo post('Umleitung'); else echo $GLOBALS['company']['homepage']; ?>" />
		</div>

                <?php if ($GLOBALS['config']['inquiryform']['show_form_title']) echo sprintf("<h3>%s</h3>", post('Formulartitel') ); ?>

			<div class="floatleft w50p lcol">
				<div class="inside">

                <h3>Kontaktdaten:</h3>
				<?php if ($GLOBALS['config']['inquiryform']['show_title']) : ?>
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
				<label for="Name"><?php echo check_required('Name', 'Name'); ?></label><br />
				<?php if ($GLOBALS['config']['inquiryform']['show_forename']) : ?>
				<!-- ** VORNAME ** //-->
				<input type="text" id="Vorname" name="Vorname" value="<?php echo post('Vorname'); ?>" class="text" /><br />
				<label for="Vorname"><?php echo check_required('Vorname', 'Vorname'); ?></label><br />
				<?php endif; ?>
				<?php if ($GLOBALS['config']['inquiryform']['show_address']) : ?>
				<!-- ** ANSCHRIFT ** //-->
				<input type="text" id="Anschrift" name="Anschrift" value="<?php echo post('Anschrift'); ?>" class="text" /><br />
				<label fir="Anschrift"><?php echo check_required('Anschrift', 'Anschrift'); ?></label><br />
				<!-- ** PLZ/ORT ** //-->
				<input type="text" id="PLZ" name="PLZ" size="5" value="<?php echo post('PLZ'); ?>" maxlength="5" class="text zip" />&nbsp;
				<input type="text" id="Ort" name="Ort" size="15" value="<?php echo post('Ort'); ?>" class="text city" /><br />
				<label for="PLZ"><?php echo check_required('PLZ', 'PLZ'); ?></label><label> und </label><label for="Ort"><?php echo check_required('Ort', 'Ort'); ?></label><br />
				<?php endif; ?>
				 
				<?php if ($GLOBALS['config']['inquiryform']['show_phone']) : ?>
				<!-- ** TELEFON ** //-->
				<input type="text" id="Tel" name="Tel" value="<?php echo post('Tel'); ?>" class="text" /><br />
				<label for="Tel"><?php echo check_required('Tel', 'Telefon'); ?></label><br />
				<?php endif; ?>
				<?php if ($GLOBALS['config']['inquiryform']['show_mobile']) : ?>
				<!-- ** HANDY ** //-->
				<input type="text" id="Handy" name="Handy" value="<?php echo post('Handy'); ?>" class="text" /><br />
				<label for="Handy"><?php echo check_required('Handy', 'Mobil'); ?></label><br />
				<?php endif; ?>
				<?php if ($GLOBALS['config']['inquiryform']['show_email']) : ?>
				<!-- ** Email ** //-->
				<input type="text" id="Email" name="Email" value="<?php echo post('Email'); ?>" class="text" /><br />
				<label for="Email"><?php echo check_required('Email', 'Email'); ?></label><br />
				<?php endif; ?>
				<?php if ($GLOBALS['config']['inquiryform']['show_availability']) : ?>
				<!-- ** ERREICHBARKEIT ** //-->
				<span class="label">Erreichbarkeit</span>
				<span class="label">
					<input type="text" name="von" value="<?php echo post('von'); ?>" maxlength="5" class="text time" />
					<input type="text" name="bis" value="<?php echo post('bis'); ?>" class="text time" />&nbsp;Uhr<br />
					<label>von</label>&nbsp;<label>bis</label><br />
				<?php endif; ?>

				<br />
                <p class="explanation">
					<?php echo $GLOBALS['config']['mandatory_explain']; ?> 
                </p>
				<br />

				<?php if ($GLOBALS['config']['inquiryform']['show_disclaimer']) : ?>
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

				<h3>Angaben zum Schuh:</h3>
				<!-- SCHUHDETAILS 3 Spalten //-->
				<div class="schuh">
					<div class="floatleft w15p">
						<div class="inside">
		                <input type="text" id="Schuh-Gr" name="Schuh-Gr" value="<?php echo post('Schuh-Gr'); ?>" class="text" maxlength="4" /><br />
						<label for="Schuh-Gr">Größe</label>
						</div>
					</div>
					<div class="floatleft w15p">
						<div class="inside">
		                <input type="text" id="Anzahl" name="Anzahl" value="<?php echo post('Anzahl'); ?>" class="text" maxlength="2" /><br />
						<label for="Anzahl">Anzahl</label>
						</div>
					</div>
					<div class="w70p">
						<div class="inside" style="position: relative;">
							<div class="file">
								<input type="text" name="Dateiname" id="Dateiname" class="text" readonly="readonly" />
								<input type="file" name="Datei" id="Datei" class="realfile" />
								<label for="Datei">Bildupload <span class="explanation">(max. Dateigröße 2MB)</span></label>
							</div>
						</div>
					</div>
				</div>
				<div class="clear"></div>

				<br />
				<h4>Gewünschte Veredelungsart:</h4>
				<!-- VEREDELUNGSART 3 Spalten Radiobutton //-->
				<br />
				<div class="veredelung">
					<div class="floatleft w33p">
						<label for="Vradio1"><?php echo showImage('art_glanzsilber.png'); ?></label><br />
		                <input type="radio" name="Veredelungsart" value="Glanzsilber" id="Vradio1" class="radio" <?php if ( "Glanzsilber" == post('Veredelungsart') ) echo 'checked="checked"'; ?> /><br />
						<label for="Vradio1">Glanzsilber</label>
					</div>
					<div class="floatleft w33p">
						<label for="Vradio2"><?php echo showImage('art_altsilber.png'); ?></label><br />
		                <input type="radio" name="Veredelungsart" value="Altsilber" id="Vradio2" class="radio" <?php if ( "Altsilber" == post('Veredelungsart') ) echo 'checked="checked"'; ?> /><br />
						<label for="Vradio2">Altsilber</label>
					</div>
					<div class="w33p">
						<label for="Vradio3"><?php echo showImage('art_gold.png'); ?></label><br />
		                <input type="radio" name="Veredelungsart" value="Gold" id="Vradio3" class="radio" <?php if ( "Gold" == post('Veredelungsart') ) echo 'checked="checked"'; ?> /><br />
						<label for="Vradio3">Gold</label>
					</div>
				</div>
				<?php if ($GLOBALS['config']['inquiryform']['show_subject']) : ?>
                <p>
                <input type="text" name="Thema" value="<?php echo post('Thema'); ?>" class="text subject" /><br />
				<label>Thema</label>
                </p>
				<?php endif; ?>
				<p>
				<textarea id="Inhalt" name="Inhalt" cols="50" rows="7" class="textarea message <?php if (!post('Inhalt') ||  post('Inhalt') == $GLOBALS['config']['inquiryform']['inhalt_default']  ) echo "ghost"; ?>"><?php if ( post('Inhalt') ) : ?>
<?php echo post('Inhalt'); ?>
<?php else : ?>
<?php echo $GLOBALS['config']['inquiryform']['inhalt_default']; ?>
<?php endif; ?></textarea><br />
				<label>Nachricht an uns</label>
				<!--<br />
				<br />
				<span class="explanation">Bitte beschreiben Sie uns das Material und die Oberfläche des 
				Schuhs, wie etwa Lackleder, Glattleder, Wildleder etc. Diese 
				Beschreibung und eventuell auch ein Bild des betreffenden Schuhs 
				hilft uns dabei, Ihnen die am besten geeignete 
				Veredelungsvariante vorzuschlagen.</span>
				</p>//-->
				<!-- CAPTCHA //-->
				<?php include( DIR_INCLUDES . 'captcha/captcha.php'); ?>
                <p>
                   <input type="submit" name="submit" value="SENDEN" class="button submit" />
                </p>

				</div>
			</div>
			</form>

<!-- JavaScript FORMCHECK - BEGIN //-->
<script type="text/javascript">
<!--//--><![CDATA[//><!--

strWorkDir = '<?php echo $GLOBALS['config']['inquiryform']['work_dir']; ?>';

IncludeJavaScript( strWorkDir + 'js/contactform.js');
IncludeJavaScript( '<?php echo $GLOBALS['config']['base'] . DIR_INCLUDES; ?>md5.js');

var strTextDefault = "<?php echo $GLOBALS['config']['inquiryform']['inhalt_default']; ?>";

// setzt den Dateinamen in das sichtbare Feld
$('Datei').observe('change', function(event) {
	$('Dateiname').value = Event.element(event).getValue();
});

// setzt den Fokus auf das echte Dateiuploadfeld
$('Dateiname').observe('focus', function(event) {
	$('Datei').activate();
});

// zeigt Standardinhalt (Hinweis) im Nachrichtenfeld
$('Inhalt').observe('focus', function(event) {
	element = Event.element(event);
	var strText = element.getValue().trim();
	if ( strText == strTextDefault )
	{
		element.value = '';
	}
	element.removeClassName('ghost');
});
$('Inhalt').observe('blur', function(event) {
	var strText = Event.element(event).getValue().trim();
	if ( 0 >= strText || strText == strTextDefault )
	{	
		Event.element(event).value = strTextDefault;
		Event.element(event).addClassName('ghost');
	}
});


function fbcheck() {

	errors = "";
	
	if (document.forms) f = eval ("document.forms['fb']");

<?php
		$arrFields  = split('[,]', post('mandatory') );
		$intArrSize = sizeof($arrFields);

		for ($i = 0; $i < $intArrSize; $i++)
		{
			$arrFields[$i] = ltrim($arrFields[$i]);
			#$strLoop = ($i == 0) ? 'if' : 'else if';
			$strLoop = 'if';
			//echo "	$strLoop (f['{$arrFields[$i]}'].value == '') { alert('Bitte {$arrFields[$i]} angeben. Vielen Dank'); }\n";
			echo "	$strLoop (f['{$arrFields[$i]}'].value == '') { errors = errors + '- {$arrFields[$i]}\\n'; }\n";

		}
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
			if ( $GLOBALS['config']['inquiryform']['show_'.$strKey] )
			{
				$arrCompareContactFormFields[] = "f['$strValue'].value == ''";
			}
		}
		$strCompareContactFormFields = implode( " && ", $arrCompareContactFormFields );
?>
	<?php if ( 0 < strlen($strCompareContactFormFields) ) :	
	?>if (<?php echo $strCompareContactFormFields; ?>) { errors = errors + '- Kontaktmöglichkeit\n'; }
	<?php endif ; 
	?>if ( f['Inhalt'].value == '' || f['Inhalt'].value == strTextDefault ) { errors = errors + '- Beschreibung\n'; }

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