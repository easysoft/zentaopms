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
    }
    else
    {
        storyLink  = createLink('story', 'view', "storyID=" + $('#story').val());
        var concat = config.requestType == 'PATH_INFO' ? '?'  : '&';
        storyLink  = storyLink + concat + 'onlybody=yes';
        $('#preview').removeClass('hidden');
        $('#preview').attr('href', storyLink);
    }
}
$(document).ready(function()
{
    setPreview();
    $("#story").chosen({no_results_text: noResultsMatch});
    $("#mailto").autocomplete(userList, { multiple: true, mustMatch: true});
});
