window.renderCell = function(result, info)
{
    if(info.col.name == 'sort')
    {
        result[0] = {html: "<i class='icon-move'></i>", className: 'text-gray cursor-move move-stage'};
    }
    return result;
}

window.onSortEnd = function(from, to, type)
{
    if(!from || !to) return false;

    const url  = $.createLink('stage', 'updateOrder');
    const form = new FormData();
    form.append('sortedIdList', JSON.stringify(this.state.rowOrders));

    $.ajaxSubmit({url, data: form});
    return true;
}
