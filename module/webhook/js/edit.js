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

    $('#allParams, #allActions').click(function()
    {
        if($(this).prop('checked'))
        {
            $(this).parents('tr').find('input[type=checkbox]').attr('checked', 'checked');
        }
        else
        {
            $(this).parents('tr').find('input[type=checkbox][disabled!=disabled]').removeAttr('checked');
        }
    });

    $('#name').focus();
    $('#paramstext').attr('disabled', 'disabled');
});
