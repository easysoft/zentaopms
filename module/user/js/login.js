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
        var password         = $('input:password').val().trim();
        var passwordStrength = computePasswordStrength(password);

        if($('#loginPanel #passwordStrength').length == 0) $(this).after("<input type='hidden' name='passwordStrength' id='passwordStrength' value='0' />");
        $('#loginPanel #passwordStrength').val(passwordStrength);

        var rand = $('input#verifyRand').val();
        if(password.length != 32 && typeof(md5) == 'function') $('input:password').val(md5(md5(password) + rand));
    });
});
