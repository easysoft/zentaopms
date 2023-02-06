$(function()
{
    $(document).on('click', '.setting-box', function()
    {
        location.href = $(this).data('link');
    }).on('click', '.setting-box a', function(e)
    {
        e.stopPropagation();
    }).on('click', '.plugin-item', function()
    {
        window.open($(this).data('link'));
    });
});
