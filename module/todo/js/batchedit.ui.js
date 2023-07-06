window.renderRowData = function($row, index, row)
{
    if(row.type != 'custom')
    {
        /* Init type cell data. */
        if(row.type == 'cycle') $row.find('td[data-name="type"]').empty().html($('#cycleCellData').html());

        /* Init name cell data. */
        const $nameBox = $(`#nameCellData .${row.type}-data`).html();
        if($nameBox && $row.find('td[data-name="name"] .inited').length == 0)
        {
            $row.find('td[data-name="name"]').empty().html($nameBox);
            $row.find('td[data-name="name"] .inited').val(row.objectID);
        }
    }

    if($row.find('td[data-name="beginAndEnd"] .inited').length == 0)
    {
        $tdDom = $row.find('td[data-name="beginAndEnd"]');
        $tdDom.empty().html($('#dateCellData').html());

        $tdDom.find('input[name="begin"]').val(row.begin).prop('disabled', !row.begin);
        $tdDom.find('input[name="end"]').val(row.end).prop('disabled', !row.begin);

        $tdDom.find('input[name="switchTime"]').attr('name', `switchTime[${row.id}]`).attr('id', `switchTime_${row.id}`).prop('checked', !row.begin);
        $tdDom.find('label[for="switchTime_"]').attr('for', `switchTime_${row.id}`);
        $tdDom.find('.inited').attr('data-tr-index', index);
    }
}

$(function()
{
    $(document).off('click', '.time-check').on('click', '.time-check', function(e)
    {
        $(e.target).closest('.inited').find('.time-input').prop('disabled', !!e.target.checked);
    });
});
