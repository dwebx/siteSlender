/**
 * contactform/js/ptContactForm.js
 *
 * This script is a part of the 'contactform' module of siteSlender to handle
 * contact forms of web pages.
 *
 * The script uses functions of the javascript framework prototype
 *
 * @package contactform
 * @subpackage javascript
 * @require prototype 1.6.0.2
 * @author Torsten Alrecht (Teo) <teo@dwebx.de>
 * @copyright 2011 dwebx.de
 * @version 1.01.00 2012-09-24 03:35 CEST
 */



/**
 * Formularelemente
 */
var elInhalt = $('Inhalt');
var elGutschein = $('Gutschein');
var elLandRechnung = $('Land');
var elLieferadresse = $('Lieferadresse');
var elLandLieferadresse = $('LLand');

var elOrderItem = $('orderItem');
var elOrderPayment = $('orderPayment');
var elOrderShipping = $('orderShipping');
var elOrderTax = $('orderTax');
var elOrderTotal = $('orderTotal');
var elTableSummary = $('tableSummary');



/**
 * GENERAL FUNCTIONS
 */


/**
 * functions to highlight the labels of input fields getting focus
 * with prototype js framework
 */
$$('.text', '.textarea').each( function(el) {
	el.observe('focus', function(){
		el.addClassName('focus');
		$$('label[for=' + el.readAttribute('name') + ']').each( function(lb) {
			lb.addClassName('focus');
		});
	});
	el.observe('blur', function(){
		el.removeClassName('focus');
		$$('label[for=' + el.readAttribute('name') + ']').each( function(lb) {
			lb.removeClassName('focus');
		});
	});
});


/**
 * zeigt Standardinhalt (Hinweis) im Nachrichtenfeld
 */
elInhalt.observe('focus', function(event) {
	var element = Event.element(event);
	var strText = element.getValue().trim();
	if ( strText == strTextDefault )
	{
		element.value = '';
	}
	element.removeClassName('ghost');
});
elInhalt.observe('blur', function(event) {
	var strText = Event.element(event).getValue().trim();
	if ( 0 >= strText || strText == strTextDefault )
	{
		Event.element(event).value = strTextDefault;
		Event.element(event).addClassName('ghost');
	}
});



/**
 * ORDER FORM
 */


/**
 * Preisangaben
 */
var Preise = new Array();
/* Versilbern */
Preise["1x_silber_de"] = "110";
Preise["1x_silber_eu"] = "119";
Preise["1x_silber_out"] = "119";

Preise["2x_silber_de"] = "200";
Preise["2x_silber_eu"] = "209";
Preise["2x_silber_out"] = "200";

/* Vergolden */
Preise["1x_gold_de"] = "300";
Preise["1x_gold_eu"] = "300";
Preise["1x_gold_out"] = "260";

Preise["2x_gold_de"] = "500";
Preise["2x_gold_eu"] = "500";
Preise["2x_gold_out"] = "430";

Preise["PayPal"] = "0.00";
Preise["Überweisung"] = "0.00";
Preise["Nachnahme"] = "7.80";


/**
 * Versand
 */
function switchVersand ()
{
	/* Nachnahmeoption */
	if (
			( !elLieferadresse.checked && elLandRechnung.value == "DE")
		||
			( elLieferadresse.checked && elLandLieferadresse.value == "DE")
	)
	{
		/* Nachnahmeoption sichtbar */
		showNachnahme(true);
	}
	else
	{
		/* Nachnahmeoption unsichtbar */
		showNachnahme(false);
	}


	var selectedOption = elLandRechnung.options[elLandRechnung.selectedIndex].value;

	/* Versand Lieferadresse */
	if  (
		elLieferadresse.checked
		&&
		"CH" != selectedOption
		&&
		"NO" != selectedOption
	)
	{
		selectedOption = elLandLieferadresse.options[elLandLieferadresse.selectedIndex].value;

		setPreise( selectedOption );
	}
	/* Versand Besteller */
	else
	{
		selectedOption = elLandRechnung.options[elLandRechnung.selectedIndex].value;

		setPreise( selectedOption );
	}

	selectedOption = '';
}


/**
 * Preise
 */
