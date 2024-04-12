window.onRenderProjectNameCell = function(result, info)
{
    if(info.col.name === 'name' && info.row.data.delay > 0)
    {
        const html = {html : '<span class="label size-sm danger-pale flex-none nowrap">' + delayInfo.replace('%s', info.row.data.delay) + '</span>', className : 'flex items-end', style : {flexDirection : "column"}};
        result.push(html);
    }

    if(info.col.name === 'end' && info.row.data.end == LONG_TIME) result[0] = longTimeText;

    return result;
}
