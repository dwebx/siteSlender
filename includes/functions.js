/**
 * includes/functions.js
 * 
 * Function to set automatically the height of right column, equal to height
 * of body or content
 *
 * @package siteSlender
 * @subpackage Core
 * @author Torsten Albrecht <teo@dwebx.de>
 * @version 0.5.1
 * @uses mootools-core-1.3.js
 */


/**
 * setExternalLinks() <teo>
 *
 * Erzeugt externe Links, fuer valides XHTML ohne 'target'-Attribut.
 *
 * Die Schreibweise der Links erfolgt nach folgendem Schema
 *
 * <a title="http://www.zieldomain.tld" href="#" rel="extern">Label</a>
 *
 * Wird das Attribut 'rel' = 'external' gefunden, wird 'title' zu 'href' und das
 * zusaetzliche Attribut 'target' = '_blank' gesetzt.
 */
function setExternalLinks()
{
	// wenn: keine Unterstuetzung fuer getElementsByName
	if ( !document.getElementsByTagName )
	{
		// dann: NULL zurueckgeben
		return null;
	}

	// alle <a>-Elemente in ein Array lesen
	var anchors = document.getElementsByTagName("a");

	// Durchlauf aller <a>-Elemente
	for ( var i=0; i < anchors.length; i++ )
	{
		var anchor = anchors[i];

		// wenn: Attribut href vorhanden und Attribut rel = external
		if ( anchor.getAttribute("href") && anchor.getAttribute("rel") == "extern" )
		{
			// wenn: URL im Title-Attribut
			if (
				0 == anchor.getAttribute('title').indexOf('http')
				||
				anchor.getAttribute('title').indexOf('#')
				||
				0 == anchor.getAttribute('title').indexOf('mailto')
			)
			{
				// dann: austauschen
				var title = anchor.getAttribute('href').substr(1);

				anchor.setAttribute("href", anchor.getAttribute("title"));
				anchor.setAttribute("title", title);
			}
			// und: setzen des target-Attributs zum oeffnen in neuerm Fenster
			anchor.setAttribute("target", "blank");
		}
	}
} // END OF function setExternalLinks()


// Function to allow one JavaScript file to be included by another.
// Copyright (C) 2006-08 www.cryer.co.uk
function IncludeJavaScript(jsFile) {
	document.write('<script type="text/javascript" src="' + jsFile + '"></scr' + 'ipt>');
}
//IncludeJavaScript('/includes/mootools-core-1.3.js'); // mootools javascript framework
IncludeJavaScript('includes/lbox/js/compressed.js'); // includes prototype, scriptaculous and it modules
//IncludeJavaScript('includes/lbox/js/lightbox.js');
//IncludeJavaScript('includes/carousel-min.js');
//IncludeJavaScript('includes/opentip/opentip.min.js');

function init()
{
    //$('a').lightBox({fixedNavigation:true});            
    //$('kontaktlink').triggerEvent('click'); //Simuliert den Link-Klick 
	setExternalLinks();
}

Element.prototype.triggerEvent = function(eventName)
{
    if (document.createEvent)
    {
        var evt = document.createEvent('HTMLEvents');
        evt.initEvent(eventName, true, true);

        return this.dispatchEvent(evt);
    }

    if (this.fireEvent)
        return this.fireEvent('on' + eventName);
}

window.onload = init; 