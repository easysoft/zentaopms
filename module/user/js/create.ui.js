$(function()
{
    password1Encrypted = false;
    password2Encrypted = false;
    verifyEncrypted    = false;
    passwordStrength   = 0;
})

function changePassword(event)
{
    if($(event.target).attr('id') == 'password1')
    {
        password1Encrypted = false;
    }
    if($(event.target).attr('id') == 'password2')
    {
        password2Encrypted = false;
    }
    if($(event.target).attr('id') == 'verifyPassword')
    {
        verifyEncrypted = false;
    }
}

function changeType(event)
{
    const type = $(event.target).val();
    if(type == 'inside')
    {
        $('#companyBox').addClass('hidden');
        $('[name="dept"]').closest('.form-row').removeClass('hidden');
        $('[name="join"]').closest('.form-row').removeClass('hidden');
        $('#commiter').closest('.form-row').removeClass('hidden');
    }
    else
    {
        $('#companyBox').removeClass('hidden');
        $('[name="dept"]').closest('.form-row').addClass('hidden');
        $('[name="join"]').closest('.form-row').addClass('hidden');
        $('#commiter').closest('.form-row').addClass('hidden');
    }
}

function changeAddCompany(event)
{
    const checked = $(event.target).prop('checked');
    if(checked)
    {
        const $inputGroup = $('[name="company"]').closest('.picker-box');
        if($inputGroup.length == 0) return;
        $('[name="company"]').zui('picker').destroy();
        $inputGroup.replaceWith("<input name='company' id='company' class='form-control'/>");
    }
    else
    {
        const link = $.createLink('company', 'ajaxGetOutsideCompany');
        $.post(link, function(data)
        {
            var $companyPicker = $('#company').replaceWith('<div id="companyPicker" class="form-group-wrapper picker-box"></div>');
            if(data)
            {
                data = JSON.parse(data);
                new zui.Picker('#companyPicker', {name: 'company', items: data});
            }
        })
    }
}

function clickSubmit()
{
    if(!password1Encrypted || !password2Encrypted)
    {
        const password1 = $('#password1').val();
        const password2 = $('#password2').val();
        const verifyPassword = $('#verifyPassword').val();
        if(!password1Encrypted)
        {
            passwordStrength = computePasswordStrength(password1);
            $("#passwordLength").val(password1.length);
        }

        $('#passwordLength').after("<input type='hidden' name='passwordStrength' value='" + passwordStrength + "' />");

        const rand = $('input#verifyRand').val();
        if(password1 && !password1Encrypted) $('#password1').val(md5(password1) + rand);
        if(password2 && !password2Encrypted) $('#password2').val(md5(password2) + rand);
        if(verifyPassword && !verifyPassword) $('#verifyPassword').val(md5(verifyPassword) + rand);

        password1Encrypted = true;
        password2Encrypted = true;
    }
}

/**
 * Change group when change role.
 *
 * @param  role $role
 * @access public
 * @return void
 */
function changeRole(event)
{
    var role = $(event.target).val();
    if(role && roleGroup[role])
    {
        $('[name^="group"]').zui('picker').$.setValue(roleGroup[role]);
    }
    else
    {
        $('[name^="group"]').zui('picker').$.setValue('');
    }
}
