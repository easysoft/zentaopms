$(document).ready(function()
{
    var verifyEncrypted = false;
    $('#verifyPassword').change(function(){verifyEncrypted = false});

    var password1Encrypted = false
    var password2Encrypted = false
    $('#password1').change(function(){password1Encrypted = false});
    $('#password2').change(function(){password2Encrypted = false});

    $('#submit').click(function()
    {
        var password         = $('input#verifyPassword').val();
        var password1        = $('input#password1').val();
        var passwordStrength = computePasswordStrength(password1);

        if($("form input[name=passwordStrength]").length == 0) $('#submit').after("<input type='hidden' name='passwordStrength' value='0' />");
        $("form input[name=passwordStrength]").val(passwordStrength);

        var rand = $('input#verifyRand').val();
        if(!verifyEncrypted && password) $('input#verifyPassword').val(md5(md5(password) + rand));
        verifyEncrypted = true;

        var password1 = $('#password1').val();
        var password2 = $('#password2').val();
        if(password1 && !password1Encrypted) $('#password1').val(md5(password1) + rand);
        if(password2 && !password2Encrypted) $('#password2').val(md5(password2) + rand);
        password1Encrypted = true;
        password2Encrypted = true;
    });
});
