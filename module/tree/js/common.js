function addItem(obj)
{
    var $inputgroup = $(obj).closest('.row-table');
    $inputgroup.after($inputgroup.clone()).next('.row-table').find('input').val('');
}

function insertItem(obj)
{
    var $inputgroup = $(obj).closest('.row-table');
    var insertHtml  = $('#insertItemBox').children('.row-table').clone();
    $inputgroup.after(insertHtml).next('.row-table').find('input').val('');

    updateItemOrder();
}

function updateItemOrder()
{
    var order = 10;
    $('#sonModule').children('.row-table').each(function(){
        if($(this).find("input[name*='order']").length > 0)
        {
            console.log(order);
            $(this).find("input[name*='order']").val(order);
            order += 10;
        }
    });

    $('#maxOrder').val(order - 10);
}

function deleteItem(obj)
{
    console.log($(obj).closest('.row-table').siblings('.row-table').find('i.icon-remove').size());
    if($(obj).closest('.row-table').siblings('.row-table.addedItem').find('i.icon-remove').size() <= 0) return;
    $(obj).closest('.row-table').remove();
}