function setPreise( option )
{
	$('Mradio1').setAttribute( 'price', Preise["1x_silber_de"] );
	$('Mradio2').setAttribute( 'price', Preise["2x_silber_de"] );
	$('Mradio3').setAttribute( 'price', Preise["1x_gold_de"] );
	$('Mradio4').setAttribute( 'price', Preise["2x_gold_de"] );

	/*
	alert(
		'[debug info]\n\n' +
		'Land: ' + option + '\n\n' +
		'1 x silber: ' + $('Mradio1').readAttribute( 'price') + ' EUR\n' +
		'2 x silber: ' + $('Mradio2').readAttribute( 'price') + ' EUR\n' +
		'1 x gold: ' + $('Mradio3').readAttribute( 'price') + ' EUR\n' +
		'1 x gold: ' + $('Mradio4').readAttribute( 'price') + ' EUR'
	);
	*/

	if ( option == "DE" )
	{
		/* Inlandspreise*/
		$$('label[for="Mradio1"] > span.price').each( function(el){ el.update(Preise["1x_silber_de"]); });
		$('Mradio1').setAttribute( 'price', Preise["1x_silber_de"] );

		$$('label[for="Mradio2"] > span.price').each( function(el){ el.update(Preise["2x_silber_de"]); });
		$('Mradio2').setAttribute( 'price', Preise["2x_silber_de"] );

		$$('label[for="Mradio3"] > span.price').each( function(el){ el.update(Preise["1x_gold_de"]); });
		$('Mradio3').setAttribute( 'price', Preise["1x_gold_de"] );

		$$('label[for="Mradio4"] > span.price').each( function(el){ el.update(Preise["2x_gold_de"]); });
		$('Mradio4').setAttribute( 'price', Preise["2x_gold_de"] );
	}
	else if ( option == "CH" || option == "NO" )
	{
		/* Nicht-EU-Preise*/
		$$('label[for="Mradio1"] > span.price').each( function(el){ el.update(Preise["1x_silber_out"]); });
		$('Mradio1').setAttribute( 'price', Preise["1x_silber_out"] );

		$$('label[for="Mradio2"] > span.price').each( function(el){ el.update(Preise["2x_silber_out"]); });
		$('Mradio2').setAttribute( 'price', Preise["2x_silber_out"] );

		$$('label[for="Mradio3"] > span.price').each( function(el){ el.update(Preise["1x_gold_out"]); });
		$('Mradio3').setAttribute( 'price', Preise["1x_gold_out"] );

		$$('label[for="Mradio4"] > span.price').each( function(el){ el.update(Preise["2x_gold_out"]); });
		$('Mradio4').setAttribute( 'price', Preise["2x_gold_out"] );
	}
	else
	{
		/* EU-Preise*/
		$$('label[for="Mradio1"] > span.price').each( function(el){ el.update(Preise["1x_silber_eu"]); });
		$('Mradio1').setAttribute( 'price', Preise["1x_silber_eu"] );

		$$('label[for="Mradio2"] > span.price').each( function(el){ el.update(Preise["2x_silber_eu"]); });
		$('Mradio2').setAttribute( 'price', Preise["2x_silber_eu"] );

		$$('label[for="Mradio3"] > span.price').each( function(el){ el.update(Preise["1x_gold_eu"]); });
		$('Mradio3').setAttribute( 'price', Preise["1x_gold_eu"] );

		$$('label[for="Mradio4"] > span.price').each( function(el){ el.update(Preise["2x_gold_eu"]); });
		$('Mradio4').setAttribute( 'price', Preise["2x_gold_eu"] );
	}

	switchSteuer();
	setArtikelpreis();
	setZahlungsart();
	orderTotal();
}


/**
 * Steuern
 */
function switchSteuer()
{
	if ( elLandRechnung.value == "CH" || elLandRechnung.value == "NO" )
	{
		$$('span.tax').each(function(s){ s.setStyle({display: 'none'}); });
		setSteuer('0');
	}
	else
	{
		$$('span.tax').each(function(s){ s.setStyle({display: 'inline'}); });
		setSteuer('19');
	}
}


/**
 * zeigt Zusatzformular bei abweichender Lieferadresse
 */
function switchLieferadresse()
{
	if ( elLieferadresse.checked )
	{
		$('shipto').setStyle({display: 'block'});
	}
	else
	{
		$('shipto').setStyle({display: 'none'});
	}
}


/**
 * zeigt Zusatzformular bei Geschenkguteschein
 */
function switchGutschein()
{
	if ( elGutschein.checked )
	{
		$('coupon').setStyle({display: 'block'});
	}
	else
	{
		$('coupon').setStyle({display: 'none'});
	}
}


