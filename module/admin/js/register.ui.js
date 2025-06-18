window.changeAgreeUX = function(e)
{
    e = $(e);
    $.post($.createLink('admin', 'changeAgreeUX'),{agreeUX:e.prop('checked')},function(response)
    {
        response = JSON.parse(response);
        zui.Messager.show(response.message);
    });
}

window.unBind = function()
{
    $.post($.createLink('admin', 'unBindCommunity'),{},function(response)
    {
        response = JSON.parse(response);
        zui.Messager.show(response.message);
        if(response.result == 'success')
        {
            setTimeout(function() {
                location.href = response.load;
            }, 1000);
        }
    });
}

window.getCaptchaContent = function(ele)
{
    $ele = $(ele);
    $.get($.createLink('admin', 'getCaptcha'), function(response)
    {
        response = JSON.parse(response);
        if(response.result == 'success') $ele.html(response.captchaContent);
    });
}

window.showCaptcha = function()
{
    $('.captcha-mobile-sender').show();
}

window.checkMobileSender = function(e)
{
    e = $(checkMobileSenderID);
    e.preventDefault();
    $('#captchaImageError').html('');
    $('#captchaMobileError').html('');
    $.post($.createLink('admin', 'sendCode'),{mobile:$('#mobile-captcha').val(), captchaContent: $('#captchaImage').val()},function(response)
    {
        response = JSON.parse(response);
        zui.Messager.show(response.message);
        if(response.result == 'success')
        {
            countdown = 60;
            setSmsSenderTime();
            $('.captcha-mobile-sender').hide();
            $('#captchaMobileError').html(response.message);
        }
        else
        {
            $('#captchaImageError').html(response.message);
            if(response.captchaContent) $('.captch-box .image-box').html(response.captchaContent);
        }
    });
}

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
