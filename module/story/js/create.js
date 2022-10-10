$(function()
{
    $('#needNotReview').on('change', function()
    {
        $('#reviewer').attr('disabled', $(this).is(':checked') ? 'disabled' : null).trigger('chosen:updated');

        if($(this).is(':checked'))
        {
            $('#reviewerBox').closest('tr').addClass('hidden');
            $('#reviewerBox').removeClass('required');
            $('#dataform #needNotReview').val(1);
        }
        else
        {
            $('#reviewerBox').closest('tr').removeClass('hidden');
            $('#reviewerBox').addClass('required');
            $('#dataform #needNotReview').val(0);
        }

        getStatus('create', "product=" + $('#product').val() + ",execution=" + executionID + ",needNotReview=" + ($(this).prop('checked') ? 1 : 0));
    });
    $('#needNotReview').change();

    // init pri selector
    $('#pri').on('change', function()
    {
        var $select = $(this);
        var $selector = $select.closest('.pri-selector');
        var value = $select.val();
        $selector.find('.pri-text').html('<span class="label-pri label-pri-' + value + '" title="' + value + '">' + value + '</span>');
    });

    $('#source').on('change', function()
    {
        if(storyType == 'requirement') return false;

        var source = $(this).val();
        if($.inArray(source, feedbackSource) != -1)
        {
            $('#feedbackBox').removeClass('hidden');
            $('#source, #sourceNoteBox').closest('td').attr('colspan', 1);
        }
        else
        {
            $('#feedbackBox').addClass('hidden');
            $('#source, #sourceNoteBox').closest('td').attr('colspan', 2);
        }
    });

    $('#customField').click(function()
    {
        hiddenRequireFields();
    });

    /* Implement a custom form without feeling refresh. */
    $('#formSettingForm .btn-primary').click(function()
    {
        saveCustomFields('createFields');
        return false;
    });

    $(document).on('change', '#module', loadURS);
});

/**
 * Load assignedTo.
 *
 * @access public
 * @return void
 */
function loadAssignedTo()
{
    var assignees = $('#reviewer').val();
    var link      = createLink('story', 'ajaxGetAssignedTo', 'type=create&storyID=0&assignees=' + assignees);
    $.post(link, function(data)
    {
        $('#assignedTo').replaceWith(data);
        $('#assignedToBox .picker').remove();
        $('#assignedTo').picker();
    });
}

function refreshPlan()
{
    loadProductPlans($('#product').val(), $('#branch').val());
}

/**
 * Set lane.
 *
 * @param  int $regionID
 * @access public
 * @return void
 */
function setLane(regionID)
{
    laneLink = createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=story&field=lane');
    $.get(laneLink, function(lane)
    {
        if(!lane) lane = "<select id='lane' name='lane' class='form-control'></select>";
        $('#lane').replaceWith(lane);
        $('#lane' + "_chosen").remove();
        $('#lane').next('.picker').remove();
        $('#lane').chosen();
    });
}

$(window).unload(function(){
    if(blockID) window.parent.refreshBlock($('#block' + blockID));
});
