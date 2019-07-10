$(function()
{
    if($('#bugList thead th.c-title').width() < 150) $('#bugList thead th.c-title').width(150);

    if(flow == 'onlyTest')
    {
        $('#subNavbar > .nav li[data-id=' + browseType + ']').addClass('active');
        var $more = $('#subNavbar > .nav > li[data-id=more]');
        if($more.find('.dropdown-menu').children().length)
        {
            $more.find('.dropdown-menu').children().each(function()
            {
                if($(this).hasClass('active')) $more.addClass('active');
            });
        }

        var navWidth = $('#subNavbar > .nav').width();
        var leftWidth  = 0;
        var rightWidth = 0;

        $rightNav = $('#subNavbar > .nav > li.right');
        rightLength = $rightNav.length;
        for(i = 0; i < rightLength; i++) rightWidth += $rightNav.eq(i).width();

        var maxWidth = navWidth - $('#subNavbar > .nav > #bysearchTab').width() - rightWidth - 100;

        $('#subNavbar > .nav > li:not(.right)').each(function()
        {
            if(leftWidth > maxWidth)
            {
                if($(this).attr('id') != 'moreMenus' && $(this).attr('id') != 'bysearchTab')
                {
                    $('#subNavbar').removeClass('hidden');
                    $('#subNavbar > ul').append($(this)[0]);
                }
            }
            else
            {
                leftWidth += $(this).width();
            }
        })
    }
});
