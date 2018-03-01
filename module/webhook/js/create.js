$(function()
{
    $('#type').change(function()
    {
        var type = $(this).val();
        $('#sendTypeTR').toggle(type != 'dingding');
        $('#paramsTR').toggle(type != 'bearychat' && type != 'dingding');
        $('#urlNote').html(urlNote[type]);
    });

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
            $(this).parents('tr').find('input[type=checkbox]').removeAttr('checked');
        }
    });

    $('#name').focus();
    $('#paramstext').attr('disabled', 'disabled');
});
