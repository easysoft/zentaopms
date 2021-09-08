/**
 * Confirm left.
 *
 * @access public
 * @return void
 */
function confirmLeft()
{
    if($('#left').val() === '0') return confirm(confirmRecord);
}

$(function()
{
    $('.form-date').datetimepicker('setEndDate', today);
})
