$(function()
{
    $(document).on('click', '.setting-box > button', function()
    {
        location.href = $(this).attr('data-link');
    }).on('click', '.setting-box a', function(e)
    {
        e.stopPropagation();
    }).on('click', '.plugin-item', function()
    {
        window.open($(this).data('link'));
    });

    if(!hasInternet && !isIntranet) $.get(createLink('admin', 'ajaxSetZentaoData'));
});
