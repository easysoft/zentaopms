/**
 * 干系人类型变更回调函数。
 *
 * @return void
 */
function onChangeStakeholderType(event)
{
    if($(event.target).val() === 'outside')
    {
        toggleNewUserCheckbox(true);
        return;
    }

    toggleNewUserCheckbox(false);
}

/*
 * 显示新建用户选择框。
 *
 * @return void
 */
function toggleNewUserCheckbox(remove)
{
    const elements = $('.newuser-checkbox');
    if(remove)
    {
        elements.removeClass('hidden');
        return;
    }

    elements.addClass('hidden');
}

/**
 * 新建用户选择框变更回调函数。
 *
 * @return void
 */
function onChangeNewUserCheckbox(event)
{
    const checked = $(event.target).prop('checked');

    toggleUserInfo(checked);

    $('#user').prop('disabled', checked);
}

/**
 * 显示新建用户信息输入框。
 *
 * @return void
 */
function toggleUserInfo(remove)
{
    const elements = $('.user-info');
    if(remove)
    {
        elements.removeClass('hidden');
        return;
    }

    elements.addClass('hidden');
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
