$(function()
{
    $('#urlIframe').height($('#mainContent').height() - 35);
    $('#mainMenu .fullScreen').click(function()
    {
        if(!$(this).hasClass('collapse'))
        {
            $(this).addClass('collapse');
            $(this).find('i').attr('class', 'icon-exchange');
            $('.side-col').hide();
        }
        else
        {
            $(this).removeClass('collapse');
            $(this).find('i').attr('class', 'icon-fullscreen');
            $('.side-col').show();
        }
    })
})
