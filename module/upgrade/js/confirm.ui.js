$(function()
{
    $(document).on('click', 'button[type=submit]', function()
    {
        $(this).addClass('disabled');
        $(this).css('pointer-events', 'none');
        $('#upgradingTips').removeClass('hidden');
    });
});
