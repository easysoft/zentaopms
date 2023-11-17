window.clickCopyCard = function(event)
{
    setCopyKanban($(event.target).closest('.copy-card').data('id'));
    $('#copyKanbanModal').modal('hide');
}

window.setCopyKanban = function(kanbanID)
{
    const url = $.createLink('kanban', 'create', 'spaceID=' + spaceID + '&type=' + spaceType + '&copyKanbanID=' + kanbanID);
    loadPartial(url, '#WIPCountBox, #spaceBox, #nameBox, #ownerBox, #teamBox, #fixedColBox, #autoColBox, #archiveBox, #manageProgressBox, #alignmentBox, #descBox, #whitelistBox');
}

window.toggleImportObjectBox = function(e)
{
    let isImport = $(e.target).val() == 'on';
    if(!isImport)
    {
        $("input[name^='importObjectList']").attr('disabled', 'disabled');
        $('#objectBox').hide();
    }
    else
    {
        $("input[name^='importObjectList']").removeAttr('disabled');
        $('#objectBox').show();
    }
}

window.waitDom('input[name=fluidBoard]', function()
{
    handleKanbanWidthAttr();
});
