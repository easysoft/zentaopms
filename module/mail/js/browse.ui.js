window.onClickBatchDelete = function(event)
{
    const dtable      = zui.DTable.query(event.target);
    const checkedList = dtable.$.getChecks();

    if(checkedList.length === 0) return;

    let $this = $(event.target);
    if(!$this.hasClass('batch-btn')) $this = $this.closest('.batch-btn');

    const postData = new FormData();
    checkedList.forEach((id) => postData.append('mailIdList[]', id));

    zui.Modal.confirm($this.data('confirm')).then((res) => {if(res) $.ajaxSubmit({url: $this.data('formaction'), data: postData})});
};

window.renderCell = function(result, info)
{
    if(info.col.name == 'failReason' && result)
    {
        const mail = info.row.data;
        if(!mail.failReason?.length) return result;

        let failReason = mail.failReason.replaceAll('<br />', '');
        let html = "<span title='" + failReason + "'>" + failReason.replaceAll("\n", '') + "</span> ";
        return [{html: html}];
    }
    return result;
};
