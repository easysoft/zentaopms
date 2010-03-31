/* reverseOrder : jQuery order reverser plugin
 *
 * Written by Corey H Maass for Arc90
 * (c) Arc90, Inc.
 * 
 * http://www.arc90.com
 * http://lab.arc90.com
 * 
 * Licensed under:
 * Creative Commons Attribution-Share Alike 3.0 http://creativecommons.org/licenses/by-sa/3.0/us/
 * 
 * Gotta love a plugin with more comments than actual code. :-)
 * items need to all be in the same parent like:
 * <div>
 * 	<div class="item">item 1</div>
 * 	<div class="item">item 2</div>
 * 	<div class="item">item 3</div>
 * </div>
 * 
 * Then call the plugin with the items to reverse:
 * $('.item').reverseOrder();
 * 
 */

(function($) {
$.fn.reverseOrder = function() {
	return this.each(function() {
		$(this).prependTo( $(this).parent() );
	});
};
})(jQuery);