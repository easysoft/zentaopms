$(function()
{
    $('#needNotReview').on('change', function()
    {
        $('#reviewer').attr('disabled', $(this).is(':checked') ? 'disabled' : null).trigger('chosen:updated');
        if($(this).is(':checked'))
        {
            $('#reviewerBox').removeClass('required');
        }
        else
        {
            $('#reviewerBox').addClass('required');
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
            $('#reviewerBox').attr('colspan', 2);
        }
        else
        {
            $('#feedbackBox').addClass('hidden');
            $('#reviewerBox').attr('colspan', 4);
        }
    });
});

function refreshPlan()
{
    $('a.refresh').click();
}

function loadLanes(regionID)
{
    var link = createLink('story', 'ajaxGetLanesByRegionID', 'regionID=' + regionID);
    $.post(link, function(data)
    {
        console.log(data);
        $('#lane').replaceWith(data);
        $('#lane_chosen').remove();
        $('#lane').chosen();
    });
}

$(window).unload(function(){
    if(blockID) window.parent.refreshBlock($('#block' + blockID));
});
