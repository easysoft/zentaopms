$(function()
{
    $('#needNotReview').on('change', function()
    {
        $('#reviewer').text('').attr('disabled', $(this).is(':checked') ? 'disabled' : null).trigger('chosen:updated');
        if($(this).is(':checked'))
        {
            $('.input-group-addon').removeClass('required');
        }
        else
        {
            $('.input-group-addon').addClass('required');
        }
    });

    if($('.tabs .tab-content .tab-pane.active').children().length == 0) $('.tabs .nav-tabs li.active').css('border-bottom', '1px solid #ccc');
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
    var link      = createLink('story', 'ajaxGetAssignedTo', 'type=change&storyID=' + storyID + '&assignees=' + assignees);
    $.post(link, function(data)
    {
        $('#assignedTo').replaceWith(data);
        $('#assignedToBox .picker').remove();
        $('#assignedTo').picker();
    });
}
