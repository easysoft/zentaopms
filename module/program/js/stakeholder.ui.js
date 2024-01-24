$(document).off('click', '.batch-unlink').on('click', '.batch-unlink', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('stakeholderIdList[]', id));

    zui.Modal.confirm({message: confirmBatchUnlinkTip, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((confirmed) =>
    {
         if(confirmed) $.ajaxSubmit({url, data: form});
    })
});
