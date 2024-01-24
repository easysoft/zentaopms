$(function()
{
    const options  = zui.DTable.query().options;
    initialOptions = $.extend(true, {}, options);
});

/**
 * 需求列显示展开收起的图标。
 * Display show icon in the story column.
 *
 * @param  object result
 * @param  object info
 * @access public
 * @return object
 */
window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'storyTitle')
    {
        if(row.data.hidden)
        {
            result.unshift({html: '<a class="dtable-nested-toggle state" data-on="click" data-story=' + row.data.story + ' data-call="deformation" data-params="event")><span class="toggle-icon is-collapsed"></span></a>'});
        }
        else
        {
            result.unshift({html: '<a class="dtable-nested-toggle state" data-on="click" data-story=' + row.data.story + ' data-call="deformation" data-params="event")><span class="toggle-icon is-expanded"></span></a>'});
            result.push({outer: false, style: {alignItems: 'start', 'padding-top': '8px'}})
        }
    }
    if(result && col.name == 'caseID' && row.data.hidden) result.push({outer: false, style: {alignItems: 'center', justifyContent: 'start'}})

    return result;
}

/**
 * 需求列合并单元格。
 * cell span in the story column.
 *
 * @param  object cell
 * @access public
 * @return object
 */
window.getCellSpan = function(cell)
{
    if(cell.col.name == 'storyTitle' && cell.row.data.rowspan) return {rowSpan: cell.row.data.rowspan};

    if(cell.col.name == 'caseID' && cell.row.data.colspan) return {colSpan: cell.row.data.colspan};
}

window.deformation = function(event)
{
    let newData      = [];
    const options    = zui.DTable.query().options;
    const story      = $(event.target).closest('a').data('story');
    const oldOptions = $.extend(true, {}, initialOptions);

    if($(event.target).closest('a').find('span').hasClass('is-collapsed'))
    {
        $.each(options.data, function(index)
        {
            if(!options.data[index]) return;
            if(options.data[index].story == story)
            {
                $.each(oldOptions.data, function(key)
                {
                    if(!oldOptions.data[key]) return;
                    if(oldOptions.data[key].story == story) newData.push(oldOptions.data[key]);
                });
            }
            else
            {
                newData.push(options.data[index]);
            }
        });
        options.data = newData;
        $(event.target).closest('a').find('span').removeClass('is-collapsed').addClass('is-expanded');
        $('#groupCaseTable').zui('dtable').render(options);
    }
    else
    {
        options.data = options.data.filter(function(option){ return option.story != story || option.rowspan != 0; });
        $.each(options.data, function(index)
        {
            if(options.data[index] && options.data[index].story == story)
            {
                options.data[index].caseID  = {html: '<span class="text-gray">' + allTestcases + ' ' + '<strong>' + options.data[index].rowspan + '</strong></span>'};
                options.data[index].rowspan = 1;
                options.data[index].colspan = 12;
                options.data[index].hidden  = 1;
            }
        });
        $(event.target).closest('a').find('span').removeClass('is-expanded').addClass('is-collapsed');
        $('#groupCaseTable').zui('dtable').render();
    }
}
