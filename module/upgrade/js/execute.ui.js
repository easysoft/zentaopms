window.showSQL = function(key)
{
    zui.Modal.alert({size: 'lg', title: 'SQL', content: {html: upgradeChanges[key].sql, className: 'leading-6'}});
}

window.submitConfirm = function(event)
{
    $(event.target).addClass('disabled');
    zui.Modal.open({id: 'progress'});

    updateProgressInterval();
    updateProgress();
}
