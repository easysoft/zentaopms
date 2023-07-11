window.unlinkStakeholderConfirm = function(confirmDeleteTip, actionUrl)
{
    zui.Modal
    .confirm({message: confirmDeleteTip, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'})
    .then((confirmed) =>
    {
        if(confirmed) $.ajaxSubmit({url: actionUrl});
    });
};

window.onClickBatchUnlink = function(event)
{
    const dtable      = zui.DTable.query(event.target);
    const checkedList = dtable.$.getChecks();

    if(checkedList.length === 0) return;

    /* Generate checked stakeholder ID list string. */
    let idList = new Array();
    checkedList.forEach(function(id)
    {
        idList.push(id);
    });

    /* Set data-url for ajaxSubmit. */
    const button = $($(event.target).parents('button'));
    button.data('url', button.data('href').replace('%s', idList.join(',')));
}
