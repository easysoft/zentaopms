function loadStoriesAndBugs(buildID,productID)
{
    link = createLink('release', 'ajaxGetStoriesAndBugs', 'buildID=' + buildID + '&productID=' + productID);
    $('#linkStoriesAndBugs').load(link, function()
    {
        $("a.preview").modalTrigger({width:1000, type:'iframe'});
    })
}

$(document).ready(function()
{
    $("a.preview").modalTrigger({width:1000, type:'iframe'});
})
