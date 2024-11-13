$(function()
{
    $("input[type=checkbox][name='autoBackup']").on('change', function(event)
    {
        var enabled = $("input[type=checkbox][name='autoBackup']:checked").length > 0;
        if(enabled)
        {
            $('.backup-settings').show();
        }
        else
        {
            $('.backup-settings').hide();
        }
    });
    $("input[type=checkbox][name='autoBackup']").trigger("change");
});