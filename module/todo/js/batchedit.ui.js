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
            data.name     = row.type;
            data.multiple = false;
            data.items    = nameItems[row.type];
            $nameBox.find('#name').picker(data);
            $row.find('[data-name="name"]').find('.picker-box').on('inited', function()
            {
                $row.find('[name^="'+ row.type + '"]').zui('picker').$.setValue(row.objectID.toString());
            });
        }
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
        $nameBox.html("<div class='picker-box' id='" + type + "'></div>");
        $nameBox.find('#' + type).picker({items: items, name: type});
    }
    else
    {
        $nameBox.html('<input class="form-control form-batch-input" type="text" autocomplete="off" name="name" data-name="name">');
        $nameBox.find('input[name=name]').attr('name', 'name[' + index + ']').attr('id', 'name_' + index);
    }
};
