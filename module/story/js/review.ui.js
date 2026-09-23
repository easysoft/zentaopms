window.switchShow = function(event)
{
    var result = $(event.target).val();

    $('#priBox').closest('.form-row').addClass('hidden');
    $('#estimateBox').closest('.form-row').addClass('hidden');
    if(result == 'reject')
    {
        $('#rejectedReasonBox').closest('.form-row').removeClass('hidden');
        $('#assignedToBox').closest('.form-row').addClass('hidden');
    }
    else if(result == 'revert')
    {
        $('#rejectedReasonBox').closest('.form-row').addClass('hidden');
        $('#duplicateStoryBox').closest('.form-row').addClass('hidden');
        if(isLastOne) $('#assignedToBox').closest('.form-row').removeClass('hidden');
    }
    else if(result == 'clarify')
    {
        $('#rejectedReasonBox').closest('.form-row').addClass('hidden');
        $('#duplicateStoryBox').closest('.form-row').addClass('hidden');
        $('#rejectedReasonBox').closest('.form-row').addClass('hidden');
        if(isLastOne) $('#assignedToBox').closest('.form-row').removeClass('hidden');
    }
    else
    {
        $('#rejectedReasonBox').closest('.form-row').addClass('hidden');
        $('#duplicateStoryBox').closest('.form-row').addClass('hidden');
        $('#rejectedReasonBox').closest('.form-row').addClass('hidden');
        if(isLastOne) $('#assignedToBox').closest('.form-row').removeClass('hidden');
        if(result == 'pass')
        {
            $('#priBox').closest('.form-row').removeClass('hidden');
            $('#estimateBox').closest('.form-row').removeClass('hidden');
        }
    }
}

window.setStory = function(event)
{
    var reason = $(event.target).val();
    $('#duplicateStoryBox').closest('.form-row').toggleClass('hidden', reason != 'duplicate');
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
