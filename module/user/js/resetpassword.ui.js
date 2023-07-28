$(function()
{
    if(expired)
    {
        setInterval(function()
        {
            var time = $('#time').text();
            if(time == 0) window.location.href = $('.alert-info .btn').attr('href');
            $('#time').text(time - 1 <= 0 ? 0 : time - 1);
        }, 1000);
    }

    var password1Encrypted = false
    var password2Encrypted = false
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
                $("#passwordLength").val(password1.length);
            }

            var rand      = $('input#verifyRand').val();
            if(password1 && !password1Encrypted) $('#password1').val(md5(password1) + rand);
            if(password2 && !password2Encrypted) $('#password2').val(md5(password2) + rand);
            password1Encrypted = true;
            password2Encrypted = true;
        }
    })
})
