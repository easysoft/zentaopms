window.confirmPublish = function(assistantID)
{
    zui.Modal.confirm({message: confirmPublishTip, icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then(res =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('ai', 'assistantPublish', `modelID=${assistantID}`)}).then(() => location.reload());
    })
};

window.confirmWithdraw = function(assistantID)
{
    zui.Modal.confirm({message: confirmWithdrawTip, icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then(res =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('ai', 'assistantWithdraw', `modelID=${assistantID}`)}).then(() => location.reload());
    })
}