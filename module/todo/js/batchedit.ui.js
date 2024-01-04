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
            data.name         = 'name';
            data.multiple     = false;
            data.items        = nameItems[row.type];
            data.defaultValue = row.objectID;
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

    $row.find('[data-name="assignedTo"]').find('.picker-box').on('inited', function(e, info)
    {
        if(row.private == 1)
        {
            $assignedTo = $(this).zui('picker');
            $assignedTo.options.disabled = true;
            $assignedTo.render($assignedTo.options);
        }
    });
};

window.togglePending = function(e)
{
    $(e.target).closest('.input-group').find('.time-input').each(function()
    {
        $(this).zui('picker').render({disabled: e.target.checked});
    });
};

window.changeType = function(e)
{
    const type     = e.target.value;
    const $tr      = $(e.target).closest('tr');
    const index    = $tr.find('.form-control-static[data-name="id"]').text()
    const $nameBox = $tr.find('[data-name="name"]');

    if(moduleList.indexOf(type) !== -1)
    {
            items = nameItems[type];
            $nameBox.html("<div class='picker-box' id='name'></div>");
            $nameBox.find('#name').picker({items: items, name: 'name'});
    }
    else
    {
        $nameBox.html($('#nameInputBox').html());
        $nameBox.find('input[name=name]').attr('name', 'name[' + index + ']').attr('id', 'name_' + index);
    }
};
