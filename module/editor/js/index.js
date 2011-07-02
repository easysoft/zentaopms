$(function()
{
   var showHeight = $(window).height() - $('#header').height() - $('#footer').height() - $('#hiddenwin').height();
   $('#editWin').height(showHeight);
   $('#extendWin').height(showHeight);
});
