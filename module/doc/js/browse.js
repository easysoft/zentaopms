$('.ajaxCollect').click(function()
{
    if(browseType == 'collectedbyme')
    {
        window.location.reload();
    }
});

$(function()
{
    $('ul.pager .pager-item').attr('data-app', appTab);
});
