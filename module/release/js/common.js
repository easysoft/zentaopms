function loadStoriesAndBugs(buildID,productID)
{
    link = createLink('release', 'ajaxGetStoriesAndBugs', 'buildID=' + buildID + '&productID=' + productID);
    $('#linkStoriesAndBugs').load(link, function()
    {
        $("a.preview").colorbox({width:1000, height:600, iframe:true, transition:'elastic', speed:350, scrolling:true});
        setOuterBox();
    })
}

$(document).ready(function()
{
    $("a.preview").colorbox({width:1000, height:600, iframe:true, transition:'elastic', speed:350, scrolling:true});
})
