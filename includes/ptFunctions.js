/**
 * includes/ptFunctions.js
 * 
 * @uses includes/lbox/compressed.js functions (prototype 1.6.0)
 */

/**
 * set same height to the right column #right even without content
 */
window.addEvent('domready',function() {

	var heightBody     = document.body.getSize().y;
	var heightHeader   = 0;
	var heightText     = 0;
	var heightRight    = 0;
	var heightRightNew = 0;

	if ($('header')) { heightHeader = $('header').getSize().y; }
	if ($('text')) { heightText = $('text').getSize().y; }

	if ($('right')) {
		heightRight = $('right').getSize().y;
		heightRightNew = ( heightBody < heightText ) ? heightText : heightBody - heightHeader ;
		//alert(heightText + ' ' + heightBody);
		$('right').setStyle('height', ( heightRight < heightRightNew ) ? heightRightNew : heightRight );
	}
});