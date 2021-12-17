/**
 * Save team members.
 *
 * @access public
 * @return void
 */
function saveMembers()
{
    $('#saveBtn').addClass('hidden');
    $('#submit').removeClass('hidden');
    $('#submit').click();
}

/**
 * Add item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function addItem(obj)
{
    var item = $('#addItem').html().replace(/%i%/g, i);
    $(obj).closest('tr').after('<tr class="addedItem">' + item  + '</tr>');
    var selects = $(obj).closest('tr').next('tr').find('select');
    selects.each(function()
    {
        $(this).trigger('liszt:updated');
        $(this).chosen();
    });
    $(obj).closest('tr').next('tr').find('.form-date').datetimepicker();
    i ++;
}

/**
 * Delete item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function deleteItem(obj)
{
    if($('#teamForm .table-form tbody').children().length < 2) return false;
    $(obj).closest('tr').remove();
}
