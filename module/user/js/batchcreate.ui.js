/**
 * 根据用户类型切换控件的显示和隐藏。
 * Toggle the display of controls according to the user type.
 *
 * @param  event  event
 * @access public
 * @return void
 */
function batchChangeType(event)
{
    const type     = $(event.target).val();
    const isInside = type == 'inside';
    $('input[data-name="type"]').val(type);
    $('[data-name="companyItem"]').toggleClass('hidden', isInside);
    $('[data-name="dept"], [data-name="join"]').toggleClass('hidden', !isInside);
    $('#userType').val(type);
}

/**
 * 根据职位更改权限组。
 * Change the permission group according to the role.
 *
 * @param  role   $role
 * @access public
 * @return void
 */
function batchChangeRole(event)
{
    const role  = $(event.target).val();
    const group = role && roleGroup[role] ? roleGroup[role] : '';
    $(event.target).closest('tr').find('[name^="group"]').zui('picker').$.setValue(group);
}

/**
 * 切换显示选择所属公司或添加公司的输入框。
 * Toggle display company picker or input when add or edit a user.
 *
 * @param  event  event
 * @access public
 * @return void
 */
function batchToggleNew(event)
{
    const $currentRow = $(event.target).closest('tr');
    const $company    = $currentRow.find('div[data-name="company"]').closest('.picker-box').toggleClass('hidden');
    const $newCompany = $currentRow.find('input[data-name="newCompany"]').toggleClass('hidden');
}

/**
 * 切换显示密码强度。
 * Toggle display password strength.
 *
 * @param  event  event
 * @access public
 * @return void
 */
function batchTogglePasswordStrength(event)
{
    const $target   = $(event.target);
    const password  = $target.val();
    const $strength = $target.closest('tr').find('.passwordStrength');
    $strength.toggleClass('hidden', password == '');
    $strength.html(password == '' ? '' : passwordStrengthList[computePasswordStrength(password)]);
}

/**
 * 切换界面类型。
 * When the visions is changed.
 *
 * @param  event  event
 * @access public
 * @return void
 */
function batchChangeVision(event)
{
    const $target = $(event.target);
    const visions = $target.val();
    const link = $.createLink('user', 'ajaxGetGroups', 'visions=' + visions);
    $.getJSON(link, function(data)
    {
        let $currentRow = $(event.target).closest('tr');
        let group  = $currentRow.find('[name^="group"]').val();
        let $group = $currentRow.find('[name^="group"]').zui('picker');
        $group.render({items: data});
        $group.$.setValue(group);

        let $row = $currentRow.next('tr');
        while($row.length)
        {
            if($row.find('[data-name="visions"]').attr('data-ditto') != 'on') break;

            group  = $row.find('[name^="group"]').val();
            $group = $row.find('[name^="group"]').zui('picker');
            $group.render({items: data});
            $group.$.setValue(group);
            $row = $row.next('tr');
        }
    });
}
