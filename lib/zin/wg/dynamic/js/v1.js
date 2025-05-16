window.showMore = function(e)
{
    let $this = $(e.target);
    if($this.attr('id') != 'showMoreDynamic') $this = $this.closest('#showMoreDynamic');
    $this.find('i').removeClass('icon-chevron-down').addClass('spin icon-refresh');

    let $appendTo    = $this.closest('li').prev().find('ul');
    let lastActionID = $this.data('lastid');
    $.get($.createLink('action', 'ajaxGetMoreActions', "lastActionID=" + lastActionID), function(data)
    {
        $appendTo.append(data);

        let hasMore = $appendTo.children('#hasMore').html() == '1';
        $this.attr('data-lastid', $appendTo.children('#lastid').html());
        $this.find('i').addClass('icon-chevron-down').removeClass('spin icon-refresh');
        if(!hasMore) $this.closest('li').remove();

        $appendTo.children('style').remove();
        $appendTo.children('script').remove();
        $appendTo.children('#lastid').remove();
        $appendTo.children('#hasMore').remove();
    })
}
