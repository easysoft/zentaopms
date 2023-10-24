$(function()
{
    $(".form-time").datetimepicker({
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0,
        format: 'hh:ii'
    });

    $("input[type=checkbox][name='autoBackup[]']").on('change', function(event)
    {
        var enabled = $("input[type=checkbox][name='autoBackup[]']:checked").length > 0;
        if(enabled)
        {
            $('.backup-settings').show();
        }
        else
        {
            $('.backup-settings').hide();
        }
    });

    $("input[type=checkbox][name='autoBackup[]']").change();

    $("#keepDays").on('input', function(event)
    {
        console.log(event,event.target.value);
        event.target.value = event.target.value.replace(/[^\d]+/g,'');
        if(Number(event.target.value) < 1)
        {
            event.target.value = 1;
        }

        if(Number(event.target.value) > 30)
        {
            event.target.value = 30;
        }
    });

    $('#backupSettingForm #saveSetting').on('click', function()
    {
        var settings = $('#backupSettingForm').serialize();
        $.post(createLink('instance', 'backupSettings', 'id=' + instanceID), settings).done(function(response)
        {
            var res = JSON.parse(response);

            if(res.result == 'success')
            {
                parent.window.$.closeModal();
                parent.window.bootAlert({title: instanceNotices.success, message:res.message});
            }
            else
            {
                var errMsg = res.message instanceof Array ? res.message.join('<br/>') : res.message;
                parent.window.bootAlert({title: instanceNotices.fail, message: errMsg});
            }
        });
    });
});
