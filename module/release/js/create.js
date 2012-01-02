 function loadStoriesAndBugs(buildID,productID)
{
    link = createLink('release', 'ajaxGetStoriesAndBugs', 'buildID=' + buildID + '&productID=' + productID);
    $('#linkStoriesAndBugs').load(link);
}
