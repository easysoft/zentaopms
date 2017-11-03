$(function()
{
    if(browseType == 'bysearch') ajaxGetSearchForm();

    $('.dropdown-menu.with-search .menu-search').click(function(e)
    {
        e.stopPropagation();
        return false;
    }).on('keyup change paste', 'input', function()
    {
        var $input = $(this);
        var val = $input.val().toLowerCase();
        var $options = $input.closest('.dropdown-menu.with-search').find('.option');
        if(val == '') return $options.removeClass('hide');
        $options.each(function()
        {
            var $option = $(this);
            $option.toggleClass('hide', $option.text().toString().toLowerCase().indexOf(val) < 0 && $option.data('key').toString().toLowerCase().indexOf(val) < 0);
        });
    });

    $('.popoverStage').mouseover(function(){$(this).popover('show')});
    $('.popoverStage').mouseout(function(){$(this).popover('hide')});
    setTimeout(function(){fixedTfootAction('#productStoryForm')}, 100);
    setTimeout(function(){fixedTheadOfList('#storyList')}, 100);

    if($('#storyList thead th.w-title').width() < 150) $('#storyList thead th.w-title').width(150);
})

function setQueryBar(queryID, title)
{
    $('#bysearchTab').before("<li id='QUERY" + queryID + "Tab' class='active'><a href='" + createLink('product', 'browse', "productID=" + productID + "&branch=" + branch + "&browseType=bysearch&param=" + queryID) + "'>" + title + "</a></li>");
}
