window.confirmDelete = function(modelID)
{
    zui.Modal.confirm({message: confirmDeleteTip, icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then(res =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('ai', 'modeldelete', `modelID=${modelID}`)}).then(() => location.reload());
    })
};

window.confirmDisable = function(modelID)
{
    zui.Modal.confirm({message: confirmDisableTip, icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then(res =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('ai', 'modeldisable', `modelID=${modelID}`)}).then(() => location.reload());
    })
};
