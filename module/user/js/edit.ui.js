/**
 * 根据用户类型切换控件的显示和隐藏。
 * Toggle the display of controls according to the user type.
 *
 * @param  event  event
 * @access public
 * @return void
 */
function changeType(event)
{
    const isInside = $(event.target).val() == 'inside';

    $('[name="company"]').closest('.form-group').toggleClass('hidden', isInside);
    $('[name="dept"]').closest('.form-row').toggleClass('hidden', !isInside);
    $('#commiter').closest('.form-group').toggleClass('hidden', !isInside);
}
