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
        $("input[name='begin[" + planID + "]']").val('2030-01-01').removeClass('form-input-show').addClass('form-input-hidden');
        $("input[name='end[" + planID + "]']").val('2030-01-01').removeClass('form-input-show').addClass('form-input-hidden');
        $("input[name='begin" + planID + "']").val('').removeClass('form-input-hidden').addClass('form-input-show');
        $("input[name='end" + planID + "']").val('').removeClass('form-input-hidden').addClass('form-input-show');
    }
    else
    {
        $("input[name='begin[" + planID + "]']").val('').removeClass('form-input-hidden').addClass('form-input-show');
        $("input[name='end[" + planID + "]']").val('').removeClass('form-input-hidden').addClass('form-input-show');
        $("input[name='begin" + planID + "']").removeClass('form-input-show').addClass('form-input-hidden');
        $("input[name='end" + planID + "']").removeClass('form-input-show').addClass('form-input-hidden');

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
        $("input[name='begin[" + planID + "]']").closest('td').addClass('required');
        $("input[name='end[" + planID + "]']").closest('td').addClass('required');
        $("input[name='begin" + planID + "']").removeAttr('disabled');
        $("input[name='end" + planID + "']").removeAttr('disabled');
    }
    else
    {
        $('#future' + planID).closest('div').removeClass('hidden');
        $("input[name='begin[" + planID + "]']").closest('td').removeClass('required');
        $("input[name='end[" + planID + "]']").closest('td').removeClass('required');
        $("input[name='begin" + planID + "']").attr('disabled', 'disabled').val('');
        $("input[name='end" + planID + "']").attr('disabled', 'disabled').val('');
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
