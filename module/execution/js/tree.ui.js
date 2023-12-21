window.changeDisplay = function()
{
    $.cookie.set('showStory', $(this).is(':checked') ? 1 : 0, {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
}

window.loadObject = function({event, item})
{
    if(!$(event.target).hasClass('state') && !$(event.target).hasClass('caret-down') && item.url)
    {
        $('#taskTree .tree-item-content').removeClass('active');
        $(event.target).closest('.tree-item-content').addClass('active');

        loadTarget(item.url, '#detailBlock');
        $('#detailBlock').removeClass('hidden');
    }
}
