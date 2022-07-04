$(function()
{
    $('#submit').click(function()
    {
        $(this).addClass('disabled');
        $("p:hidden").removeClass('hidden');
    });
});
