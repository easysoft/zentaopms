window.changeSpaceType = function()
{
    const type = $('[name=type]:checked').val();
    $('.ownerBox').toggleClass('hidden', type == 'private');
    $('.teamBox').toggleClass('hidden', type == 'private');
    $('.whitelistBox').toggleClass('hidden', type != 'private');
}

window.changeKanbanType = function()
{
    const type = $('[name=type]:checked').val();
    const url  = $.createLink('kanban', 'create', 'spaceID=' + spaceID + '&type=' + type);
    loadPartial(url, '#WIPCountBox, #spaceBox, #nameBox, #ownerBox, #teamBox, #fixedColBox, #autoColBox, #archiveBox, #manageProgressBox, #alignmentBox, #descBox, #whitelistBox');
}

window.loadAllUsers = function()
{
    const link = $.createLink('kanban', 'ajaxLoadUsers', 'spaceID=0&field=owner&selectedUser=' + $('[name=owner]').val() + "&type=all");
    $.getJSON(link, function(data)
    {
        $('[name=owner]').zui('picker').render({items: data});
    });
}

window.changeKanbanSpace = function()
{
    const spaceID = $('[name=space]').val();
    if(spaceType == 'private')
    {
        const url = $.createLink('kanban', 'ajaxLoadUsers', 'spaceID=' + spaceID + '&field=whitelist&selectedUser=' + $('[name^=whitelist]').val());
        $.getJSON(url, function(data)
        {
            $('[name^=whitelist]').zui('picker').render({items: data});
        });
    }
    else
    {
        url = $.createLink('kanban', 'ajaxLoadUsers', 'spaceID=' + spaceID + '&field=owner&selectedUser=' + $('[name=owner]').val());
        $.getJSON(url, function(data)
        {
            $('[name=owner]').zui('picker').render({items: data});
        });
    }
}
