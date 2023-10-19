$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) =>
    {
        form.append('cases[]', id);
        form.append('versions[' + id + ']', $('#versions' + id).val());
    });

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
 * 对列进行重定义。
 * Redefine the column.
 *
 * @param  array  result
 * @param  array  info
 * @access public
 * @return string|array
 */
window.renderCell = function(result, info)
{
    if(info.col.name == 'title' && result[0])
    {
        const testcase = info.row.data;

        let html = '(';
        for(i = 1; i <= testcase.version; i++)
        {
            html += "<a href='" + $.createLink('testcase', 'view', "caseID=" + testcase.id + "&version=" + i) + "' data-toggle='modal' data-size='lg'>#" + i + "</a>";
        }
        html += ')';
        result.push({html});
    }
    if(info.col.name == 'version' && result[0])
    {
        const testcase = info.row.data;
        let html = "<select name='versions[" + testcase.id + "]' id='versions" + testcase.id + "' class='form-control' style='width:60px'>";
        for(i = 1; i <= testcase.version; i++)
        {
            html += "<option value='" + i + "'>" + i + "</option>";
        }
        html += "</select>";
        result[0] = {html};
    }
    return result;
}
