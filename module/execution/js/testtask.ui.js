$(document).off('click','.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('taskIdList[]', id));

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data: form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
})

/**
 * 计算测试单表格信息的统计。
 * Set task summary for table footer.
 *
 * @param  element element
 * @param  array   checkedIDList
 * @access public
 * @return object
 */
window.setStatistics = function(element, checkedIDList)
{
    let waitCount    = 0;
    let doingCount   = 0;
    let doneCount    = 0;
    let blockedCount = 0;
    let totalCount   = 0;

    const rows = element.layout.allRows;
    rows.forEach((row) => {
        if(checkedIDList.length == 0 || checkedIDList.includes(row.id))
        {
            if(row.id.includes('_')) return;

            const task = row.data;
            if(task.rawStatus == 'wait')
            {
                waitCount ++;
            }
            else if(task.rawStatus == 'doing')
            {
                doingCount ++;
            }
            else if(task.rawStatus == 'done')
            {
                doneCount ++;
            }
            else if(task.rawStatus == 'blocked')
            {
                blockedCount ++;
            }

            totalCount ++;
        }
    })
    resetFooterPadding();

    const summary = checkedIDList.length > 0 ? checkedAllSummary : pageSummary;
    return {
        html: summary.replace('%total%', totalCount)
        .replace('%wait%', waitCount)
        .replace('%testing%', doingCount)
        .replace('%blocked%', blockedCount)
        .replace('%done%', doneCount)
    };
}

let initialOptions;

$(function()
{
    const options  = zui.evalValue($('[zui-create-dtable]').attr('zui-create-dtable'));
    initialOptions = $.extend(true, {}, options);
});

window.resetFooterPadding = function()
{
    const width = $('#taskTable .dtable-body .dtable-cells-container .dtable-cell.is-last-row').width();
    $('#taskTable .dtable-footer').css('padding-left', width + 12);
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
    if(result && col.name == 'taskID' && row.data.hidden)
    {
        result.push({outer: false, style: {alignItems: 'center', justifyContent: 'start'}})
    }
    if(col.name == 'status' && result)
    {
        result[0] = {html: `<span class='status-${row.data.rawStatus}'>` + row.data.status + "</span>"};
    }

    return result;
}

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
    if(cell.col.name == 'taskID' && cell.row.data.colspan)
    {
        return {colSpan: cell.row.data.colspan};
    }
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
        options.cols.forEach((col) => {if(col.name == 'taskID') col.checkbox = true});
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
                options.data[index].taskID  = {html: '<span class="text-gray">' + allTasks + ' ' + '<strong>' + options.data[index].rowspan + '</strong></span>'};
                options.data[index].rowspan = 1;
                options.data[index].colspan = 10;
                options.data[index].hidden  = true;
            }
        });
        options.cols.forEach((col) => {if(col.name == 'taskID') col.checkbox = false});
        $(event.target).closest('a').find('span').removeClass('is-expanded').addClass('is-collapsed');
        $('#taskTable').zui('dtable').render();
    }
}
