$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const formData = dtable.$.getFormData();
    const url      = $(this).data('url');
    const form     = new FormData();
    checkedList.forEach((id) =>
    {
        form.append('case[' + id + ']', id);

        let key = 'version[' + id + ']';
        form.append(key, formData[key]);
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
        result[0].children.props.required = true;
        let versions = [];
        for(i = 1; i <= info.row.data.version; i++) versions.push({'text': '#' + i, 'value': i});
        result[0].children.props.items = versions;
    }
    if(info.col.name == 'lastRunDate' && result[0] && result[0].substring(0, 4) == '0000') result.shift();
    return result;
}
