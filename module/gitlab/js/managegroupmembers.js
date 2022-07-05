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
    if($(obj).hasClass('disabled')) return false;
    if($('#teamForm .table-form tbody').children().length < 2) return false;
    $(obj).closest('tr').remove();
}

$(document).on('change', '[id^="levels"]', function()
{
    $tr   = $(this).closest('tr');
    $next = $(this).closest('td').next()
    var ownerLevel = 50;
    if($(this).val() == ownerLevel)
    {
        $next.prepend('<input type="text" value="" class="form-control disabled" disabled autocomplete="off" />');
        $next.find('[id^="expires"]').addClass('hidden');
        $tr.find('a[onclick^="deleteItem"]').addClass('disabled');
    }
    else
    {
        $next.find('[id^="expires"]').removeClass('hidden');
        $next.find('input.disabled').remove();
        $tr.find('a[onclick^="deleteItem"]').removeClass('disabled');
    }
})
