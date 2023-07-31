$(function()
{
    $.get($.createLink('system', 'ajaxOssInfo'), function(response)
    {
        let res = JSON.parse(response);
        if(res.result == 'success')
        {
            $('#ossAdmin').text(res.data.account.username);
            $('#ossPassword').val(res.data.account.password);
            $('#ossVisitUrl').attr('href', res.data.url);
            $('#ossAccountModal').modal('show');
        }
    });
});

function copySK()
{
    var sk = document.getElementById('ossSK');
    $('#ossSK').removeClass('hidden');
    sk.select();
    document.execCommand('copy');
    $('#ossSK').addClass('hidden');
    zui.Modal.alert(copySuccess);
}

function copyPassBtn()
{
    var ossPassword = document.getElementById('ossPassword');
    $('#ossPassword').removeClass('hidden');
    ossPassword.select();
    document.execCommand('copy');
    $('#ossPassword').addClass('hidden');
    zui.Modal.alert(copySuccess);
}
