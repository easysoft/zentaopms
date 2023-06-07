$(document).on('click', '.batch-btn', function()
{
    const dtable      = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const form = new FormData();
    const url  = $(this).data('url');
    checkedList.forEach((id) => form.append('bugIdList[]', id));
    postAndLoadPage(url, form);
});

/**
 * 计算表格Bug信息的统计。
 * Set bug summary for table footer.
 *
 * @param  element element
 * @param  array   checkedIDList
 * @access public
 * @return object
 */
window.setStatistics = function(element, checkedIDList)
{
    let totalCount = 0;

    const rows  = element.layout.allRows;
    rows.forEach((row) => {
        if(checkedIDList.length == 0 || checkedIDList.includes(row.id))
        {
            totalCount ++;
        }
    })

    const summary = checkedIDList.length > 0 ? checkedSummary.replace('{0}', totalCount) : pageSummary;
    return {html: summary};
}
