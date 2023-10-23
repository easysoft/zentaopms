$(document).ready(function()
{
    var password1Encrypted = false
    var password2Encrypted = false
    var passwordStrength   = 0;
    $('#password1').on('change', function(){password1Encrypted = false});
    $('#password2').on('change', function(){password2Encrypted = false});

    $('form .btn[type="submit"]').on('click', function()
    {
        if(!password1Encrypted || !password2Encrypted)
        {
            var password1 = $('#password1').val().trim();
            var password2 = $('#password2').val().trim();

            if(!password1Encrypted)
            {
                passwordStrength = computePasswordStrength(password1);
                $("#passwordLength").val(password1.length);
            }
            if($("form input[name=passwordStrength]").length == 0) $('#passwordLength').after("<input type='hidden' name='passwordStrength' value='0' />");
            $("form input[name=passwordStrength]").val(passwordStrength);

            var rand      = $('input#verifyRand').val();
            if(password1 && !password1Encrypted) $('#password1').val(md5(password1) + rand);
            if(password2 && !password2Encrypted) $('#password2').val(md5(password2) + rand);
            password1Encrypted = true;
            password2Encrypted = true;
        }
    })
});
