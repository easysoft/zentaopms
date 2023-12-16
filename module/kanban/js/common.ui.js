window.updateKanbanRegion = function(regionID, kanbanData)
{
    if(!regionID || !kanbanData) return;
    $('.kanban-list').zui('kanbanlist').$.getKanban(regionID).update(kanbanData);
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
    const link = $.createLink('kanban', 'ajaxLoadUsers', 'spaceID=0&field=owner&type=all');
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
        const url = $.createLink('kanban', 'ajaxLoadUsers', 'spaceID=' + spaceID + '&field=whitelist');
        $.getJSON(url, function(data)
        {
            $('[name^=whitelist]').zui('picker').render({items: data});
        });
    }
    else
    {
        url = $.createLink('kanban', 'ajaxLoadUsers', 'spaceID=' + spaceID + '&field=owner');
        $.getJSON(url, function(data)
        {
            $('[name=owner]').zui('picker').render({items: data});
        });
    }
}

/**
 * Handle radio logic of Kanban column width setting.
 *
 * @access public
 * @return void
 */
window.handleKanbanWidthAttr = function()
{
    $('#colWidth, #minColWidth, #maxColWidth').attr('onkeyup', 'value=value.match(/^\\d+$/) ? value : ""');
    $('#colWidth, #minColWidth, #maxColWidth').attr('maxlength', '3');
    let fluidBoard = $(".form input[name='fluidBoard']:checked").val() || 0;
    let addAttrEle = fluidBoard == 0 ? '#colWidth' : '#minColWidth, #maxColWidth';
    let $fixedTip  = $('#fixedColBox .fixedTip');
    let $autoTip   = $('#autoColBox .autoTip');
    $(addAttrEle).closest('.width-radio-row').addClass('required');
    if(fluidBoard == 1)
    {
        $('#colWidth').attr('disabled', true);
        $('#minColWidth, #maxColWidth').removeAttr('disabled');
    }
    else
    {
        $('#colWidth').removeAttr('disabled');
        $('#minColWidth, #maxColWidth').attr('disabled', true);
    }

    $(document).on("#minColWidth, #maxColWidth", 'input', function()
    {
        $('#minColWidthLabel, #maxColWidthLabel').remove();
        $('#minColWidth, #maxColWidth').removeClass('has-error');
    });

    if(fluidBoard == 1)
    {
        $fixedTip.addClass('hidden');
        $autoTip.removeClass('hidden');
        $('#colWidth').closest('div').removeClass('required');
        $('#maxColWidth').closest('div').addClass('required');
    }
    else
    {
        $fixedTip.removeClass('hidden');
        $autoTip.addClass('hidden');
        $('#colWidth').closest('div').addClass('required');
        $('#maxColWidth').closest('div').removeClass('required');
    }
}
