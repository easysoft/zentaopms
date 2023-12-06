function setDownloading(event)
{
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true; // Opera don't support, omit it.

    $.cookie.set('downloading', 0, {expires:config.cookieLife, path:config.webRoot});

    time = setInterval(function()
    {
        if($.cookie.get('downloading') == 1)
        {
            $(event.target).closest('div.modal')[0].classList.remove('show');

            $.cookie.set('downloading', null, {expires:config.cookieLife, path:config.webRoot});

            clearInterval(time);
        }
    }, 300);

    return true;
}
