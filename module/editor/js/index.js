$(function()
{
    var showHeight = $('#main').height() - $('#mainMenu').height() - 40;
    $('#editWin').height(showHeight);
    $('#extendWin').height(showHeight);

    $('.side-col a').click(function()
    {
        $(this).closest('.side-col').find('a.text-primary').removeClass('text-primary');
        $(this).addClass('text-primary');
    });
});
