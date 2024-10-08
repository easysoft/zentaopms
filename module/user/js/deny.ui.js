window.locateLogin = function(obj)
{
    let $this = $(obj);
    $.getJSON($this.data('url'), function(data)
    {
        if(typeof data.load != 'undefined') top.location.href = data.load;
    })
}

window.checkGoBackBtn = function()
{
    if(window.top === window) return;
    const historyState = top.window.history.state;
    const canGoBack = historyState && historyState.prev && historyState.prev.code === $.apps.currentCode;
    if(canGoBack) return;
    $('.go-back-btn').hide();
    $('.close-app-btn').removeClass('hidden').on('click', () => $.apps.closeApp($.apps.currentCode));
};
