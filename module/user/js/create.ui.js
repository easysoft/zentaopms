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

        if($("form input[name=passwordStrength]").length == 0) $('#passwordLength').after("<input type='hidden' name='passwordStrength' value='0' />");
        $("form input[name=passwordStrength]").val(passwordStrength);

        const rand = $('input#verifyRand').val();
        if(password1 && !password1Encrypted) $('#password1').val(md5(password1) + rand);
        if(password2 && !password2Encrypted) $('#password2').val(md5(password2) + rand);

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
