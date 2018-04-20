function updateItemOrder()
{
    var order = 10;
    $('#sonModule').children('.row-table').each(function()
    {
        if($(this).find("input[name*='order']").length > 0)
        {
            console.log(order);
            $(this).find("input[name*='order']").val(order);
            order += 10;
        }
    });

    $('#maxOrder').val(order - 10);
}

function addItem(obj)
{
    var $inputRow = $(obj).closest('.row-module');
    var $newRow = $('#insertItemBox').children('.row-module').clone().insertAfter($inputRow).addClass('highlight');
    $newRow.find('input').val('');
    setTimeout(function()
    {
        $newRow.removeClass('highlight');
    }, 1600);
    updateItemOrder();
}

function insertItem(obj)
{
    var $inputgroup = $(obj).closest('.row-table');
    var insertHtml  = $('#insertItemBox').children('.row-table').clone();
    $inputgroup.after(insertHtml).next('.row-table').find('input').val('');

    updateItemOrder();
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
