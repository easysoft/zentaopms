/**
 * 查询条件改变时重新加载 Bug 创建表。
 * Reload bug create table when query conditions changed.
 *
 * @access public
 * @return void
 */
function loadBugCreate()
{
    const begin     = $('#conditions').find('#begin').val().replaceAll('-', '');
    const end       = $('#conditions').find('#end').val().replaceAll('-', '');
    const product   = $('#conditions').find('[name=product]').val();
    const execution = $('#conditions').find('[name=execution]').val();
    const params    = window.btoa('begin=' + begin + '&end=' + end + '&product=' + product + '&execution=' + execution);
    const link      = $.createLink('pivot', 'preview', 'dimension=' + dimension + '&group=' + groupID + '&module=pivot&method=bugCreate&params=' + params);
    loadPage(link, '#table-pivot-preview');
}

/**
 * 查询条件改变时重新加载产品汇总表。
 * Load product summary page when query condition changed.
 *
 * @access public
 * @return void
 */
function loadProductSummary()
{
    let conditions = '';
    $('#conditions input[type=checkbox]').each(function(i)
    {
        if($(this).prop('checked')) conditions += $(this).val() + ',';
    })
    conditions = conditions.substring(0, conditions.length - 1);

    const params = window.btoa('conditions=' + conditions);
    const link   = $.createLink('pivot', 'preview', 'dimension=' + dimension + '&group=' + groupID + '&module=pivot&method=productSummary&params=' + params);
    loadPage(link, '#table-pivot-preview');
}

/**
 * 跨行跨列合并单元格。
 * Merge cell.
 *
 * @param  object cell
 * @access public
 * @return object|void
 */
getCellSpanOfProductSummary = function(cell)
{
    if((cell.col.name == 'name' || cell.col.name == 'PO') && cell.row.data.rowspan)
    {
        return {rowSpan: cell.row.data.rowspan};
    }
}
