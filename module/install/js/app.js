$(function()
{
    $('#submitBtn').on('click', function()
    {
        var loadingDialog = bootbox.dialog(
        {
            message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + notices.creatingSolution + '</div>',
        });

        $('#submitBtn').attr('disabled', true);
        $.post(createLink('install', 'app'), $('#installForm').serializeArray()).done(function(response)
        {
            var res = JSON.parse(response);
            if(res.result == 'success')
            {
                setTimeout(function()
                {
                    loadingDialog.modal('hide');
                    $('#submitBtn').attr('disabled', false);
                    parent.window.location.href = res.locate;
                }, 3000);
            }
            else
            {
                loadingDialog.modal('hide');
                $('#submitBtn').attr('disabled', false);
                var errMessage = res.message;
                if(res.message instanceof Array) errMessage = res.message.join('<br/>');
                if(res.message instanceof Object) errMessage = Object.values(res.message).join('<br/>');

                bootbox.alert(
                {
                    title:   notices.fail,
                    message: errMessage,
                });
            }
        });
    });

    $('select').on('change', checkMemory);
    checkMemory();
});

/**
 * Check is over memory.
 */
function checkMemory()
{
    $('#submitBtn').attr('disabled', true);
    var apps = [];
    $.each(category, (index, cate) => 
    {
        var item = $('#' + cate).val();
        if(item != '') apps.push(item);
    });

    $.post(createLink('install', 'ajaxCheck'), {apps: apps}).done(function(response)
    {
        var res = JSON.parse(response);
        if(res.code == undefined || res.code == 41010)
        {
            $('#skipBtn').show();
            $('#overMemoryNotice').show();
            $('#submitBtn').attr('disabled', true);
            return;
        }

        $('#skipBtn').hide();
        $('#overMemoryNotice').hide();
        $('#submitBtn').attr('disabled', false);
    });
}