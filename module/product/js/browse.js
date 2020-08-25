$(function()
{
    $('#subNavbar .nav li').removeClass('active');
    $("#subNavbar .nav li[data-id=" + storyType + ']').addClass('active');

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

    if($('#productStoryForm td.has-child').length > 0)
    {
        $('#productStoryForm th.c-title').append("<button type='button' id='toggleFold' class='btn btn-mini collapsed'>" + unfoldAll + "</button>");
        var allUnfold = true;
        $('#productStoryForm td.has-child').each(function()
        {
            var storyID = $(this).closest('tr').attr('data-id');
            if(typeof(unfoldID[storyID]) == 'undefined')
            {
                allUnfold = false;
                $('#productStoryForm tr.parent-' + storyID).hide();
                $(this).find('a.story-toggle').addClass('collapsed')
            }

        })

        if(allUnfold)
        {
            $('#productStoryForm th.c-title #toggleFold').html(foldAll).removeClass('collapsed');
        }
        else
        {
            $('#productStoryForm th.c-title #toggleFold').html(unfoldAll).addClass('collapsed');
        }

        $(document).on('click', '#toggleFold', function()
        {
            var newUnfoldID = [];
            var url         = '';
            if($(this).hasClass('collapsed'))
            {
                $('#productStoryForm td.has-child').each(function()
                {
                    var storyID = $(this).closest('tr').attr('data-id');
                    $('#productStoryForm tr.parent-' + storyID).show();
                    $(this).find('a.story-toggle').removeClass('collapsed')
                    newUnfoldID.push(storyID);
                })
                $(this).html(foldAll).removeClass('collapsed');
                url = createLink('product', 'ajaxSetUnfoldID', 'productID=' + productID);
            }
            else
            {
                $('#productStoryForm td.has-child').each(function()
                {
                    var storyID = $(this).closest('tr').attr('data-id');
                    $('#productStoryForm tr.parent-' + storyID).hide();
                    $(this).find('a.story-toggle').addClass('collapsed');
                    newUnfoldID.push(storyID);
                })
                $(this).html(unfoldAll).addClass('collapsed');
                url = createLink('product', 'ajaxSetUnfoldID', 'productID=' + productID + '&action=delete');
            }
            $.post(url, {'newUnfoldID': JSON.stringify(newUnfoldID)});
        });

        $('#productStoryForm td.has-child a.story-toggle').click(function()
        {
            var newUnfoldID = [];
            var url         = '';
            if($(this).hasClass('collapsed'))
            {
                var storyID = $(this).closest('tr').attr('data-id');
                $('#productStoryForm tr.parent-' + storyID).show();
                newUnfoldID.push(storyID);
                url = createLink('product', 'ajaxSetUnfoldID', 'productID=' + productID);
            }
            else
            {
                var storyID = $(this).closest('tr').attr('data-id');
                $('#productStoryForm tr.parent-' + storyID).hide();
                newUnfoldID.push(storyID);
                url = createLink('product', 'ajaxSetUnfoldID', 'productID=' + productID + '&action=delete');
            }

            setTimeout(function()
            {
                if($('#productStoryForm td.has-child a.story-toggle.collapsed').length == 0)
                {
                    $('#toggleFold').html(foldAll).removeClass('collapsed');
                }
                else
                {
                    $('#toggleFold').html(unfoldAll).addClass('collapsed');
                }
            }, 100);

            $.post(url, {'newUnfoldID': JSON.stringify(newUnfoldID)});
        });
    }
});
