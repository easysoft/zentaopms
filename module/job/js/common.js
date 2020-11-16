/**
 * Add item.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function addItem(obj)
{
    var $inputgroup = $(obj).closest('.input-group').clone();
    $inputgroup.find('input').val('');
    $(obj).closest('.input-group').after($inputgroup);
}

/**
 * Delete item.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function deleteItem(obj)
{
    if($('#paramDiv').find("input").size() <= 2) return;
    $(obj).closest('.input-group').remove();
}
