$(document).on('click', "[id^='noLimit']", function()
{
    var index = $(this).attr('id').slice(7);
    if($(this).attr('checked') == 'checked')
    {
        $('#WIPCount' + index).val('');
        $('#WIPCount' + index).attr('disabled', true);
    }
    else
    {
        $('#WIPCount' + index).removeAttr('disabled');
    }
});

function addItem(obj)
{
    var item = $('#addItem').html().replace(/%i%/g, i);
    $(obj).closest('tr').after('<tr class="addedItem">' + item  + '</tr>');
    $('.colorpicker #color' + i).colorPicker();

    i ++;
}

function deleteItem(obj)
{
    if($('#columnForm .table tbody').children('tr').length < 4) return false;
    $(obj).closest('tr').remove();
}
