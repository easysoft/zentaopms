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

    let targetBranchItems = [];
    for(let branchName in branchNamePairs)
    {
        branchID = branchNamePairs[branchName];
        if(!checkedList.includes(String(branchID))) targetBranchItems.push({value: branchID, text: branchName});
    }

    let $targetBranchPicker = $('[name=targetBranch]').zui('picker');
    $targetBranchPicker.render({items: targetBranchItems});
    $targetBranchPicker.$.setValue('0');
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

window.clickSubmit = function()
{
    let mergedBranchName = '';
    let targetBranchName = $('#mergeForm .picker-single-selection').text();

    $(".is-checked[data-col='name']").each(function()
    {
        mergedBranchName += ',' + $(this).find('.dtable-cell-content').attr('title');
    });

    mergedBranchName = mergedBranchName.substr(1);
    targetBranchName = $('#createBranch').prop('checked') ? $('#mergeForm input[name=name]').val() : targetBranchName;

    let confirmMergeMessage = confirmMerge.replace(/(.*)mergedBranch(.*)targetBranch(.*)/, "$1" + mergedBranchName + "$2" + targetBranchName + "$3");

    const formUrl  = $('#mergeForm').attr('action');
    const formData = new FormData($("#mergeForm")[0]);
    console.log(branchNamePairs);
    if(targetBranchName && typeof branchNamePairs[targetBranchName] === 'undefined')
    {
        zui.Modal.confirm(confirmMergeMessage).then((res) => {
            if(res) $.ajaxSubmit({url: formUrl, data: formData})
        });
        return false;
    }
}
