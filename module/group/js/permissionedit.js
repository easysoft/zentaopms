$(function()
{
    $(".panel-list").on('mouseover', ".priv-item", function()
    {
        $(this).addClass('text-primary');
        $(this).find('.icon').removeClass('hidden');
    }).on('mouseout', '.priv-item', function()
    {
        $(this).removeClass('text-primary');
        $(this).find('.icon').addClass('hidden');
    })
});
