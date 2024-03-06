window.ajaxDelete = function(storyID)
{
    zui.Modal.confirm({message: confirmDeleteTip, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.get($.createLink('story', 'delete', 'storyID=' + storyID + '&confirm=yes'), function(data){if(data.result == 'success') loadPage(data.load)});
    });
}

$('#mainContent .files').removeClass('px-6');

window.refreshViewModal = function()
{
    if(isInModal)
    {
        loadModal($.createLink('story', 'view', 'storyID=' + storyID), $('.modal').attr('id'));
    }
    else
    {
        loadPage($.createLink('story', 'view', 'storyID=' + storyID));
    }
}
