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

$(document).on('click', '.unlinkStory', function(e)
{
    const $this = $(e.target).closest('li').find('.unlinkStory');
    zui.Modal.confirm({message: unlinkStoryTip, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.get($this.attr('url'), function(){$this.closest('li').remove()});
    });
});

window.ajaxDelete = function(storyID)
{
    zui.Modal.confirm({message: confirmDeleteTip, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.get($.createLink('story', 'delete', 'storyID=' + storyID + '&confirm=yes'), function(data){if(data.result == 'success') loadPage(data.load)});
    });
}
