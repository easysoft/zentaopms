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
