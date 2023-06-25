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

$(document).on('click', '.relievedTwins', function(e)
{
    const $this    = $(e.target).closest('li').find('.relievedTwins');
    const postData = new FormData();
    postData.append('twinID', $this.data('id'));
    zui.Modal.confirm({message: relievedTip, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.post($.createLink('story', 'ajaxRelieveTwins'), postData, function(){$this.closest('li').remove()});
    });
});

$(document).on('click', '.unlinkStory', function(e)
{
    const $this = $(e.target).closest('li').find('.relievedTwins');
    zui.Modal.confirm({message: unlinkStoryTip, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.get($this.data('url'), function(){$this.closest('li').remove()});
    });
});
