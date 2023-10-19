let originalEncrypted  = false;
let password1Encrypted = false
let password2Encrypted = false
let passwordStrength   = 0;

function changePassword()
{
    const id = $(event.target).attr('id');
    if(id == 'originalPassword') originalEncrypted  = false;
    if(id == 'password1') password1Encrypted = false;
    if(id == 'password2') password2Encrypted = false;
};

function clickSubmit()
{
    const password  = $('#originalPassword').val().trim();
    const password1 = $('#password1').val().trim();
    const password2 = $('#password2').val().trim();
    const rand      = $('#verifyRand').val();
    if(!password1Encrypted)
    {
        passwordStrength = computePasswordStrength(password1);
        $('#passwordLength').val(password1.length);
    }

    if($('form input[name=passwordStrength]').length == 0) $(event.target).after("<input type='hidden' name='passwordStrength' value='0' />");
    $('form input[name=passwordStrength]').val(passwordStrength);

    if(password  && !originalEncrypted)  $('#originalPassword').val(md5(md5(password) + rand));
    if(password1 && !password1Encrypted) $('#password1').val(md5(password1) + rand);
    if(password2 && !password2Encrypted) $('#password2').val(md5(password2) + rand);
    originalEncrypted  = true;
    password1Encrypted = true;
    password2Encrypted = true;
}

$(function() {
    if(isonlybody) return;
    var secondButton = $(".form-actions button").eq(1);

    secondButton.removeClass('open-url');
    secondButton.off("click").on("click", function() {
        $.get($.createLink('user', 'logout'), function(data)
        {
            location.href = $.createLink('user', 'login');
        });
    });
})
