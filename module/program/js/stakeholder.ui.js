window.onClickBatchUnlink = function(event)
{
    zui.Modal
    .confirm({message: confirmBatchUnlinkTip, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'})
    .then((confirmed) =>
     {
         if(confirmed)
         {
             const dtable      = zui.DTable.query($(this).target);
             const checkedList = dtable.$.getChecks();
             if(!checkedList.length) return;

             const url  = $(this).closest('button').data('url');
             const form = new FormData();
             checkedList.forEach((id) => form.append('stakeholderIdList[]', id));

             $.ajaxSubmit({url, data: form});
         }
    });
}
