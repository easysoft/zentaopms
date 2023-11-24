$(function()
{
    password1Encrypted = false;
    password2Encrypted = false;
    changeType();
});

/**
 * When password1 change.
 *
 * @access public
 * @return void
 */
function password1Change()
{
    password1Encrypted = false;
}

/**
 * When password2 change.
 *
 * @access public
 * @return void
 */
function password2Change()
{
    password2Encrypted = false;
}

function computePassword()
{
    if(!password1Encrypted || !password2Encrypted)
    {
        var password1 = $('#password1').val().trim();
        var password2 = $('#password2').val().trim();
        if(!password1Encrypted)
        {
            $("#passwordStrength").val(computePasswordStrength(password1));
            $("#passwordLength").val(password1.length);
        }


        var rand = $('input#verifyRand').val();
        if(password1 && !password1Encrypted) $('#password1').val(md5(password1) + rand);
        if(password2 && !password2Encrypted) $('#password2').val(md5(password2) + rand);
        password1Encrypted = true;
        password2Encrypted = true;
    }
}

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
