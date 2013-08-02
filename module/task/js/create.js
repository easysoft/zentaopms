/* Copy story title as task title. */
function copyStoryTitle()
{
    var storyTitle = $('#story option:selected').text();
    startPosition = storyTitle.indexOf(':') + 1;
    endPosition   = storyTitle.lastIndexOf('(');
    storyTitle = storyTitle.substr(startPosition, endPosition - startPosition);
    $('#name').attr('value', storyTitle);
}

/* Set the assignedTos field. */
function setOwners(result)
{
    if(result == 'affair')
    {
        $('#assignedTo').attr('size', 4);
        $('#assignedTo').attr('multiple', 'multiple');
    }
    else
    {
        $('#assignedTo').removeAttr('size');
        $('#assignedTo').removeAttr('multiple');
    }
}

/* Set the story priview link. */
function setPreview()
{
    if(!$('#story').val())
    {
        $('#preview').addClass('hidden');
        $('#copyButton').addClass('hidden');
    }
    else
    {
        storyLink  = createLink('story', 'view', "storyID=" + $('#story').val());
        var concat = config.requestType == 'PATH_INFO' ? '?'  : '&';
        storyLink  = storyLink + concat + 'onlybody=yes';
        $('#preview').removeClass('hidden');
        $('#preview').attr('href', storyLink);
        $('#copyButton').removeClass('hidden');
    }

    $("#preview").colorbox({width:960, height:550, iframe:true, transition:'none', scrolling:true});

    setAfter();
}

/**
 * Set after locate. 
 * 
 * @access public
 * @return void
 */
function setAfter()
{
    if($("#story").length == 0 || $("#story").select().val() == '') 
    {
        if($('input[value="continueAdding"]').attr('checked') == 'checked') 
        {
            $('input[value="toTaskList"]').attr('checked', 'checked');
        }
        $('input[value="continueAdding"]').attr('disabled', 'disabled');
    }
    else
    {
        $('input[value="continueAdding"]').attr('checked', 'checked');
        $('input[value="continueAdding"]').attr('disabled', false);
    }
}

/**
 * load stories of module.
 * 
 * @access public
 * @return void
 */
function loadModuleRelated()
{
    moduleID  = $('#module').val();
    projectID = $('#project').val();
    setStories(moduleID, projectID);
}

/* Get select of stories.*/
function setStories(moduleID, projectID)
{
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=0&moduleID=' + moduleID);
    $.get(link, function(stories)
    {
        if(!stories) stories = '<select id="story" name="story"></select>';
        $('#story').replaceWith(stories);
        $('#story_chzn').remove();
        $("#story").chosen({no_results_text: ''});
    });
}

$(document).ready(function()
{
    setPreview();
    $("#story").chosen({no_results_text: noResultsMatch});
    $("#mailto").chosen({no_results_text: noResultsMatch});
});
