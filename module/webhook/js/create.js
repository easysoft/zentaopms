$(function()
{
    $('.objectType').click(function()
    {
        if($(this).prop('checked'))
        {
            $(this).parent().parent().next().find('input[type=checkbox]').attr('checked', 'checked');
        }
        else
        {
            $(this).parent().parent().next().find('input[type=checkbox]').removeAttr('checked');
        }
    });

    $('#name').focus();
    $('#paramsmessage').attr('disabled', 'disabled');
});
