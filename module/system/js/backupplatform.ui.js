var backupInterval;
var backupName;

/**
 * Start backup.
 *
 * @access public
 * @return void
 */
function startBackup()
{
    const form = new FormData($("#backupForm")[0]);

    $.ajaxSubmit({
        url: $.createLink('system', 'backupPlatform'),
        data: form,
        onComplete: function(response)
        {
            if(response.result == 'success')
            {
                $('.modal-content .panel-body').html(waitting);
                backupName = response.name;
                backupInterval = setInterval(checkBackup, 2000);
            }
            else
            {
                zui.Modal.alert(response.message);
            }
        },
    });
}

/**
 * Check backup progress.
 *
 * @access public
 * @return void
 */
function checkBackup()
{
    $.ajaxSubmit({
        url: $.createLink('system', 'ajaxGetBackupProgress'),
        data: {'name': backupName},
        onComplete: function(response)
        {
            if(response.result == 'progress')
            {
                $('.modal-content .panel-body').html(response.text);
            }
            else
            {
                clearInterval(backupInterval);
                if(response.result == 'failed') zui.Modal.alert(response.message);
            }
        },
    });
}
