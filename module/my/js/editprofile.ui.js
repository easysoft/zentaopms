let password1Encrypted = false;
let password2Encrypted = false;
let verifyEncrypted    = false;

/**
 * 密码改变时标记密码未加密。
 * Mark password unencrypted when password changes.
 *
 * @param  event  event
 * @access public
 * @return void
 */
function changePassword(event)
{
    const targetID = $(event.target).attr('id');
    if(targetID == 'password1')      password1Encrypted = false;
    if(targetID == 'password2')      password2Encrypted = false;
    if(targetID == 'verifyPassword') verifyEncrypted    = false;
}

/**
 * 加密密码并记录密码强度和长度。
 * Encrypt password and record password strength and length.
 *
 * @access public
 * @return void
 */
function encryptPassword()
{
    const rand = $('input[name=verifyRand]').val();

    /* 加密当前登录用户的密码。*/
    /* Encrypt password of current user. */
    if($('input#verifyPassword').length > 0)
    {
        const password = $('input#verifyPassword').val().trim();
        if(password && !verifyEncrypted)
        {
            $('input#verifyPassword').val(md5(md5(password) + rand));
            verifyEncrypted = true;
        }
    }

    if($('#password1').length == 0 || $('#password2').length == 0) return;

    /* 加密新添加用户或被修改用户的密码 1，并记录密码强度和长度。*/
    /* Encrypt password 1 of new or modified user, and record password strength and length. */
    const password1 = $('#password1').val().trim();
    if(password1 && !password1Encrypted)
    {
        $('#password1').val(md5(password1) + rand);
        $("input[name=passwordStrength]").val(computePasswordStrength(password1));
        $("input[name=passwordLength]").val(password1.length);
        password1Encrypted = true;
    }

    /* 加密新添加用户或被修改用户的密码 2。*/
    /* Encrypt password 2 of new or modified user. */
    const password2 = $('#password2').val().trim();
    if(password2 && !password2Encrypted)
    {
        $('#password2').val(md5(password2) + rand);
        password2Encrypted = true;
    }
}
