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

    $('[name="company"]').closest('.form-row').toggleClass('hidden', isInside);
    $('[name="dept"]').closest('.form-row').toggleClass('hidden', !isInside);
    $('[name="join"]').closest('.form-row').toggleClass('hidden', !isInside);
    $('#commiter').closest('.form-row').toggleClass('hidden', !isInside);
}

/**
 * 根据职位更改权限组。
 * Change the permission group according to the role.
 *
 * @param  role   $role
 * @access public
 * @return void
 */
function changeRole(event)
{
    const role  = $(event.target).val();
    const group = role && roleGroup[role] ? roleGroup[role] : '';
    $('[name^="group"]').zui('picker').$.setValue(group);
}
