let renderedDefault = false;
let defaultModel = null;
window.onRenderCell = (result, {col, row}) =>
{
    if(col.name == 'name' && ((!renderedDefault && row.data.enabled == '1') || (renderedDefault && row.data.id == defaultModel)))
    {
        result.push({html: `<span class='label gray-pale rounded-xl cursor-help' title='${langDefaultTip}'>${langDefault}</span>`});
        renderedDefault = true;
        defaultModel = row.data.id;
    }
    return result;
};

window.confirmDisable = function(modelID)
{
    zui.Modal.confirm({message: confirmDisableTip, icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then(res =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('ai', 'modeldisable', `modelID=${modelID}`)}).then(() => location.reload());
    })
};
