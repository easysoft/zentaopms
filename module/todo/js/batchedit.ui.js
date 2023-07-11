window.renderRowData = function($row, index, row)
{
    if(row.type != 'custom')
    {
        /* Init type cell data. */
        if(row.type == 'cycle')
        {
            $row.find('td[data-name="type"]').empty().html($('#cycleCellData').html());
            $row.find('td[data-name="type"]').find('input[name="type"]').attr('name', `type[${wor.id}]`);
        }

        /* Init name cell data. */
        const $nameBox = $(`#nameCellData .${row.type}-data`).html();
        if($nameBox && $row.find('td[data-name="name"] .inited').length == 0)
        {
            $row.find('td[data-name="name"]').empty().html($nameBox);
            $row.find('td[data-name="name"] .inited').attr('name', `${row.type}[${row.id}]`).val(row.objectID);
        }
    }

    if($row.find('td[data-name="beginAndEnd"] .inited').length == 0)
    {
        if(row.begin == 2400) row.begin = '';

        $tdDom = $row.find('td[data-name="beginAndEnd"]');
        $tdDom.empty().html($('#dateCellData').html());

        $tdDom.find('select[name="begin"]').attr('name', `begin[${row.id}]`).val(row.begin.replace(':', '')).prop('disabled', !row.begin);
        $tdDom.find('select[name="end"]').attr('name', `end[${row.id}]`).val(row.end.replace(':', '')).prop('disabled', !row.begin);

        $tdDom.find('input[name="switchTime"]').attr('name', `switchTime[${row.id}]`).attr('id', `switchTime_${row.id}`).prop('checked', !row.begin);
        $tdDom.find('label[for="switchTime_"]').attr('for', `switchTime_${row.id}`);
    }
}

$(function()
{
    $(document).off('click', '.time-check').on('click', '.time-check', function(e)
    {
        $(e.target).closest('.inited').find('.time-input').prop('disabled', !!e.target.checked);
    });
});
