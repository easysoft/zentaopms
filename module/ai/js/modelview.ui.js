window.confirmDelete = modelID =>
{
    zui.Modal.confirm({message: confirmDeleteTip, icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then(res =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('ai', 'modeldelete', `modelID=${modelID}`)});
    })
};

window.confirmDisable = modelID =>
{
    zui.Modal.confirm({message: confirmDisableTip, icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then(res =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('ai', 'modeldisable', `modelID=${modelID}`)});
    })
};

window.testConnection = modelID =>
{
    $('[href^="javascript:testConnection"]').attr('disabled', 'disabled');
    $.ajax(
    {
        type: 'GET',
        url: $.createLink('ai', 'modelTestConnection', `modelID=${modelID}`),
        dataType: 'json',
        success: data =>
        {
            if(data.result == 'success')
            {
                zui.Messager.show({content: data.message, type: 'success'})
            }
            else
            {
                zui.Messager.show({content: data.message, type: 'danger'})
            }
        },
        complete: () =>
        {
            $('[href^="javascript:testConnection"]').removeAttr('disabled');
        }
    });
};
