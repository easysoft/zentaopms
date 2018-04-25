$(function()
{
   var showHeight = $('#main').height() - $('#mainMenu').height() - 40;
   $('#editWin').height(showHeight);
   $('#extendWin').height(showHeight);

   $('.side-col a').click(function()
   {
       $('.side-col a.active').removeClass('active');
       $(this).addClass('active');
   });
});
