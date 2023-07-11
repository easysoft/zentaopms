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
};
