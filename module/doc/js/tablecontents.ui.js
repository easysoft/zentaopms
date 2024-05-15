$(document).off('click', '#actionBar #' + exportMethod).on('click', '#actionBar #' + exportMethod, function()
{
    const dtable = zui.DTable.query($('#mainContent .dtable'));
    if(!$('#mainContent .dtable').length) return;

    const checkedList = dtable.$.getChecks();
    $.cookie.set('checkedItem', checkedList, {expires:config.cookieLife, path:config.webRoot});
});
