$('#submit').click(function()
{
    $(this).attr('disabled', true);
    var url = createLink('user', 'forgetPassword');
    $.post(url, {account: $('#account').val(), email: $('#email').val()}, function(data)
    {
        $('#submit').attr('disabled', false);
        bootbox.alert(data.message);
    }, 'json')
})
