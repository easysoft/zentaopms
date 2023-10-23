function setDownloading(event)
{
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true; // Opera don't support, omit it.

    $.cookie.set('downloading', 0);

    time = setInterval(function()
    {
        if($.cookie.get('downloading') == 1)
        {
            $(event.target).closest('div.modal')[0].classList.remove('show');

            $.cookie.set('downloading', null);

            clearInterval(time);
        }
    }, 300);

    return true;
}
