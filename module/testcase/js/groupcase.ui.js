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
        result.unshift({html: '<a class="dtable-nested-toggle state" data-on="click" data-story=' + row.data.story + ' data-call="deformation" data-params="event")><span class="toggle-icon is-expanded"></span></a>'});
    }

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
    if(cell.col.name == 'storyTitle')
    {
        if(cell.row.data.rowspan)
        {
            return {rowSpan: cell.row.data.rowspan};
        }
    }
}

window.deformation = function(event)
{
    const options = zui.DTable.query().options;
    const story   = $(event.target).parent().data('story');
    let   newOptions;

    if($(event.target).hasClass('is-collapsed'))
    {
        newOptions = $.extend(true, {}, initialOptions);
        $(event.target).removeClass('is-collapsed').addClass('is-expanded');
    }
    else
    {
        options.data = options.data.filter(function(option){return option.story != story || option.rowspan != 0;});
        $.each(options.data, function(index)
        {
            if(options.data[index] && options.data[index].story == story)
            {
                options.data[index].rowspan = 1;
            }
        });
        $(event.target).removeClass('is-expanded').addClass('is-collapsed');
        newOptions = options;
    }
    console.log(newOptions);
    $('#groupCaseTable').zui('dtable').render(newOptions);
}
