/**
 * 激活/关闭分支。
 * Change branch status.
 *
 * @param  int    branchID
 * @param  string changeStatus
 * @access public
 * @return void
 */
window.changeStatus = function(branchID, changeStatus)
{
    const methodName = changeStatus == 'close' ? 'close' : 'activate';
    const confirmMsg = changeStatus == 'close' ? confirmclose : confirmactivate;
    if(window.confirm(confirmMsg))
    {
        $.ajaxSubmit({
            url: $.createLink('branch', methodName, `branchID=${branchID}`)
        });
    }
}

/**
 * 设置合并分支按钮是否显示。
 * Set merge btn display.
 *
 * @access public
 * @return void
 */
window.checkedChange = function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    $('#mergeBranch').hide();
    if(checkedList.length == 2) $('#mergeBranch').show();
}

window.createSortLink = function(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';
    return sortLink.replace('{orderBy}', sort);
}

$(document).on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const form = new FormData();
    const url  = $(this).data('url');
    checkedList.forEach((id) => form.append('branchIDList[]', id));
    postAndLoadPage(url, form);
}).on('click', '#mergeBranch', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return false;

    $('#mergedBranchIDList').val(checkedList.join(','));
});

/**
 * Set create branch form.
 *
 * @param  element obj
 * @access public
 * @return void
 */
function createBranch(obj)
{
    $('#createForm input, #createForm textarea').attr('disabled', true);
    $('input[name=targetBranch]').removeAttr('disabled');
    $('#createForm').addClass('hidden');

    const createNew = $(obj).is(':checked');
    if(createNew)
    {
        $('#createForm input, #createForm textarea').removeAttr('disabled');
        $('#targetBranch').attr('disabled', true);
        $('#createForm').removeClass('hidden');
    }
}
