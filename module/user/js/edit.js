$(function()
{
    var password1Encrypted = false
    var password2Encrypted = false
    $('#password1').change(function(){password1Encrypted = false});
    $('#password2').change(function(){password2Encrypted = false});
    $('#submit').click(function()
    {
        var password1 = $('#password1').val();
        var password2 = $('#password2').val();
        var rand      = $('input#verifyRand').val();
        if(password1 && !password1Encrypted) $('#password1').val(md5(password1) + rand);
        if(password2 && !password2Encrypted) $('#password2').val(md5(password2) + rand);
        password1Encrypted = true;
        password2Encrypted = true;
    })
});
