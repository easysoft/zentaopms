window.renderRowData = function($row, index, row)
{
    if($row.find('td[data-name="beginAndEnd"] .inited').length == 0)
    {
        $tdDom = $row.find('td[data-name="beginAndEnd"]');
        $tdDom.empty().html($('#dateCellData').html());

        $tdDom.find('input[name="begin"]').attr('name', `begin[${index}]`);
        $tdDom.find('input[name="end"]').attr('name', `end[${index}]`);

        $tdDom.find('input[name="switchTime"]').attr('name', `switchTime[${index}]`).attr('id', `switchTime_${index}`);
        $tdDom.find('label[for="switchTime_"]').attr('for', `switchTime_${index}`);
    }
}
