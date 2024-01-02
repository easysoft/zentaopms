// Prevent login page show in a iframe modal
if(window.self !== window.top) window.top.location.href = window.location.href;

$(document).ready(function()
{
    timeoutID = null;

    /* Fix bug for misc-ping */
    $('#hiddenwin').removeAttr('id');

    var $login = $('#login');
    var adjustPanelPos = function()
    {
        var bestTop = Math.max(0, Math.floor($(window).height() - $login.outerHeight())/2);
        $login.css('margin-top', bestTop);
    };
    adjustPanelPos();
    $(window).on('resize', adjustPanelPos);

    $('#account').focus();

    $("#langs li > a").click(function()
    {
        selectLang($(this).data('value'));
    });

    $('#loginPanel #submit').click(function()
    {
        var account          = $('#account').val().trim();
        var password         = $('input:password').val().trim();
        var passwordStrength = computePasswordStrength(password);

        var hasMD5    = typeof(md5) == 'function';
        var referer   = $('#referer').val();
        var link      = createLink('user', 'login');
        var keepLogin = $('#keepLoginon').attr('checked') == 'checked' ? 1 : 0;
        var captcha   = $('#captcha').length == 1 ? $('#captcha').val() : '';
        var timeout   = true;

        clearTimeout(timeoutID);
        timeoutID = setTimeout(function()
        {
            if(timeout) alert(loginTimeoutTip);
        }, 4000);

        $.get(createLink('user', 'refreshRandom'), function(data)
        {
            var rand = data;
            timeout  = false;

            $.ajax
            ({
                url: link,
                dataType: 'json',
                method: 'POST',
                data:
                {
                    "account": account,
                    "password": hasMD5 ? md5(md5(password) + rand) : password,
                    'passwordStrength' : passwordStrength,
                    'referer' : referer,
                    'verifyRand' : rand,
                    'keepLogin' : keepLogin,
                    'captcha' : captcha
                },
                success:function(data)
                {
                    if(data.result == 'fail')
                    {
                        alert(data.message);
                        if($('.captchaBox').length == 1) $('.captchaBox .input-group .input-group-addon img').click();
                        return false;
                    }

                    location.href = data.locate;
                }
            })
        });

        return false;
    });

    /**
     *  Refresh captcha.
     */
    $('.captchaBox .input-group .input-group-addon img').click(function()
    {
        var captchaLink = createLink('misc', 'captcha', "sessionVar=captcha");
        captchaLink += captchaLink.indexOf('?') < 0 ? '?' : '&';
        captchaLink += 's=' + Math.random();

        $(this).attr('src', captchaLink);
    })
});

/**
 * Show notice for one click package use weak password.
 *
 * @access public
 * @return void
 */
function showNotice()
{
    if(typeof(process4Safe) == 'string') bootbox.alert(process4Safe)
}
