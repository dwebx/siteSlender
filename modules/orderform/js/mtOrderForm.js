/**
 * contactform/js/mtOrderForm.js
 * 
 * This script is a part of the 'contactform' module of siteSlender to handle 
 * contact forms of web pages. 
 * 
 * The script uses functions of the javascript framework mootools
 * 
 * @package contactform
 * @subpackage javascript
 * @require mootools-core-1.3.js
 * @author Torsten Alrecht (Teo) <teo@dwebx.de>
 * @copyright 2011 dwebx.de
 * @version 1.00.00 2011-05-28 03:15 CEST
 */


/** 
 * functions to highlight the labels of input fields getting focus
 * with prototype js framework
 */
$$('.text, .textarea').each( function(el) {
	el.addEvents({
		focus: function() {
			el.addClass('focus');
			$$('label[for=' + el.get('name') + ']').each( function(lb) {
				lb.addClass('focus');
			});
		},
		blur: function() {
			el.removeClass('focus');
			$$('label[for=' + el.get('name') + ']').each( function(lb) {
				lb.removeClass('focus');
			});
		}
	});
});


/**
 * old and alternative version of the script above
 * 
$$('.text, .textarea').each( function(el) {
	el.addEvents({
		focus: function() {
			el.addClass('focus');
			$$('label').each( function(lb) {
				if ( lb.get('for') == el.get('name') ) {
					lb.addClass('focus');
				}
			});
		},
		blur: function() {
			el.removeClass('focus');
			$$('label').each( function(lb) {
				if ( lb.get('for') == el.get('name') ) {
					lb.removeClass('focus');
				}
			});
		}
	});
});
*/