/**
 * Add item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
window.addItem = function(obj)
{
    let item         = $('#addItem > tbody').html().replace(/%i%/g, itemIndex);
    const $currentTr = $(obj).closest('tr');
    $currentTr.after(item);

    const $newRow = $currentTr.next();
    $('select[name^=accounts]').each(function()
    {
        const selectValue = $(this).val();
        if(selectValue) $newRow.find(`option[value='${selectValue}']`).remove();
    });
}

/**
 * Delete item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
window.deleteItem = function(obj)
{
    if($('#teamForm .table tbody').children().length < 2) return false;
    $(obj).closest('tr').remove();
}
