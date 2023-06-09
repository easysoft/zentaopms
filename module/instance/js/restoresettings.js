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

    $("input[type=checkbox][name='autoRestore[]']").on('change', function(event)
    {
        var enabled = $("input[type=checkbox][name='autoRestore[]']:checked").length > 0;
        if(enabled)
        {
            $('.restore-settings').show();
        }
        else
        {
            $('.restore-settings').hide();
        }
    });

    $("input[type=checkbox][name='autoRestore[]']").change();

    $('#restoreSettingForm #saveSetting').on('click', function()
    {
        var settings = $('#restoreSettingForm').serialize();
        $.post(createLink('instance', 'restoreSettings', 'id=' + instanceID), settings).done(function(response)
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
