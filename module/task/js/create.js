/* Copy story title as task title. */
function copyStoryTitle()
{
    var storyTitle = $('#story option:selected').text();
    storyTitle = storyTitle.substr(storyTitle.lastIndexOf(':')+ 1);
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
        storyLink = createLink('story', 'view', "storyID=" + $('#story').val());
        $('#preview').removeClass('hidden');
        $('#preview').attr('href', storyLink);
    }
}
$(document).ready(function()
{
    setPreview();
    $("#mailto").autocomplete(userList, { multiple: true, mustMatch: true});
});
