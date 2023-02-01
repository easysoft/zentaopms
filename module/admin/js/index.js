$(function()
{
    $(document).on('click', '.setting-box', function()
    {
        location.href = $(this).data('link');
    }).on('click', '.setting-box a', function(e)
    {
        e.stopPropagation();
    });
});
