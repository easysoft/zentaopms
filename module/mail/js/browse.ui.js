window.onClickBatchDelete = function(event)
{
    event.stopPropagation();
    event.preventDefault();

    const dtable      = zui.DTable.query(event.target);
    const checkedList = dtable.$.getChecks();

    if(checkedList.length === 0) return;

    const postData = new FormData();
    checkedList.forEach((id) => postData.append('mailIDList[]', id));

    $.ajaxSubmit({"url": $(event.target).attr('href'), "data": postData});
}

window.confirmDelete = function(actionUrl)
{
    zui.Modal
    .confirm({message: confirmDeleteTip, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'})
    .then((confirmed) =>
    {
        if(confirmed) $.ajaxSubmit({url: actionUrl});
    });
}

window.resendAlert = function(type, msg)
{
    zui.Messager.show({content: msg, type: type});

    loadCurrentPage();
}
