function setTranslateView(view)
{
    $.cookie('translateView', view, {expires:config.cookieLife, path:config.webRoot});
    location.reload();
}

$(function()
{
    adjustKeyWidth();
});
