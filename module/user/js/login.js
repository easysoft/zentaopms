// Prevent login page show in a iframe modal
if(window.self !== window.top) window.top.location.href = window.location.href;

$(document).ready(function()
{
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
        var rand      = $('input#verifyRand').val();
        var referer   = $('#referer').val();
        var link      = createLink('user', 'login');
        var keepLogin = $('#keepLoginon').attr('checked') == 'checked' ? 1 : 0;

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
            },
            success:function(data)
            {
                if(data.result == 'fail') 
                {
                    alert(data.message);
                    return false;
                }
                else
                {
                    location.href = data.locate;
                }
            }
        })

        return false;
    });
});