/**
 * blendet Nachnahmeoption ein/aus
 */
function showNachnahme( option )
{
	if ( !option )
	{
		/* Nachnahmeoption inaktiv und ausblenden */
		$('Zradio3').checked = option;
		$('Zradio3').setStyle({display: 'none'});
		$$('label[for="Zradio3"]').each(function(l){ l.setStyle({display: 'none'}); });

		elOrderPayment.value = '0.00';

		orderTotal();
	}
	else
	{
		/* Nachnahmeoption einblenden */
		$('Zradio3').setStyle({display: 'inline'});
		$$('label[for="Zradio3"]').each(function(l){ l.setStyle({display: 'inline'}); });
	}
}


/**
 * setzt Preis für Artikel
 */
function setArtikelpreis()
{
	var val = $('fb').getInputs('radio', 'Menge').find(function(r){return r.checked}).readAttribute('price');

	// alert( 'Artikelpreis:\n\n' + val + '.00 EUR' );

	elOrderItem.value = val + '.00';

	orderTotal();
}


/**
 * setzt Preis für Zahlungsart
 */
function setSteuer(val)
{
	elOrderTax.value = val;
}


/**
 * setzt Preis für Zahlungsart
 */
function setZahlungsart()
{
	var val = $('fb').getInputs('radio', 'Zahlungsart').find(function(r){return r.checked}).value;

	// alert( 'Zahlungsart:\n\n' + val + ' = ' + Preise[val] + ' EUR' );

	if ( undefined == val )
	{
		elOrderPayment.value = '0.00';
	}
	else
	{
		elOrderPayment.value = Preise[val];
	}

	orderTotal();
}


/**
 * zeigt Zusammenfassung
 */
function orderTotal()
{
	var sumOrderTotal = parseFloat(elOrderItem.value) + parseFloat(elOrderPayment.value) + 	parseFloat(elOrderShipping.value);

	elOrderTotal.value = sumOrderTotal.toFixed(2);

	/* HTML Ausgabe */
	var strOrderTotal = '<table summary="orderTotal" border="0">';

	strOrderTotal = strOrderTotal + '<tr><td>Zwischensumme: </td><td>' + elOrderItem.value + ' EUR</td></tr>';

	if ( '0.00' != elOrderPayment.value )
	{
		strOrderTotal = strOrderTotal + '<tr><td>Aufpreis für Zahlungsverfahren: </td><td>' + elOrderPayment.value + ' EUR</td></tr>';
	}

	strOrderTotal = strOrderTotal + '<tr><td>Versandkosten: </td><td>' + elOrderShipping.value + ' EUR</td></tr>';

	if ( 0 < elOrderTax.value )
	{
		strOrderTotal = strOrderTotal + '<tr><td>enthaltene Mehrwertsteuer: </td><td>' + elOrderTax.value + ' %</td></tr>';
	}

	strOrderTotal = strOrderTotal + '<tr><td class="total">Gesamtpreis: </td><td class="total">' + elOrderTotal.value + ' EUR</td></tr>';

	strOrderTotal = strOrderTotal + '</table>';

	elTableSummary.update( strOrderTotal );
}



/**
 * Click Event für Checkbox Lieferadresse
 */
elLieferadresse.observe('click', function(event) {
	switchLieferadresse();
	switchVersand();
});


/**
 * Click Event für Checkbox Gutschein
 */
elGutschein.observe('click', function(event) {
	switchGutschein();
});


/**
 * Click Event für Radiobuttons Zahlungsart
 */
$('fb').getInputs('radio', 'Zahlungsart').each(function(r) {
	r.observe('click', function(event) {
		setZahlungsart();
	});
});


/**
 * Click Event für Radiobuttons Artikel
 */
$('fb').getInputs('radio', 'Menge').each(function(r) {
	r.observe('click', function(event) {
		setArtikelpreis();
	});
});


/**
 * Change Event für das Bestellerland
 */
elLandRechnung.observe('change', function(event) {
	switchVersand();
});


/**
 * Change Event für das Lieferungsland
 */
elLandLieferadresse.observe('change', function(event) {
	switchVersand();
});


/**
 * setzt Formularstatus bei Neuladen der Seite
 */
document.observe("dom:loaded", function() {
	setPreise('DE');
	switchLieferadresse();
	switchGutschein();
	switchSteuer();
	switchVersand();
	orderTotal();
});
