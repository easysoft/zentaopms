$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('todoIdList[]', id));

    if($(this).attr('id') == 'changeDate') form.append('date', $('#formDate').val());
    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data: form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});

/**
 * Get checked items.
 *
 * @access public
 * @return array
 */
function getCheckedItems()
{
    var checkedItems = [];
    $('#todoForm [name^=todoIDList]:checked').each(function(index, ele)
    {
        checkedItems.push($(ele).val());
    });
    return checkedItems;
};

/**
 * 计算表格任务信息的统计。
 * Set todo summary for table footer.
 *
 * @param  element element
 * @param  array   checkedIDList
 * @access public
 * @return object
 */
window.setStatistics = function(element, checks)
{
    let waitCount  = 0;
    let doingCount = 0;
    checks.forEach((checkID) => {
        const task = element.getRowInfo(checkID).data;
        if(task.status == 'wait')  waitCount ++;
        if(task.status == 'doing') doingCount ++;
    })
    if(checks.length) return {html: element.options.checkedSummary.replaceAll('%total%', `${checks.length}`).replaceAll('%wait%', waitCount).replaceAll('%doing%', doingCount)};
    return zui.formatString(element.options.defaultSummary);
};

window.generateHtml = function(event)
{
    try
    {
        const dtable = zui.DTable.query(event.target);
        const checkedList = dtable.$.getChecks();
        if(!checkedList.length) return;

        return zui.jsx`<div class="toolbar input-group mr-2 size-sm">
            <${zui.DatePicker.Component} name="date" id="formDate" className="size-sm" style="width: 110px;" placeholder="${dtable.options.dateHoder}" />
            <button class="btn secondary toolbar-item batch-btn ajax-btn size-sm" data-url=${$.createLink('todo', 'import2Today')} id="changeDate">
                <span class="text">${dtable.options.changeDateLabel}</span>
            </button>
        </div>`;
    }
    catch(error){}
};
