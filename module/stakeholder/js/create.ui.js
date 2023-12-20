/**
 * 干系人类型变更回调函数。
 *
 * @return void
 */
function toggleUser()
{
    if($(this).val() == 'team')    var link = $.createLink('stakeholder', 'ajaxGetMembers', 'program=' + programID + '&projectID=' + projectID);
    if($(this).val() == 'company') var link = $.createLink('stakeholder', 'ajaxGetCompanyUser', 'programID=' + programID + '&projectID=' + projectID);
    if($(this).val() == 'outside') var link = $.createLink('stakeholder', 'ajaxGetOutsideUser', 'objectID=' + programID ? programID : projectID);
    $.getJSON(link, function(users)
    {
        console.log(users);
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
function toggleCompany()
{
    let $companyPicker = $('[name^="company"]').zui('picker');

    /* Reset empty default value. */
    $('#companyName').val('');
    $companyPicker.$.setValue('');

    $('#companyName').toggleClass('hidden', !$(this).prop('checked'));

    $('.company-picker').toggleClass('hidden', $(this).prop('checked'));
}
