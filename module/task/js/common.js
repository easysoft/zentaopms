/* Set the story module. */
function setStoryModule()
{
    var storyID = $('#story').val();
    if(storyID)
    {
        var link = createLink('story', 'ajaxGetInfo', 'storyID=' + storyID);
        $.getJSON(link, function(storyInfo)
        {
            if(storyInfo)
            {
                $('#module').val(storyInfo.moduleID);
                $("#module").trigger("chosen:updated");

                $('#storyEstimate').val(storyInfo.estimate);
                $('#storyPri').val(storyInfo.pri);
                $('#storyDesc').val(storyInfo.spec);
            }
        });
    }
}
