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
    zui.Modal.confirm({message: confirmMsg, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
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

    const createNew           = $(this).is(':checked');
    const $targetBranchPicker = $('[name=targetBranch]').zui('picker');
    if(createNew)
    {
        $targetBranchPicker.render({disabled: true});

        $('#createForm input, #createForm textarea').removeAttr('disabled');
        $('#createForm').removeClass('hidden');
    }
    else
    {
        $targetBranchPicker.render({disabled: false});
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
    if(targetBranchName && typeof branchNamePairs[targetBranchName] === 'undefined')
    {
        zui.Modal.confirm(confirmMergeMessage).then((res) => {
            if(res) $.ajaxSubmit({url: formUrl, data: formData})
        });
        return false;
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

    $('#mergeBranch').toggleClass('hidden', checkedList.includes('0'));
}


/**
 * 拖拽的分支是否允许放下。
 * Is it allowed to drop the dragged branch.
 *
 * @param  from   被拿起的元素
 * @param  to     放下时的目标元素
 * @access public
 * @return bool
 */
window.canSortTo = function(from, to)
{
    if(!from || !to) return false;
    if(from.id == '0' || to.id == '0') return false;

    return true;
}

/**
 * 拖拽分支。
 * Drag branch.
 *
 * @param  from   被拿起的元素
 * @param  to     放下时的目标元素
 * @param  type   放在目标元素的上方还是下方
 * @access public
 * @return bool
 */
window.onSortEnd = function(from, to, type)
{
    if(!from || !to) return false;
    if(!canSortTo(from, to)) return false;

    const url  = $.createLink('branch', 'sort');
    const form = new FormData();
    form.append('orderBy', orderBy);
    form.append('branches', JSON.stringify(this.state.rowOrders));
    $.ajaxSubmit({url, data: form});

    return true;
}
