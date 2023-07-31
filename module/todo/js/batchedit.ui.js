window.renderRowData = function($row, index, row)
{
    if(row.type != 'custom')
    {
        /* Init type cell data. */
        if(row.type == 'cycle')
        {
            $row.find('td[data-name="type"]').empty().html($('#cycleCellData').html());
            $row.find('td[data-name="type"]').find('input[name="type"]').attr('name', `type[${row.id}]`);
        }

        /* Init name cell data. */
        if(typeof nameItems[row.type] != 'undefined')
        {
            let $nameBox = $row.find('td[data-name="name"]');
            $nameBox.html("<div class='picker-box' id='name'></div>");
            data = {};
            data.name  = 'name';
            data.items = nameItems[row.type];
            data.defaultValue  = row.objectID;
            $nameBox.find('#name').picker(data);
        }
    }

    if($row.find('td[data-name="beginAndEnd"] .inited').length == 0)
    {
        if(row.begin == 2400) row.begin = '';

        $tdDom = $row.find('td[data-name="beginAndEnd"]');
        $tdDom.empty().html($('#dateCellData').html());

        $tdDom.find('#begin.picker-box').picker({name: 'begin', items: timeItems, defaultValue: row.begin.replace(':', ''), disabled: !row.begin});
        $tdDom.find('#end.picker-box').picker({name: 'end', items: timeItems, defaultValue: row.end.replace(':', ''), disabled: !row.begin});

        $tdDom.find('input[name="switchTime"]').attr('name', `switchTime[${row.id}]`).attr('id', `switchTime_${row.id}`).prop('checked', !row.begin);
        $tdDom.find('label[for="switchTime_"]').attr('for', `switchTime_${row.id}`);
    }
};

window.togglePending = function(e)
{
    $(e.target).closest('.input-group').find('.time-input').each(function()
    {
        $(this).zui('picker').render({disabled: e.target.checked});
    });
}
