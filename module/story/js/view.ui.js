$(document).on('mouseenter', '.detail-side .tab-pane ul li', function(e)
{
    $(this).find('.unlink').removeClass('hidden');
    e.stopPropagation();
});

$(document).on('mouseleave', '.detail-side .tab-pane ul li', function(e)
{
    $(this).find('.unlink').addClass('hidden');
    e.stopPropagation();
});

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

window.waitDom('body.body-modal .toolbar', function()
{
    $('.body-modal .toolbar a[data-load="modal"]').attr('data-toggle', 'modal').removeAttr('data-load');
})

window.renderChildCell = function(result, info)
{
    if(info.col.name == 'title' && result)
    {
        let html       = '';
        const story    = info.row.data;
        const gradeMap = gradeGroup[story.type] || {};
        let gradeLabel = gradeMap[story.grade];

        if(gradeLabel) html += "<span class='label gray-pale rounded-xl clip'>" + gradeLabel + "</span> ";
        if(html) result.unshift({html});
    }
    return result;
}
