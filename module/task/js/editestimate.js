/**
 * Confirm left.
 *
 * @access public
 * @return bool
 */
function confirmLeft()
{
    $left = $('#left');
    if(!$left.prop('readonly') && $left.val() === '0') return confirm(confirmRecord);
}

$(function()
{
    $('.form-date').datetimepicker('setEndDate', today);
})
