/**
 * Check restore progress.
 *
 * @access public
 * @return void
 */
function checkRestore()
{
    $.ajaxSubmit({
        url: $.createLink('system', 'ajaxGetRestoreProgress'),
        data: {'name': restoreName},
        onComplete: function(response)
        {
            if(response.result == 'progress')
            {
                $('div.restoreProgress').html(response.text);
            }
            else
            {
                clearInterval(restoreInterval);
                if(response.result == 'failed') zui.Modal.alert(response.message);
            }
        },
    });
}

if(error)
{
    zui.Modal.alert(error);
    openUrl($.createLink('system', 'browseBackup'));
}
else
{
    var restoreInterval = setInterval(checkRestore, 2000);
}
