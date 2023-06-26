window.handleImportBug = function(result, data)
{
    const {col, row, value} = data;

    if(col.name == 'pri' && col.type == 'html')
    {
        $('#priSelect .select-pri').attr('name', `pri[${row.data.id}]`);

        result[0] = null;
        result[result.length] = {html: $('#priSelect').html()};
    }
    else if(col.name == 'assignedTo')
    {
        $('#userSelect .select-user').attr('name', `user[${row.data.id}]`);

        result[0] = null;
        result[result.length] = {html: $('#userSelect').html()};
    }
    else if(col.name == 'estimate')
    {
        $('#numInput .input-num').attr('name', `estimate[${row.data.id}]`);

        result[0] = null;
        result[result.length] = {html: $('#numInput').html()};
    }
    else if(col.name == 'estStarted' || col.name == 'deadline')
    {
        $('#dateInput .input-date').attr('name', `${col.name}[${row.data.id}]`);

        result[0] = null;
        result[result.length] = {html: $('#dateInput').html()};
    }

    return result;
}


/**
 * 设置优先级，指派给和截止日期的默认值。
 * Set defaults for pri, assign and deadline.
 */
const today = zui.formatDate(new Date(), 'yyyy-MM-dd');
window.onRenderRow = function({row})
{
    $(`.dtable-row[data-id="${row.id}"]`).find('.select-pri').val(row.data.pri);
    $(`.dtable-row[data-id="${row.id}"]`).find('.select-user').val(row.data.assignedTo);

    if(row.data.deadline > executionBegin && row.data.deadline > today)
    {
        $(`.dtable-row[data-id="${row.id}"]`).find('input[name^=deadline]').val(row.data.deadline);
    }
}

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
