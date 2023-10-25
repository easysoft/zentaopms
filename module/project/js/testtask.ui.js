$(function()
{
    const options  = zui.DTable.query().options;
    initialOptions = $.extend(true, {}, options);
});

/**
 * 产品列合并单元格。
 * Merge cell in the product column.
 *
 * @param  object cell
 * @access public
 * @return object
 */
window.getCellSpan = function(cell)
{
    if(cell.col.name == 'productName' && cell.row.data.rowspan)
    {
        return {rowSpan: cell.row.data.rowspan};
    }
    if(cell.col.name == 'id' && cell.row.data.colspan)
    {
        return {colSpan: cell.row.data.colspan};
    }
}

/**
 * 产品列显示展开收起的图标。
 * Display show icon in the product column.
 *
 * @param  object result
 * @param  object info
 * @access public
 * @return object
 */
window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'productName')
    {
        if(row.data.hidden)
        {
            result.unshift({html: '<a class="dtable-nested-toggle state" data-on="click" data-product=' + row.data.product + ' data-call="deformation" data-params="event")><span class="toggle-icon is-collapsed"></span></a>'});
        }
        else
        {
            result.unshift({html: '<a class="dtable-nested-toggle state" data-on="click" data-product=' + row.data.product + ' data-call="deformation" data-params="event")><span class="toggle-icon is-expanded"></span></a>'});
            result.push({outer: false, style: {alignItems: 'start', 'padding-top': '8px'}})
        }
    }
    if(result && col.name == 'id' && row.data.hidden)
    {
        result.push({outer: false, style: {alignItems: 'center', justifyContent: 'start'}})
    }

    return result;
}

window.deformation = function(event)
{
    let newData      = [];
    const options    = zui.DTable.query().options;
    const product    = $(event.target).closest('a').data('product');
    const oldOptions = $.extend(true, {}, initialOptions);

    if($(event.target).closest('a').find('span').hasClass('is-collapsed'))
    {
        $.each(options.data, function(index)
        {
            if(!options.data[index]) return;
            if(options.data[index].product == product)
            {
                $.each(oldOptions.data, function(key)
                {
                    if(!oldOptions.data[key]) return;
                    if(oldOptions.data[key].product == product) newData.push(oldOptions.data[key]);
                });
            }
            else
            {
                newData.push(options.data[index]);
            }
        });
        options.data = newData;
        $(event.target).closest('a').find('span').removeClass('is-collapsed').addClass('is-expanded');
        $('#taskTable').zui('dtable').render(options);
    }
    else
    {
        options.data = options.data.filter(function(option)
        {
            return option.product != product || option.rowspan != 0;
        });
        $.each(options.data, function(index)
        {
            if(options.data[index] && options.data[index].product == product)
            {
                options.data[index].id      = {html: '<span class="text-gray">' + allTasks + ' ' + '<strong>' + options.data[index].rowspan + '</strong></span>'};
                options.data[index].rowspan = 1;
                options.data[index].colspan = 10;
                options.data[index].hidden  = 1;
            }
        });
        $(event.target).closest('a').find('span').removeClass('is-expanded').addClass('is-collapsed');
        $('#taskTable').zui('dtable').render();
    }
}
