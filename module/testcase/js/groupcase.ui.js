/**
 * 标题列显示额外的内容。
 * Display extra content in the title column.
 *
 * @param  object result
 * @param  object info
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

