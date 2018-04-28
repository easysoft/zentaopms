function addItem(obj)
{
    var $inputRow = $(obj).closest('.row-module');
    var $newRow = $('#insertItemBox').children('.row-module').clone().insertAfter($inputRow).addClass('highlight');
    $newRow.find("input[type!='hidden']").val('');
    setTimeout(function()
    {
        $newRow.removeClass('highlight');
    }, 1600);
}

function insertItem(obj)
{
    var $inputgroup = $(obj).closest('.row-table');
    var insertHtml  = $('#insertItemBox').children('.row-table').clone();
    $inputgroup.after(insertHtml).next('.row-table').find('input').val('');
}

function deleteItem(obj)
{
    var $inputRow = $(obj).closest('.row-module');
    if ($inputRow.siblings('.row-module.row-module-new').find('.btn-delete').length > 0)
    {
        $inputRow.addClass('highlight').fadeOut(500, function()
        {
            $inputRow.remove();
        });
    }
}
