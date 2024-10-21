window.setDownloading = function()
{
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true; // Opera don't support, omit it.

    $.cookie.set('downloading', 0, {expires:config.cookieLife, path:config.webRoot});

    time = setInterval(function()
    {
        if($.cookie.get('downloading') == 1)
        {
            $('.modal .modal-actions .close')[0].click();

            $.cookie.set('downloading', null, {expires:config.cookieLife, path:config.webRoot});

            clearInterval(time);
        }
    }, 300);

    return true;
}
