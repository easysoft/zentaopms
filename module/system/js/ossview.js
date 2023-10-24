$(function()
{
    $('#ossManage').on('click', function(event)
    {
        $.get(createLink('system', 'ajaxOssInfo'), function(response)
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

    $('#copySKBtn').on('click', function(event)
    {
        $('#ossSK').removeClass('hidden').select();
        document.execCommand('copy');
        $('#ossSK').addClass('hidden');
        bootbox.alert(copySuccess);
    });

    $('#copyPassBtn').on('click', function(event)
    {
        $('#ossPassword').removeClass('hidden').select();
        document.execCommand('copy');
        $('#ossPassword').addClass('hidden');
        bootbox.alert(copySuccess);
    });
});
