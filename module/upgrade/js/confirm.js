$(function()
{
    $('#submit').click(function()
    {
        $(this).addClass('disabled');
        $(this).css('pointer-events', 'none');
        $('#upgradingTips').removeClass('hidden');
    });
});
