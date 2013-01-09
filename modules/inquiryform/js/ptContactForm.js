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
 * @version 1.00.00 2011-05-28 03:15 CEST
 */

/* 
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
 * old and better readable alternative version of the script above
 * 
$$('.text', '.textarea').each( function(el) {
	el.observe('focus', respondToFocus);
	el.observe('blur', respondToBlur);
});

function respondToFocus(event) {
	var el = event.element();
	el.addClassName('focus');
	$$('label[for=' + el.readAttribute('name') + ']').each( function(lb) {
		lb.addClassName('focus');
	});
}
function respondToBlur(event) {
	var el = event.element();
	el.removeClassName('focus');
	$$('label[for=' + el.readAttribute('name') + ']').each( function(lb) {
		lb.removeClassName('focus');
	});
}
*/