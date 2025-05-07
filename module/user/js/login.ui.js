window.adjustPanelPos = function()
{
    let $login = $('#login');
    let bestTop = Math.max(0, Math.floor($(window).height() - $login.outerHeight())/2);
    $login.css('margin-top', bestTop);
};

window.refreshCaptcha = function(obj)
{
    let $this = $(obj)
    let captchaLink = $.createLink('misc', 'captcha');
    captchaLink += captchaLink.indexOf('?') < 0 ? '?' : '&';
    captchaLink += 's=' + Math.random();

    $this.attr('src', captchaLink);
};

/**
 * Show notice for one click package use weak password.
 *
 * @access public
 * @return void
 */
window.showNotice = function()
{
    if(typeof(process4Safe) == 'string') zui.Modal.alert(process4Safe);
};

window.switchLang = function(lang)
{
    selectLang(lang);
};

adjustPanelPos();
$(window).on('resize', adjustPanelPos);

timeoutID = null;
window.safeSubmit = function(e)
{
    e.preventDefault();
    e.stopPropagation();

    let account          = $('#account').val().trim();
    let password         = $('input#password[type="password"]').val().trim();
    let passwordStrength = computePasswordStrength(password);

    let hasMD5    = typeof(md5) == 'function';
    let referer   = $('[name=referer]').val();
    let link      = $.createLink('user', 'login');
    let keepLogin = $('#keepLoginon').prop('checked') ? 1 : 0;
    let captcha   = $('#captcha').length == 1 ? $('#captcha').val() : '';
    let timeout   = true;

    clearTimeout(timeoutID);
    timeoutID = setTimeout(function()
    {
        if(timeout) zui.Modal.alert(loginTimeoutTip);
    }, 4000);

    $('#submit').attr('disabled', 'disabled');
    $.get($.createLink('user', 'refreshRandom'), function(rand)
    {
        if(password != '') password = hasMD5 ? md5(md5(password) + rand) : password,
        timeout  = false;

        $.post(link,
        {
            "account"          : account,
            "password"         : password,
            'passwordStrength' : passwordStrength,
            'referer'          : referer,
            'verifyRand'       : rand,
            'keepLogin'        : keepLogin,
            'captcha'          : captcha
        },
        function(data)
        {
            data = JSON.parse(data);
            if(data.result == 'fail')
            {
                zui.Modal.alert(data.message);
                if($('.captchaBox').length == 1) refreshCaptcha($('.captchaBox .input-group .input-group-addon img'));
                clearTimeout(timeoutID);
                $('#submit').removeAttr('disabled').trigger('blur');
                waitDom('.modal.show .btn.primary', function()
                {
                    let $this = $(this);
                    setTimeout(function(){$this.trigger('focus')}, 200);
                })
                return false;
            }

            let anchor = '';
            if(location.hash.indexOf('#app=') === 0)
            {
                const params = $.parseUrlParams(location.hash.substring(1));
                $.cookie.set('tab', params.app, {expires: config.cookieLife, path: config.webRoot});

                anchor = location.hash;
            }
            location.href = data.locate + anchor;
        });
    });

    return false;
};

window.demoSubmit = function($el)
{
    let account          = $($el).attr('data-account');
    let password         = $($el).attr('data-password');
    let link             = $.createLink('user', 'login');
    let timeout          = true;
    let passwordStrength = computePasswordStrength(password);

    clearTimeout(timeoutID);
    timeoutID = setTimeout(function()
    {
        if(timeout) zui.Modal.alert(loginTimeoutTip);
    }, 4000);
    $.post(link,
    {
        "account"          : account,
        "password"         : password,
        'passwordStrength' : passwordStrength,
    },
    function(data)
    {
        data = JSON.parse(data);
        if(data.result == 'fail')
        {
            zui.Modal.alert(data.message);
            return false;
        }

        location.href = data.locate;
    });
}

document.getElementById("account").focus();
