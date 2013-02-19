function loadStoriesAndBugs(buildID,productID)
{
    link = createLink('release', 'ajaxGetStoriesAndBugs', 'buildID=' + buildID + '&productID=' + productID);
    $('#linkStoriesAndBugs').load(link, function()
    {
        $("a.preview").colorbox({width:960, height:550, iframe:true, transition:'none', scrolling:true});
    })
}

$(document).ready(function()
{
    $("a.preview").colorbox({width:960, height:550, iframe:true, transition:'none', scrolling:true});
})
