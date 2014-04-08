$(function()
{
   var showHeight = $('.outer').height() - $('#featurebar').height() - 40;
   $('#editWin').height(showHeight);
   $('#extendWin').height(showHeight);

   $('.panel a.list-group-item').click(function(){$('.panel a.list-group-item.active').removeClass('active'); $(this).addClass('active')});
});
