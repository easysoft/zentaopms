$(function()
{
   var showHeight = $(window).height() - $('#header').height() - $('#footer').height() - 20;
   $('#editWin').height(showHeight);
   $('#extendWin').height(showHeight);
   if($('a.iframe').size()) $("a.iframe").colorbox({width:800, height:400, iframe:true, transition:'none', scrolling:true});
});
