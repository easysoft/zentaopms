function switchShow(result)
{
    $('#priBox').hide();
    $('#estimateBox').hide();
    if(result == 'reject')
    {
        $('#rejectedReasonBox').show();
        $('#preVersionBox').hide();
        $('#assignedToBox').hide();
        if(isMultiple)
        {
            $('#assignedToBox').show();
            loadAssignedTo();
        }
    }
    else if(result == 'revert')
    {
        $('#preVersionBox').show();
        $('#rejectedReasonBox').hide();
        $('#duplicateStoryBox').hide();
        $('#childStoriesBox').hide();
        $('#assignedToBox').show();
        loadAssignedTo();
    }
    else if(result == 'clarify')
    {
        $('#preVersionBox').hide();
        $('#rejectedReasonBox').hide();
        $('#duplicateStoryBox').hide();
        $('#childStoriesBox').hide();
        $('#rejectedReasonBox').hide();
        $('#assignedToBox').show();
        loadAssignedTo();
    }
    else
    {
        $('#preVersionBox').hide();
        $('#rejectedReasonBox').hide();
        $('#duplicateStoryBox').hide();
        $('#childStoriesBox').hide();
        $('#rejectedReasonBox').hide();
        $('#assignedToBox').hide();
        if(result == 'pass')
        {
            $('#priBox').show();
            $('#estimateBox').show();
            $('#assignedToBox').show();
            loadAssignedTo();
        }
    }

    getStatus('review', "storyID=" + storyID + ",result=" + result);
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

/**
 * Load assignedTo.
 *
 * @access public
 * @return void
 */
function loadAssignedTo()
{
    var link = createLink('story', 'ajaxGetAssignedTo', 'type=review&storyID=' + storyID);
    $.post(link, function(data)
    {
        $('#assignedTo').replaceWith(data);
        $('#assignedToBox .picker').remove();
        $('#assignedTo').picker();
    });
}

$(function()
{
    if($('.tabs .tab-content .tab-pane.active').children().length == 0) $('.tabs .nav-tabs li.active').css('border-bottom', '1px solid #ccc');
})
