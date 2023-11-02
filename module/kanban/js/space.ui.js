window.changeShowClosed = function()
{
    var showClosed = $('#showClosed').prop('checked') ? 1 : 0;
    $.cookie.set('showClosed', showClosed, {expires:config.cookieLife, path:config.webRoot});
    loadPage();
}
