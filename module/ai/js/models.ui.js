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
