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
    zui.Modal.confirm({message: confirmMsg, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('branch', methodName, `branchID=${branchID}`)});
    });
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
 * @access public
 * @return void
 */
function createBranch()
{
    $('#createForm input, #createForm textarea').attr('disabled', true);
    $('input[name=targetBranch]').removeAttr('disabled');
    $('#createForm').addClass('hidden');

    const createNew = $(this).is(':checked');
    if(createNew)
    {
        $('#createForm input, #createForm textarea').removeAttr('disabled');
        $('#targetBranch').attr('disabled', true);
        $('#createForm').removeClass('hidden');
    }
}
