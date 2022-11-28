function addItem(obj)
{
    var maxNum = 0;
    $(obj).closest('table').find("tr[class^='line']").each(function()
    {
        var trname = $(this).attr('class');
        var index  = trname.match(/\d+/g);

        index  = parseInt(index);
        maxNum = index > maxNum ? index : maxNum;
    })

    maxNum = parseInt(maxNum);
    maxNum += 1;

    var className = $(obj).closest('tr').attr('class');
    var lastTr    = $('table tr.' + className).last();
    $($('table tr.' + className).get().reverse()).each(function()
    {
        var $newRow = $(this).clone();
        $newRow.attr('class', className.replace(/\d+/g, maxNum));
        $newRow.addClass('highlight');
        $newRow.find('select').each(function()
        {
            var name = $(this).attr('name');
            var id   = $(this).attr('id');
            $(this).attr('name', name.replace(/\d+/g, maxNum));
            $(this).attr('id', id.replace(/\d+/g, maxNum));
        })

        $newRow.find("input[type='checkbox']").each(function()
        {
            var name = $(this).attr('name');
            var id   = $(this).attr('id');
            $(this).attr('name', name.replace(/\d+/g, maxNum));
            $(this).attr('id', id.replace(/\d+/g, maxNum));
            $(this).attr('checked', false);
        })

        $(lastTr).after($newRow);
        $newRow.find('div.picker').remove();
        $newRow.find('.picker-select').val('').picker({chosenMode: true});

        var $picker = $newRow.find('.picker-select').data('zui.picker');
        $picker.setDisabled(false);
        setTimeout(function()
        {
            $newRow.removeClass('highlight');
        }, 1600);
    })

    if($("table tr").size() > 6)
    {
        $('.btn-delete').removeClass('hidden');
    }
}

function deleteItem(obj)
{
    var currentClass = $(obj).closest('tr').attr('class');
    $(obj).closest('table').find('tr.' + currentClass).remove();

    if($("table tr").size() <= 6)
    {
        $('.btn-delete').addClass('hidden');
        return false;
    }
}

function resetProgramName(obj)
{
    var programSelect = $(obj).closest('tr').find('select[name^="program"]');
    var newName = 'program[' + $(obj).val() + '][]';
    programSelect.attr('name', newName);
}

function toggleDisabled(obj)
{
    var checked = $(obj).is(':checked');
    var $picker = $(obj).closest('tr').find('.input-group select').data('zui.picker');

    if(checked)
    {
        $picker.setDisabled(true);
    }
    else
    {
        $picker.setDisabled(false);
    }
}
