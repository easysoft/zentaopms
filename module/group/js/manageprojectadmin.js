function addItem(obj)
{
    var $currentRow = $(obj).closest('tr');
    var $newRow = $currentRow.clone().addClass('highlight');
    $currentRow.after($newRow);
    $newRow.find('th').text('');

    $newRow.find('div[id^=members], div[id^=program]').remove();
    $newRow.find('select[name^="members"], select[name^="program"]').val('').chosen();
    setTimeout(function()
    {   
        $newRow.removeClass('highlight');
    }, 1600);
}

function deleteItem(obj)
{
    var $currentLine= $(obj).closest('tr');
    $currentLine.remove();
}

function resetProgramName(obj)
{
    var programSelect = $(obj).closest('tr').find('select[name^="program"]');
    var newName = 'program[' + $(obj).val() + '][]';
    programSelect.attr('name', newName);
}
