$().ready(function()
{
    getCaptchaContent($('.image-box'));
});

$('.image-box').on('click', function()
{
    getCaptchaContent($('.image-box'));
});

function getCaptchaContent($ele)
{
    $.get($.createLink('officialwebsite', 'getCaptcha'), function(response)
    {
        response = JSON.parse(response);
        if(response.result == 'success') $ele.html(response.captchaContent);
    });
}

$('#captcha-btn').on('click', function(e)
{
    $('.captcha-mobile-sender').show();
});

$('#checkMobileSender').on('click', function(e)
{
    e.preventDefault();
    $('#captchaImageError').html('');
    $('#captchaMobileError').html('');
    $.post($.createLink('officialwebsite', 'sendcode'),{mobile:$('#mobile-captcha').val(), captchaContent: $('#captchaImage').val()},function(response)
    {
        response = JSON.parse(response);
        if(response.result == 'success')
        {
            countdown = 60;
            setSmsSenderTime();
            $('.captcha-mobile-sender').hide();
            zui.Messager.show({
                message: response.message,
                type: 'primary',
            })
            $('#captchaMobileError').html(response.message);
        }
        else
        {
            $('#captchaImageError').html(response.message);
            if(response.captchaContent) $('.captch-box .image-box').html(response.captchaContent);
        }
    });
});

function setSmsSenderTime()
{
    $('#captcha-btn').html(countdown + 's').off('click').removeClass('captcha-btn-class');
    smsSenderTimer = setInterval(function(){
        countdown -= 1;
        if(countdown > 0){
            $('#captcha-btn').html(countdown + 's').off('click').removeClass('captcha-btn-class');
        }else{
            window.clearInterval(smsSenderTimer);
            $('#captchaMobileError').html('');
            $('#captcha-btn').html('重新发送').on('click', function(e)
            {
                $('#captchaMobileError').html('');
                $('.captcha-mobile-sender').show();
            }).addClass('captcha-btn-class');
        }
    },1000);
}
