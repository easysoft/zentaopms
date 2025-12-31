/**
 * 处理导入分支类型按钮点击
 */
window.handleImportBranchType = function($this)
{
    const dtable      = zui.DTable.query($this);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    checkedList.forEach(function(id)
    {
        postData.append('branchTypes[]', id);
    });

    $.ajaxSubmit({url: $this.data('url'), data: postData});
};
