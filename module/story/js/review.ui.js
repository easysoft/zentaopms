window.switchShow = function(obj)
{
    var result = $(obj).val();

    $('#priBox').addClass('hidden');
    $('#estimateBox').addClass('hidden');
    if(result == 'reject')
    {
        $('#rejectedReasonBox').removeClass('hidden');
        $('#assignedToBox').addClass('hidden');
    }
    else if(result == 'revert')
    {
        $('#rejectedReasonBox').addClass('hidden');
        $('#duplicateStoryBox').addClass('hidden');
        if(isLastOne) $('#assignedToBox').removeClass('hidden');
    }
    else if(result == 'clarify')
    {
        $('#rejectedReasonBox').addClass('hidden');
        $('#duplicateStoryBox').addClass('hidden');
        $('#rejectedReasonBox').addClass('hidden');
        if(isLastOne) $('#assignedToBox').removeClass('hidden');
    }
    else
    {
        $('#rejectedReasonBox').addClass('hidden');
        $('#duplicateStoryBox').addClass('hidden');
        $('#rejectedReasonBox').addClass('hidden');
        if(isLastOne) $('#assignedToBox').removeClass('hidden');
        if(result == 'pass')
        {
            $('#priBox').removeClass('hidden');
            $('#estimateBox').removeClass('hidden');
        }
    }
}

window.setStory = function(obj)
{
    var reason = $(obj).val();
    $('#duplicateStoryBox').toggleClass('hidden', reason != 'duplicate');
}

window.renderChildCell = function(result, info)
{
    if(info.col.name == 'title' && result)
    {
        let html       = '';
        const story    = info.row.data;
        const gradeMap = gradeGroup[story.type] || {};
        let gradeLabel = gradeMap[story.grade];

        if(gradeLabel) html += "<span class='label gray-pale rounded-xl clip'>" + gradeLabel + "</span> ";
        if(html) result.unshift({html});
    }
    return result;
}
