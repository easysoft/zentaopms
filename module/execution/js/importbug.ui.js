$(document).off('click', '.import-bug-btn').on('click', '.import-bug-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return false;

    const formData = new FormData($("#importForm")[0]);
    checkedList.forEach((id) => formData.append(`id[${id}]`, id));

    $.ajaxSubmit({url: $('#importForm').attr('action'), data: formData});
    return false;
});
