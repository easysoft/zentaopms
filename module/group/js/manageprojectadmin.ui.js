function addItem(e)
{
    let maxNum = 0;
    $(e.target).closest('table').find("tr[class^='line']").each(function()
    {
        let trname = $(this).attr('class');
        let index  = trname.match(/\d+/g);

        index  = parseInt(index);
        maxNum = index > maxNum ? index : maxNum;
    })

    maxNum = parseInt(maxNum);
    maxNum += 1;

    let className = $(e.target).closest('tr').attr('class');
    let lastTr    = $('table tr.' + className).last();
    $($('table tr.' + className).get().reverse()).each(function()
    {
        let $newRow = $(this).clone();
        $newRow.attr('class', className.replace(/\d+/g, maxNum));

        $newRow.find("input[type='checkbox']").each(function()
        {
            let name = $(this).attr('name');
            let id   = $(this).attr('id');
            $(this).prop('name', name.replace(/\d+/g, maxNum));
            $(this).prop('id', id.replace(/\d+/g, maxNum));
            $(this).prop('checked', false);
        })

        let optionArr = [];
        $newRow.find('select').each(function()
        {
            let name = $(this).attr('name');
            let options = zui.Picker.query(`[name='${name}']`).options;

            let newName = name.replace(/\d+/g, maxNum);
            let newID   = newName.replace(/\[|\]/g, '');

            options.name         = newName;
            options.defaultValue = '';

            optionArr[newID] = options;

            $(this).closest('td').find('div[data-zui-picker]').parent().append(`<div id='${newID}' style='width: 100%'></div>`);
            $(this).closest('td').find('div[data-zui-picker]').remove();

        });

        /* Append btn-delete. */
        $newRow.find('.btn-group .btn').length == 1 ? $newRow.find('.btn-group').append('<button class="btn ghost btn-delete square" type="button"><i class="icon icon-trash"></i></button>') : '';
        $(lastTr).after($newRow);

        for(let key in optionArr)
        {
            let options = optionArr[key];
            let newID   = key;

            new zui.Picker(`#${newID}`, options);
            $(`#${newID}`).zui('picker').render({disabled: false});
        }
    })
}

function deleteItem(e)
{
    if($("table tr").length == 5) return false;

    let currentClass = $(e.target).closest('tr').attr('class');
    $(e.target).closest('table').find('tr.' + currentClass).remove();
}

function toggleDisabled(e)
{
    let name    = e.target.name;
    let checked = e.target.checked;

    let $picker = $(e.target).closest('tr').find('.input-group select').zui('picker');

    if(checked)
    {
        $picker.render({disabled: true});
    }
    else
    {
        $picker.render({disabled: false});
    }
}
