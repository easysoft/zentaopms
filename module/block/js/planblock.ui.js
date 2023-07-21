window.onRenderPlanNameCell = function(result, info)
{
    if(info.col.name === 'title' && info.row.data.expired == true)
    {
        const html = {html : '<span class="label size-sm circle danger-pale">' + delay + '</span>', className : 'flex items-end w-10', style : {flexDirection : "column"}};
        result.push(html);
    }

    return result;
}
