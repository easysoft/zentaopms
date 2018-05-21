$(function()
{
    $('.popoverStage').mouseover(function(){$(this).popover('show')});
    $('.popoverStage').mouseout(function(){$(this).popover('hide')});

    if($('#storyList thead th.w-title').width() < 150) $('#storyList thead th.w-title').width(150);
});

function setQueryBar(queryID, title)
{
    $('#bysearchTab').before("<a id='QUERY" + queryID + "Tab' class='btn btn-link btn-active-text' href='" + createLink('product', 'browse', "productID=" + productID + "&branch=" + branch + "&browseType=bysearch&param=" + queryID) + "'><span class='text'>" + title + "</span></a>");
}
