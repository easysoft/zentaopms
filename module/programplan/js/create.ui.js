window.onRenderRow = function(row, rowIdx, data)
{
    if(!data || !data.planIDList) return;

    row.children('.form-batch-row-actions').children('[data-type=delete]').remove();
};
