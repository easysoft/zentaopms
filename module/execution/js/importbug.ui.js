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
    const $priSelect = $(`.dtable-row[data-id="${row.id}"]`).find('select[name^=pri]');
    if(!$priSelect.hasClass('inited'))
    {
        $priSelect.val(row.data.pri);
        $priSelect.addClass('inited');
    }

    const $userSelect = $(`.dtable-row[data-id="${row.id}"]`).find('.select-user');
    if(!$userSelect.hasClass('inited'))
    {
        $userSelect.val(row.data.assignedTo);
        $userSelect.addClass('inited');
    }

    const $dateInput = $(`.dtable-row[data-id="${row.id}"]`).find('input[name^=deadline]');
    if(!$dateInput.hasClass('inited') && row.data.deadline > executionBegin && row.data.deadline > today)
    {
        $dateInput.val(row.data.deadline);
        $dateInput.addClass('inited');
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
