window.clickCopyCard = function(event)
{
    setCopyKanban($(event.target).data('id'));
    $('#copyKanbanModal').modal('hide');
}

window.setCopyKanban = function(kanbanID)
{
    const url = $.createLink('kanban', 'create', 'spaceID=' + spaceID + '&type=' + spaceType + '&copyKanbanID=' + kanbanID);
    loadPartial(url, '#WIPCountBox, #spaceBox, #nameBox, #ownerBox, #teamBox, #fixedColBox, #autoColBox, #archiveBox, #manageProgressBox, #alignmentBox, #descBox, #whitelistBox');
}
