/* **************************************************************************
Title: Incremental Search for Select Boxes
Copyright: Tobi Oetiker <tobi@oetiker.ch>, OETIKER+PARTNER AG

$Id: jquery.AddIncSearch.js 299 2009-11-16 16:22:10Z oetiker $

This jquery 1.3.x plugin adds incremental search to selectboxes of
your choics.

If you want to 'modify' selectboxes in your document, do the
following.

The behaviour of the widget can be tuned with the following options:

  maxListSize       if the total number of entries in the selectbox are
                    less than maxListSize, show them all

  maxMultiMatch     if multiple entries match, how many should be displayed.

  warnMultiMatch    string to append to a list of entries cut short
                    by maxMultiMatch

  warnNoMatch       string to show in the list when no entries match

  zIndex            zIndex for the additional page elements
                    it should be higher than the index of the select boxes.

 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
 <head>
 <script type="text/javascript" src="jquery-1.3.2.min.js"></script>
 <script type="text/javascript" src="jquery.AddIncSearch.js"></script>
 <script type="text/javascript">
 jQuery(document).ready(function() {
    jQuery("select").AddIncSearch({
        maxListSize   : 200,
        maxMultiMatch : 100,
        warnMultiMatch : 'top matches ...',
        warnNoMatch    : 'no matches ...'
    });
 });
 </script>
 <body>
 <form>
   <select>
     <option value="1">Hello</option>
     <option value="2">You</option>
   </select>
 </form>
 </body>
 </html>

License:

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
* *******************************************************************/
(function($) {
    // setup a namespace for us
    var nsp = 'AddIncSearch';

    $[nsp] = {
        // let the user override the default
        // $.pluginPattern.defaultOptions.optA = false
        defaultOptions: {
            maxListSize    : 200,
            maxMultiMatch  : 100,
            warnMultiMatch : 'top matches ...',
            warnNoMatch    : 'no matches ...',
	        zIndex         : 'auto'
        }
    };

    // Private Variables and Functions
    var _ = {
        moveInputFocus: function (jq,dist) {
            var fields = jq.parents('form,body').eq(0)
                .find('button,input[type!=hidden],textarea,select');
            var index = fields.index( jq );
            if ( index > -1
                 && index + dist < fields.length
                 && index + dist >= 0 ) {
                 fields.eq( index + dist ).focus();
                 return true;
            }
            else {
                 return false;
            }
        },

        action: function(options){
            if (this.nodeName != 'SELECT'){ // only select objects
                return this;
            }
            if (this.size > 1){  // no select boxes
                return this;
            }
            var $this = $(this);
            var $parent = $this.parent();
            var meta_opts = options;
            // lets you override the options
            // inside the dom objects class property
            // requires the jQuery metadata plugin
            // <div class="hello {color: 'red'}">ddd</div>
            if ($.meta){
                meta_opts = $.extend({}, options, $this.data());
            }

            var text_arr = [];
            var opt_arr = [];
            var opt_cnt = this.length;
            for (var i =0; i<opt_cnt;i++){
                opt_arr[i] = this.options[i];
                text_arr[i] = opt_arr[i].text.toLowerCase();
            }
            var button_width = $this.outerWidth();
            var button_height = $this.outerHeight();
            var selected = $(this.options[this.selectedIndex]).clone();
            // fix size of the list to whatever it was 'before'
            $this.width(button_width);
            $this.height(button_height);
            var button = $this.empty().append(selected);
            var top_match = $('<option>'+meta_opts.warnMultiMatch+'</option>').get(0);
            var no_match = $('<option>'+meta_opts.warnNoMatch+'</option>').get(0);
            top_match.disabled=true;
            no_match.disabled=true;

            var blocker = $('<div/>');
            blocker.css({
                position: 'absolute',
                width:  button.outerWidth(),
                height: button.outerHeight(),
                backgroundColor: '#ffffff',
                opacity: 0.01
            });
            blocker.appendTo($parent);

            var input = $('<input type="text"/>');
            input.hide();
            input.appendTo($parent);

            input.width(button.outerWidth());
            input.height(button.outerHeight());
            input.css({
                position: 'absolute',
                borderLeftWidth: button.css('border-left-width'),
                paddingLeft: button.css('padding-left'),
                borderTopWidth: button.css('border-top-width'),
                paddingTop: button.css('padding-top'),
                borderRightWidth: button.css('border-right-width'),
                paddingRight: button.css('padding-right'),
                borderBottomWidth: button.css('border-bottom-width'),
                paddingBottom: button.css('padding-bottom'),
                padding: 0,
                margin: 0,
                borderStyle: 'solid',
                borderColor: 'transparent',
                backgroundColor: 'transparent',
                outlineStyle: 'none',
            });
            var chooser = $('<select size=10/>');
            var cdom = chooser.get(0);
            chooser.css({
                position: 'absolute',
                width:  button.outerWidth(),
            });
            chooser.hide();
            if (meta_opts.zIndex && /^\d+$/.test(meta_opts.zIndex)){
                blocker.css({
                    zIndex : meta_opts.zIndex.toString(10)
                })
                input.css({
                    zIndex : (meta_opts.zIndex+1).toString(10)
                })
                chooser.css({
                    zIndex : (meta_opts.zIndex+1).toString(10)
                })
            }

            chooser.appendTo($parent);

            var position = function (){
                var offset = button.offset();
                chooser.css({
                    top: offset.top+button.outerHeight(),
                    left: offset.left
                });
                input.css({
                    top: offset.top,
                    left: offset.left+2
                });
                blocker.css({
                    top: offset.top,
                    left: offset.left
                });
            };
            // fix positioning on window resize
            button.resize(position);
            $(window).resize(position);

            // set initial position
            position();

            var over_input = false;
            input.mouseover(function(){
                over_input=true;
            });
            input.mouseout(function(){
                over_input=false;
            });

            var over_chooser = false;
            chooser.mouseover(function(){
                over_chooser=true;
            });
            chooser.mouseout(function(){
                over_chooser=false;
            });


            function input_show(){
                selected.remove();
                if (selected.val() != ''){
                    input.val(selected.text());
                }
                input.show();
                chooser.show();
            };

            function input_hide(){
                button.append(selected);
                button.change();
                input.hide();
                chooser.hide();
            };

            function blocker_click(e){
                input_show();
                input.focus();
                input.select();
                input.keyup();
                e.stopPropagation();
            };

            blocker.click(blocker_click);

            function chooser_click(e){
                e.stopPropagation();
                if (cdom.selectedIndex<0){
                    return;
                }
                sync_select();
                input_hide();
            };

            chooser.click(chooser_click);

            button.focus(function(){
                blocker.click();
            });

            input.focus(function(){
                over_input = true;
            });

            chooser.focus(function(){
                over_chooser = true;
            });

            input.blur(function(){
                over_input = false;
                if (!over_input && !over_chooser){
                    chooser.hide();
                    input_hide();
                }
            });
            chooser.blur(function(){
                over_chooser = false;
                if (!over_input && !over_chooser){
                    chooser.hide();
                    input_hide();
                }
            });

            var timer = null;
            var final_call = null;
            var search_cache = 'x';

            // the actual searching gets done here
            // to not block input, we get called
            // with a timer
            function searcher(){
                var matches = 0;
                var search = $.trim(input.val().toLowerCase());
	            if (search_cache == search){ // no change ...
                    timer = null;
                    return true;
                }

                search_cache = search;
                chooser.hide();
                chooser.empty();
				var match_id;
                for(var i=0;i<opt_cnt && matches < meta_opts.maxMultiMatch;i++){
                    if(search == '' || text_arr[i].indexOf(search,0) >= 0){
	                    matches++;
                        chooser.append(opt_arr[i]);
						match_id = i;
                    }
                };
                if (matches >= 1){
                    cdom.selectedIndex = 0;
	                selected.val(cdom.options[0].value);
                    selected.text(cdom.options[0].text);
                }
                if (matches == 0){
                    chooser.append(no_match);
                }
                else if (matches == 1 && opt_cnt < meta_opts.maxListSize){
                    chooser.append(opt_arr);
					cdom.selectedIndex = match_id;
                }
                else if (matches >= meta_opts.maxMultiMatch){
                    chooser.append(top_match);
                }
                chooser.show();
                // if we were running during the previous
                // keystroke do another run to make sure
                // we got it all
                if (final_call){
                    setTimeout(final_call,0);
                    final_call = null;
                }
                timer = null;
            };

            function keyup_handler(e){
                // if no timer is running, start one
                // to call the searcher function
                if (timer == null){
                    timer = setTimeout(searcher,0);
                    final_call = null;
                }
                else {
                    // if a timer is running
                    // make sure to call searcher once again
                    // after the timer is done
                    final_call = searcher;
                };
            };

            input.keyup(keyup_handler);

            function sync_select(){
                selected = $(cdom.options[cdom.selectedIndex]).clone();
            };

            var pg_step = cdom.size;
            function keydown_handler(e){
                switch(e.keyCode){
                case 9:
                    input.blur();
                    chooser.blur();
                    _.moveInputFocus(button,e.shiftKey ? -1 : 1);
                    break;
                case 13:  //enter
                    input.blur();
                    chooser.blur();
                    _.moveInputFocus(button,1);
                    break;
                case 40: //down
                    if (cdom.options.length > cdom.selectedIndex){
                        cdom.selectedIndex++;
                        sync_select();
                    };
                    break;
                case 38: //up
                    if (cdom.selectedIndex > 0){
                        cdom.selectedIndex--;
                        sync_select();
                    }
                    break;
                case 34: //pgdown
                    if (cdom.options.length > cdom.selectedIndex + pg_step){
                        cdom.selectedIndex+=pg_step;
                    } else {
					    cdom.selectedIndex = cdom.options.length-1;
				    }
                    sync_select();
                    break;
                case 33: //pgup
                    if (cdom.selectedIndex - pg_step > 0){
                        cdom.selectedIndex-=pg_step;
                    } else {
					    cdom.selectedIndex = 0;
				    }
                    sync_select();
                    break;
                default:
                    return true;
                }
                // we handled the key. stop
                // doing anything with it!
                return false;
            };
            input.keydown(keydown_handler);
            return;
        }
    };

    $.fn[nsp] = function(options) {
      if ($.browser.msie){
        var bvers = (parseInt(jQuery.browser.version));
          if (bvers < 7) {
             return this; // do not use with ie6, does not work
          }
        }
        var localOpts = $.extend(
            {}, // start with an empty map
            $[nsp].defaultOptions, // add defaults
            options // add options
        );
        // take care to pass on the context. without the call
        // action would be running in the _ context
        return this.each(function(){_.action.call(this,localOpts)});
    };

})(jQuery);

/* EOF */
