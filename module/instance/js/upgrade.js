$(function()
{
    $('#upgradeForm').on('submit', function(event)
    {
        event.preventDefault();

        var loadingDialog = bootbox.dialog(
        {
            message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.upgrading + '</div>',
        });

        $.post($('#upgradeForm').attr('action'), $('#upgradeForm').serializeArray()).done(function(response)
        {
            loadingDialog.modal('hide');
            window.parent.$('#triggerModal').modal('hide');

            let res = JSON.parse(response);
            if(res.result == 'success')
            {
                config.onlybody = 'no';
                window.parent.$.apps.reload('space', createLink('space', 'browse'), 'space');
                window.parent.location.reload();
            }
            else
            {
                alert(res.message);
            }
        });
    });
});
