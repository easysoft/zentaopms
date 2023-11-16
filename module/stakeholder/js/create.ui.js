/**
 * 干系人类型变更回调函数。
 *
 * @return void
 */
function toggleUser()
{
    if($(this).val() == 'team')    var link = $.createLink('stakeholder', 'ajaxGetMembers', 'user=&program=' + programID + '&projectID=' + projectID);
    if($(this).val() == 'company') var link = $.createLink('stakeholder', 'ajaxGetCompanyUser', 'user=&programID=' + programID + '&projectID=' + projectID);
    if($(this).val() == 'outside') var link = $.createLink('stakeholder', 'ajaxGetOutsideUser', 'objectID=' + programID ? programID : projectID);
    $.getJSON(link, function(users)
    {
        let $userPicker = $('[name^="user"]').zui('picker');
        $userPicker.render({items: users});
        $userPicker.$.setValue('');
    });

    $('[name=newUser]').prop('checked', false);
    $('[name=newUser]').parents('.input-group-addon').toggleClass('hidden', $(this).val() != 'outside');

    toggleNewUserInfo();
}

function toggleNewUserInfo()
{
    let $userPicker = $('[name^="user"]').zui('picker');
    $userPicker.render({disabled: $('[name=newUser]').prop('checked')});

    $('.user-info').toggleClass('hidden', !$('[name=newUser]').prop('checked'));
}

/**
 * 公司下拉列表选择公司触发回调函数。
 *
 * @return void
 */
function onChooseCompany(event)
{
    $('#company').val($(event.target).val());
}

/**
 * 新建公司选择框变更回调函数。
 *
 * @return void
 */
function onChangeNewCompany(event)
{
    const checkbox        = $(event.target);
    const checkboxChecked = checkbox.prop('checked');
    const companyInput    = $('#company');
    const companySelect   = $('[name="companySelect"]');

    /* Reset empty default value. */
    companyInput.prop('value', '');
    companySelect.prop('value', '');

    /* Switch between 'select' and 'input'. */
    if(checkboxChecked)
    {
        checkbox.prop('value', 1);
        companyInput.removeClass('hidden');
        companyInput.trigger('focus');
        companySelect.addClass('hidden');
    }
    else
    {
        checkbox.prop('value', 0);
        companyInput.removeClass('hidden');
        companyInput.addClass('hidden');
        companySelect.removeClass('hidden');
        companySelect.trigger('focus');
    }

    /* Remove error tips and error style. */
    companyInput.removeClass('has-error');
    companySelect.removeClass('has-error');
    const tip = checkbox.parents('.form-group').children('.form-tip');
    if(tip)
    {
        tip.remove();
    }
}
