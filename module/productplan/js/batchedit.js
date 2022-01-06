/**
 * Handle the product plan pending status. Fix bug #2937.
 *
 * @param  int planID
 * @access public
 * @return date
 */
function changeDate(planID)
{
    if($("#future"+planID).prop('checked'))
    {
        $("input[name='begin[" + planID + "]']").attr('disabled', 'disabled').val('');
        $("input[name='end[" + planID + "]']").attr('disabled', 'disabled').val('');
    }
    else
    {
        $("input[name='begin[" + planID + "]']").removeAttr('disabled', 'disabled');
        $("input[name='end[" + planID + "]']").removeAttr('disabled', 'disabled');
        $('.form-date').datetimepicker('update');
    }
};

/**
 * Set plan status.
 *
 * @param int $planID
 * @param $status
 * @access public
 * @return void
 */
function setPlanStatus(planID, status)
{
    if(status != 'wait')
    {
        $('#future' + planID).closest('div').addClass('hidden');
        $("input[name='begin[" + planID + "]']").removeAttr('disabled');
        $("input[name='end[" + planID + "]']").removeAttr('disabled');
        $('.form-date').datetimepicker('update');
    }
    else
    {
        var isFuture = $('#future' + planID).prop('checked');

        $('#future' + planID).closest('div').removeClass('hidden');

        if(isFuture)
        {
            $("input[name='begin[" + planID + "]']").attr('disabled', 'disabled');
            $("input[name='end[" + planID + "]']").attr('disabled', 'disabled');
        }
    }
}


/**
 * Get conflict stories.
 *
 * @param  int    $planID
 * @param  int    $branch
 * @access public
 * @return void
 */
function getConflictStories(planID, branch)
{
    $.get(createLink('productplan', 'ajaxGetConflictStory', 'planID=' + planID + '&newBranch=' + branch), function(conflictStories)
    {
        if(conflictStories != '' && !confirm(conflictStories))
        {
            $('#branch' + planID).val(oldBranch[planID]);
            $('#branch' + planID).trigger("chosen:updated");
        }
    });
}
