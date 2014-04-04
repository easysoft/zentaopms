$(function()
{
   var showHeight = $('.outer').height() - $('#featurebar').height() - 40;
   $('#editWin').height(showHeight);
   $('#extendWin').height(showHeight);

   $('.panel a.list-group-item').click(function(){$('.panel a.list-group-item.active').removeClass('active'); $(this).addClass('active')});
   if($('a.iframe').size()) $("a.iframe").colorbox({width:800, height:400, iframe:true, transition:'none', scrolling:true});
});
