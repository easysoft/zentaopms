$(function()
{
    if($('#storyList thead th.c-title').width() < 150) $('#storyList thead th.c-title').width(150);
    $('#storyList td.has-child .story-toggle').each(function()
    {
        var $td = $(this).closest('td');
        var labelWidth = 0;
        if($td.find('.label').length > 0) labelWidth = $td.find('.label').width();
        $td.find('a').eq(0).css('max-width', $td.width() - labelWidth - 60);
    });

    $(document).on('click', '.story-toggle', function(e)
    {
        var $toggle = $(this);
        var id = $(this).data('id');
        var isCollapsed = $toggle.toggleClass('collapsed').hasClass('collapsed');
        $toggle.closest('[data-ride="table"]').find('tr.parent-' + id).toggle(!isCollapsed);

        e.stopPropagation();
        e.preventDefault();
    });

    // Fix state dropdown menu position
    $('.c-stage > .dropdown').each(function()
    {
        var $this = $(this);
        var menuHeight = $(this).find('.dropdown-menu').outerHeight();
        var $tr = $this.closest('tr');
        var height = 0;
        while(height < menuHeight)
        {
            var $next = $tr.next('tr');
            if(!$next.length) break;
            height += $next.outerHeight;
        }
        if(height < menuHeight)
        {
            $this.addClass('dropup');
        }
    });

    toggleFold('#productStoryForm', unfoldStories, productID, 'product');
});
