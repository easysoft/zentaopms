function addVariable(obj)
{
    var $inputRow = $(obj).closest('.row-module');
    var $newRow = $('#insertItemBox').clone().insertAfter($inputRow).addClass('highlight');
    $newRow.find("input[type!='hidden']").val('');
    setTimeout(function()
    {
        $newRow.removeClass('highlight');
    }, 1600);
}

function deleteVariable(obj)
{
    var $inputRow = $(obj).closest('.row-module');
    $inputRow.remove();
}
