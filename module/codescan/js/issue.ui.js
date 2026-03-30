window.renderIssueCell = function (result, {col, row})
{
    if(col.name == 'priority')
    {
        const severity = result[0];
        if(!severity) return result;

        let color = '';
        if(row.data.priority == 'high') color = 'danger';
        else if(row.data.priority == 'medium') color = 'warning';
        result[0] = {html: `<span class="text-${color}">${severity}</span>`};
        return result;
    }

    if(col.name == 'file')
    {
        let   maxLength = 38;
        const file      = result[0];
        if(!file || file.length <= maxLength) return result;

        const fileArr  = file.split('/');
        const fileName = fileArr[fileArr.length - 1];
        if(fileName.length > maxLength - 5)
        {
            result[0] = `/.../${fileName}`;
            return result;
        }

        let fileHead = '';
        maxLength = maxLength - fileName.length - 5;
        fileArr.forEach((item) =>
        {
            if(fileHead.length + item.length <= maxLength) fileHead += item + '/';
        });

        result[0] = {html: fileHead + '.../' + fileName};
    }

    return result;
};

$(document).off('click','.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const form      = new FormData();
    const filter    = [];
    const url       = $(this).data('url');
    const type      = $(this).data('type');
    const isBugBtn  = $(this).hasClass('bug-btn');
    const isAjaxBtn = $(this).hasClass('ajax-btn');
    let   hasIssue  = false;
    checkedList.forEach((id) => {
        const rowData = dtable.$.getRowInfo(id).data;
        if(isBugBtn && (rowData.bugID || !['wait', 'todo'].includes(rowData.status)))
        {
            filter.push('#' + id);
        }
        else if(type == 'active' && rowData.status != 'closed')
        {
            filter.push('#' + id);
        }
        else if(type == 'confirm' && rowData.status != 'wait')
        {
            filter.push('#' + id);
        }
        else if(type == 'ignore' && rowData.status != 'wait' && rowData.status != 'todo')
        {
            filter.push('#' + id);
        }
        else
        {
            hasIssue = true;
            form.append('issueIdList[]', id);
        }
    });

    const issueIdList = form.getAll('issueIdList[]');
    if(issueIdList.length > 0) $.cookie.set('issueIdList', issueIdList.join('|'));

    if(!hasIssue) return false;

    isAjaxBtn ? $.ajaxSubmit({url, data: form}) : (type != 'ignore' ? loadPage(url, '#mainContainer') : loadModal(url));
    return true;
});

window.loadIssueList = function()
{
    let type = $('#featureBar .nav-item a.active').data('id');
    if(!type) type = 'all';

    let url = pageUrl;
    url = url.replace('%s', type);
    url = url.replace('%s', $('[name^=severityFilter]').val().join(','));
    loadPartial(url, $('.scan-issue-block').length ? '.scan-issue-block' : '#mainContainer');
};

window.changeTreeType = function(obj)
{
    $('.tree-type-btn').removeClass('primary');
    $(obj).addClass('primary');

    const type = $(obj).data('type');
    if(!type) return;

    $('.issue-tree-file, .issue-tree-rule').addClass('hidden');
    $('.issue-tree-' + type).removeClass('hidden');
};

window.issueTreeClick = function(info)
{
    if(!info || !info.item || !info.item.isLeaf) return false;

    $.cookie.set('issueFile', info.item.ref);
    loadPage(info.item.link);
    return false;
}
