window.clickCopyCard = function(event)
{
    setCopyKanban($(event.target).closest('.copy-card').data('id'));
    $('#copyKanbanModal').modal('hide');
}

window.setCopyKanban = function(kanbanID)
{
    copyRegion = $('[name=copyRegionInfo]').prop('checked');
    const url = $.createLink('kanban', 'create', 'spaceID=' + spaceID + '&type=' + spaceType + '&copyKanbanID=' + kanbanID + '&exyra=copyRegion=' + (copyRegion ? '1' : '0'));
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
