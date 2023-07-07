window.onRenderProjectNameCell = function(result, info)
{
    if(info.col.name === 'name' && info.row.data.delay > 0)
    {
        const html = {html : '<span class="label size-sm circle danger-pale">' + delayInfo.replace('%s', info.row.data.delay) + '</span>', className : 'flex items-end w-28', style : {flexDirection : "column"}};
        result.push(html);
    }

    return result;
}
