function switchShow(result)
{
    if(result == 'reject')
    {
        $('#rejectedReasonBox').show();
        $('#preVersionBox').hide();
        $('#priBox').hide();
        $('#estimateBox').hide();
        $('#assignedTo').val('closed');
        $('#assignedTo').trigger("chosen:updated");
    }
    else if(result == 'clarify')
    {
        $('#priBox').hide();
        $('#estimateBox').hide();
        $('#preVersionBox').show();
        $('#rejectedReasonBox').hide();
        $('#duplicateStoryBox').hide();
        $('#childStoriesBox').hide();
        $('#assignedTo').val(assignedTo);
        $('#assignedTo').trigger("chosen:updated");
    }
    else
    {
        $('#priBox').show();
        $('#estimateBox').show();
        $('#preVersionBox').hide();
        $('#rejectedReasonBox').hide();
        $('#duplicateStoryBox').hide();
        $('#childStoriesBox').hide();
        $('#rejectedReasonBox').hide();
        $('#assignedTo').val(assignedTo);
        $('#assignedTo').trigger("chosen:updated");
    }
}

function setStory(reason)
{
    if(reason == 'duplicate')
    {
        $('#duplicateStoryBox').show();
        $('#childStoriesBox').hide();
    }
    else if(reason == 'subdivided')
    {
        $('#duplicateStoryBox').hide();
        $('#childStoriesBox').show();
    }
    else
    {
        $('#duplicateStoryBox').hide();
        $('#childStoriesBox').hide();
    }
}
