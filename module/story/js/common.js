$(function()
{
    if(typeof(storyType) == 'undefined') storyType = ''; 
    $('#subNavbar .nav li').removeClass('active');
    $("#subNavbar .nav li[data-id=" + storyType + ']').addClass('active');
})

function getStatus(method, params)
{
    $.get(createLink('story', 'ajaxGetStatus', "method=" + method + '&params=' + params), function(status)
    {
        $('form #status').val(status).change();
    });
}
