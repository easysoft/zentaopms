$(function() {
    for(i=0; i<batchCreateNum; i++) $("#story" + i).chosen({no_results_text: noResultsMatch});
})

/* Get select of stories.*/
function setStories(moduleID, projectID, num)
{
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=0&moduleID=' + moduleID + '&storyID=0&num=' + num + '&type=short');
    $.get(link, function(stories)
    {
        var storyID = $('#story' + num).val();
        if(!stories) stories = '<select id="story' + num + '" name="story' + num + '" class="select-1"></select>';
        $('#story' + num).replaceWith(stories);
        $('#story' + num).val(storyID);
        $('#story' + num + '_chzn').remove();
        $("#story" + num).chosen({no_results_text: ''});
    });
}

/* Copy story title as task title. */
function copyStoryTitle(num)
{
    var storyTitle = $('#story' + num).find('option:selected').text();
    startPosition  = storyTitle.indexOf(':') + 1;
    endPosition    = storyTitle.lastIndexOf('[');
    storyTitle     = storyTitle.substr(startPosition, endPosition - startPosition);
    $('#story' + num).parent().next().find('input:first').val(storyTitle);
}

/* Set the story module. */
function setStoryRelated(num)
{
    var storyID = $('#story' + num).val();
    if(storyID)
    {
        var link = createLink('story', 'ajaxGetModule', 'storyID=' + storyID);
        $.get(link, function(moduleID)
        {
            $('#module' + num).val(moduleID);
        });
    }
}
