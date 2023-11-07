window.updateKanbanRegion = function(regionID, kanbanData)
{
    $('.kanban-list').zui('kanbanlist').$.getKanban(regionID).update(kanbanData);
}

window.updateKanbanGroup = function(key, kanbanData)
{
    $('.kanban-list').zui('kanbanlist').$.getKanban(key).update(kanbanData);
}

window.changeSpaceType = function()
{
    const type = $('[name=type]:checked').val();
    $('.ownerBox').toggleClass('hidden', type == 'private');
    $('.teamBox').toggleClass('hidden', type == 'private');
    $('.whitelistBox').toggleClass('hidden', type != 'private');
}

window.changeKanban = function()
{
    const targetID = $('[name=kanban]').val();
    const url      = $.createLink('kanban', methodName, 'kanbanID=' + kanbanID + '&regionID=' + regionID + '&groupID=' + groupID + '&columnID=' + columnID + '&targetID=' + targetID);
    loadPartial(url, '#cardForm');
}

window.changeProduct = function()
{
    const targetID = $('[name=product]').val();
    const url      = $.createLink('kanban', methodName, 'kanbanID=' + kanbanID + '&regionID=' + regionID + '&groupID=' + groupID + '&columnID=' + columnID + '&targetID=' + targetID);
    loadPartial(url, '#linkForm');
}

window.changeProject = function()
{
    const targetID = $('[name=project]').val();
    const url      = $.createLink('kanban', methodName, 'kanbanID=' + kanbanID + '&regionID=' + regionID + '&groupID=' + groupID + '&columnID=' + columnID + '&targetID=' + targetID);
    loadPartial(url, '#linkForm');
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
