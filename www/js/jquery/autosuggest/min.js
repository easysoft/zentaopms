 /*
 * AutoSuggest
 * Copyright 2009-2010 Drew Wilson
 * www.drewwilson.com
 * code.drewwilson.com/entry/autosuggest-jquery-plugin
 *
 * Version 1.2   -   Updated: Jan. 05, 2010
 *
 * This Plug-In will auto-complete or auto-suggest completed search queries
 * for you as you type. You can add multiple selections and remove them on
 * the fly. It supports keybord navigation (UP + DOWN + RETURN), as well
 * as multiple AutoSuggest fields on the same page.
 *
 * Inspied by the Autocomplete plugin by: Jšrn Zaefferer
 * and the Facelist plugin by: Ian Tearle (iantearle.com)
 *
 * This AutoSuggest jQuery plug-in is dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
(function($){$.fn.autoSuggest=function(data,options){var defaults={startText:"Enter Name Here",selectedItem:"value",searchObj:"value",queryParam:"q",retrieveLimit:false,extraParams:"",matchCase:false,minChars:1,keyDelay:400,start:function(){},selectionClick:function(elem){},formatList:false,retrieveComplete:function(data){return data;},resultsComplete:function(){}};var opts=$.extend(defaults,options);var d_type="object";var d_count=0;if(typeof data=="string"){d_type="string";var req_string=data;}else{var org_data=data;for(k in data)if(data.hasOwnProperty(k))d_count++;}
if((d_type=="object"&&d_count>0)||d_type=="string"){return this.each(function(x){x=x+""+Math.floor(Math.random()*100);opts.start.call(this);var input=$(this);input.attr("autocomplete","off").addClass("as-input").attr("id","as-input-"+x).val(opts.startText);var input_focus=false;input.wrap('<ul class="as-selections" id="as-selections-'+x+'"></ul>').wrap('<li class="as-original" id="as-original-'+x+'"></li>');var selections_holder=$("#as-selections-"+x);var org_li=$("#as-original-"+x);var results_holder=$('<div class="as-results" id="as-results-'+x+'"></div>').hide();var results_ul=$('<ul class="as-list"></ul>');var values_input=$('<input type="hidden" class="as-values" name="as_values_'+x+'" />');input.after(values_input);selections_holder.click(function(){input_focus=true;input.focus();}).mousedown(function(){input_focus=false;}).after(results_holder);$("li",results_ul).live("mouseover",function(){$("li",results_ul).removeClass("active");$(this).addClass("active");});var timeout=null;var prev="";input.focus(function(){if($(this).val()==opts.startText&&values_input.val()==""){$(this).val("");}else if(input_focus){$("li.as-selection-item",selections_holder).removeClass("blur");if($(this).val()!=""){results_holder.show();}}
input_focus=true;return true;}).blur(function(){if($(this).val()==""&&values_input.val()==""){$(this).val(opts.startText);}else if(input_focus){$("li.as-selection-item",selections_holder).addClass("blur").removeClass("selected");results_holder.hide();}}).keydown(function(e){lastKeyPressCode=e.keyCode;first_focus=false;switch(e.keyCode){case 38:e.preventDefault();moveSelection("up");break;case 40:e.preventDefault();moveSelection("down");break;case 8:if(input.val()==""){var last=values_input.val().split(",");last=last[last.length-2];selections_holder.children().not(org_li.prev()).removeClass("selected");if(org_li.prev().hasClass("selected")){values_input.val(values_input.val().replace(last+",",""));org_li.prev().remove();}else{org_li.prev().addClass("selected");}}
if(input.val().length==1){results_holder.hide();prev="";}
if($(":visible",results_holder).length>0){if(timeout){clearTimeout(timeout);}
timeout=setTimeout(function(){keyChange();},opts.keyDelay);}
break;case 9:case 13:var active=$("li.active:first",results_holder);if(active.length>0){active.click();results_holder.hide();e.preventDefault();}
break;default:if(timeout){clearTimeout(timeout);}
timeout=setTimeout(function(){keyChange();},opts.keyDelay);break;}});function keyChange(){if(lastKeyPressCode==46||(lastKeyPressCode>8&&lastKeyPressCode<32)){return results_holder.hide();}
var string=input.val().replace(/[\\]+|[\/]+/g,"");if(string==prev)return;prev=string;if(string.length>=opts.minChars){selections_holder.addClass("loading");if(d_type=="string"){var limit="";if(opts.retrieveLimit){limit="&limit="+encodeURIComponent(opts.retrieveLimit);}
$.getJSON(req_string+"?"+opts.queryParam+"="+encodeURIComponent(string)+limit+opts.extraParams,function(data){d_count=0;var new_data=opts.retrieveComplete.call(this,data);for(k in new_data)if(new_data.hasOwnProperty(k))d_count++;processData(new_data,string);});}else{processData(org_data,string);}}else{selections_holder.removeClass("loading");results_holder.hide();}}
function processData(data,query){if(!opts.matchCase){query=query.toLowerCase();}
var matchCount=0;results_holder.html(results_ul.html("")).hide();for(var i=0;i<d_count;i++){var num=i;var forward=false;if(opts.searchObj=="value"){var str=data[num].value;}else{var str="";var names=opts.searchObj.split(",");for(var y=0;y<names.length;y++){var name=$.trim(names[y]);str=str+data[num][name]+" ";}}if(str){if(!opts.matchCase){str=str.toLowerCase();}
if(str.search(query)!=-1&&values_input.val().search(data[num].value+",")==-1){forward=true;}}
if(forward){var formatted=$('<li class="as-result-item" id="as-result-item-'+num+'"></li>').click(function(){var data=$(this).data("data");var num=data.num;if($("#as-selection-"+num,selections_holder).length<=0){var data=data.attributes;input.val("").focus();prev="";values_input.val(values_input.val()+data.value+",");var item=$('<li class="as-selection-item" id="as-selection-'+num+'"></li>').click(function(){opts.selectionClick.call(this,$(this));selections_holder.children().removeClass("selected");$(this).addClass("selected");}).mousedown(function(){input_focus=false;});var close=$('<a class="as-close">&times;</a>').click(function(){values_input.val(values_input.val().replace(data.value+",",""));item.remove();input.focus();return false;});org_li.before(item.html(data[opts.selectedItem]).prepend(close));results_holder.hide();}}).mousedown(function(){input_focus=false;}).data("data",{attributes:data[num],num:num});var this_data=$.extend({},data[num]);if(!opts.matchCase){var regx=new RegExp("(?![^&;]+;)(?!<[^<>]*)("+query+")(?![^<>]*>)(?![^&;]+;)","gi");}else{var regx=new RegExp("(?![^&;]+;)(?!<[^<>]*)("+query+")(?![^<>]*>)(?![^&;]+;)","g");}
this_data[opts.selectedItem]=this_data[opts.selectedItem].replace(regx,"<em>$1</em>");if(!opts.formatList){formatted=formatted.html(this_data[opts.selectedItem]);}else{formatted=opts.formatList.call(this,this_data,formatted);}
results_ul.append(formatted);delete this_data;matchCount++;}}
selections_holder.removeClass("loading");if(matchCount<=0){results_ul.html('<li class="as-message">No Results Found</li>');}
results_ul.css("width",selections_holder.outerWidth());results_holder.show();opts.resultsComplete.call(this);}
function moveSelection(direction){if($(":visible",results_holder).length>0){var lis=$("li",results_holder);if(direction=="down"){var start=lis.eq(0);}else{var start=lis.filter(":last");}var active=$("li.active:first",results_holder);if(active.length>0){if(direction=="down"){start=active.next();}else{start=active.prev();}}lis.removeClass("active");start.addClass("active");}}});}}})(jQuery);