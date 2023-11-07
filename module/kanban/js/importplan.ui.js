$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    form.append('targetLane', $('[name=lane]').val())
    checkedList.forEach((id) => form.append('productplans[]', id));

    $.ajaxSubmit({url, data:form});
});

