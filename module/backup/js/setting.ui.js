$(function()
{
    $("input[type=radio][name='autoBackup']").on('change', function(event)
    {
        var enabled = $("input[type=radio][name='autoBackup']:checked").val();
        if(enabled == 1) $('.backup-settings').show();
        if(enabled == 0) $('.backup-settings').hide();
    });
    $("input[type=radio][name='autoBackup']").trigger("change");
});