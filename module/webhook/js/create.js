$(function()
{
    $('#type').change(function()
    {
        var type = $(this).val();
        $('#sendTypeTR').toggle(type != 'dinggroup' && type != 'dinguser' && type != 'wechatuser' && type != 'wechatgroup');
        $('#secretTR').toggle(type == 'dinggroup');
        $('#urlTR').toggle(type != 'dinguser' && type != 'wechatuser');
        $('.dinguserTR').toggle(type == 'dinguser');
        $('.wechatTR').toggle(type == 'wechatuser');
        $('#paramsTR').toggle(type != 'bearychat' && type != 'dinggroup' && type != 'dinguser' && type != 'wechatuser' && type != 'wechatgroup');
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
            $(this).parents('tr').find('input[type=checkbox][disabled!=disabled]').removeAttr('checked');
        }
    });

    $('#name').focus();
    $('#type').change();
    $('#paramstext').attr('disabled', 'disabled');
});
