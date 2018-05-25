$(function()
{
    if($('#bugList thead th.w-title').width() < 150) $('#bugList thead th.w-title').width(150);

    if(flow == 'onlyTest')
    {
        toggleSearch();
        $(".export").modalTrigger({width:650, type:'iframe'});

        $('#subNavbar > .nav > li').removeClass('active');
        $('#subNavbar > .nav > li[data-id=' + browseType + ']').addClass('active');

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

function setQueryBar(queryID, title)
{
    $('#bysearchTab').before("<li id='QUERY" + queryID + "Tab' class='active'><a href='" + createLink('bug', 'browse', "productID=" + productID + "&branch=" + branch + "&browseType=bysearch&param=" + queryID) + "'>" + title + "</a></li>");
}
