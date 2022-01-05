/**
 * Add item to create view of programplan.
 *
 * @param  obj    obj
 * @access public
 * @return void
 */
function addItem(obj)
{
    var item = $('#addItem').html().replace(/%i%/g, i);
    $(obj).closest('tr').after('<tr class="addedItem">' + item  + '</tr>');
    var newItem = $('#names' + i).closest('tr');
    newItem.find('.form-date').datepicker();
    $("#output" + i).chosen();
    $("#PM_i__chosen").remove();
    $("#PM" + i).chosen();
    $("#output_i__chosen").remove();
    i ++;
}

/**
 * Delete item.
 *
 * @param  obj    obj
 * @access public
 * @return void
 */
function deleteItem(obj)
{
    if($('#planForm .table tbody').children().length < 2) return false;
    $(obj).closest('tr').remove();
}
