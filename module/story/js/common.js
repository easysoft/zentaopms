$(function()
{
    if(typeof(storyType) == 'undefined') storyType = ''; 
    $('#navbar .nav li').removeClass('active');
    $("#navbar .nav li[data-id=" + storyType + ']').addClass('active');
})

function getStatus(method, params)
{
    $.get(createLink('story', 'ajaxGetStatus', "method=" + method + '&params=' + params), function(status)
    {
        $('form #status').val(status).change();
    });
}
