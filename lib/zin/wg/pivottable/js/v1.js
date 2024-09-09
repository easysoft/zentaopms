/**
 * 计算数据表格的高度。
 * Calculate height of data table.
 *
 * @param  int    height
 * @access public
 * @return int
 */
window.getHeight = function(height = 800, filterCount = 0)
{
    const dtableContent = $('#stepContent .dtable-content').height();
    const titleContent  = 48 + 8 * 2;
    const filterRows    = Math.ceil(filterCount / 2);
    let conditionHeight = filterRows * 40 + 16;

    if(filterCount != 0) conditionHeight += 8;

    return Math.min(dtableContent - titleContent - conditionHeight, height);
}

/**
 * 如果列包含切片字段，重新渲染单元格。
 * If column contains slice field, re-render cell.
 *
 * @param  array  result
 * @param  object cell
 * @access public
 * @return array
 */
window.renderCell = function(result, {row, col})
{
    if(result && col.setting.colspan)
    {
        let values = result.shift();
        if(typeof(values.type) != 'undefined' && values.type == 'a') values = values.props['children'];

        result.push({className: 'gap-0 px-0'});
        values.forEach((value, index) =>
            result.push({
                html: value || !Number.isNaN(value) ? `${value}` : '&nbsp;',
                className: 'flex justify-center items-center h-full w-1/2' + (index == 0 ? ' border-r': ''),
                style: 'border-color: var(--dtable-border-color)'
            })
        );
    }

    return result;
}

/**
 * 合并单元格。
 * Merge cell.
 *
 * @param  object cell
 * @access public
 * @return object|void
 */
window.getCellSpan = function(cell)
{
    const options = this.options.cellSpanOptions[cell.col.name];
    if(options)
    {
        const rowSpan = cell.row.data[options.rowspan ?? 'rowspan'] ?? 1;
        const colSpan = cell.row.data[options.colspan ?? 'colspan'] ?? 1;
        return {rowSpan, colSpan};
    }
}
