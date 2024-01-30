window.showHistory = function()
{
    const showHistory = !$('#hisTrigger').hasClass('text-primary');
    if(showHistory)
    {
        $('#history, #closeBtn').removeClass('hidden');
        $('#contentTree').addClass('hidden');
        $('#outlineToggle .icon').addClass('icon-menu-arrow-left').removeClass('icon-menu-arrow-right')
    }
    else
    {
        $('#contentTree').removeClass('hidden');
        $('#history, #closeBtn').addClass('hidden');
        $('#outlineToggle .icon').removeClass('icon-menu-arrow-left').addClass('icon-menu-arrow-right')
    }

    $('#hisTrigger').toggleClass('text-primary');
}

$(function()
{
    if($.cookie.get('isFullScreen') == 1) fullScreen();

    $('#history').append('<a id="closeBtn" href="###" class="btn btn-link hidden"><i class="icon icon-close"></i></a>');
});

$(document).on('click', '#closeBtn', function()
{
    $('#hisTrigger').removeClass('text-primary');
    $('#history, #closeBtn').addClass('hidden');
});

window.toggleOutline = function()
{
    $('#outlineToggle .icon').toggleClass('icon-menu-arrow-left').toggleClass('icon-menu-arrow-right')
    $('#contentTree').toggleClass('hidden');
}
