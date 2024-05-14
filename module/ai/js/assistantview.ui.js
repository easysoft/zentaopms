window.confirmPublish = function(assistantID)
{
    zui.Modal.confirm({message: confirmPublishTip, icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then(res =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('ai', 'assistantPublish', `modelID=${assistantID}`)}).then(() => $.apps.reloadApp('admin'));
    })
};

window.confirmWithdraw = function(assistantID)
{
    zui.Modal.confirm({message: confirmWithdrawTip, icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then(res =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('ai', 'assistantWithdraw', `modelID=${assistantID}`)}).then(() => $.apps.reloadApp('admin'));
    })
}

window.confirmDelete = function(assistantID)
{
    zui.Modal.confirm({message: confirmDeleteTip, icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then(res =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('ai', 'assistantDelete', `modelID=${assistantID}`)}).then(() => $.apps.reloadApp('admin', $.createLink('ai', 'assistants')));
    })
}
