/**
 * Handle the product plan pending status. Fix bug #2937.
 *
 * @param  int planID
 * @access public
 * @return date
 */
function changeDate(planID)
{
    if($("#future" + planID).prop('checked'))
    {
        $("input[name='begin[" + planID + "]']").attr('disabled', 'disabled');
        $("input[name='end[" + planID + "]']").attr('disabled', 'disabled');
    }
    else
    {
        $("input[name='begin[" + planID + "]']").removeAttr('disabled', 'disabled');
        $("input[name='end[" + planID + "]']").removeAttr('disabled', 'disabled');
        $('.form-date').datetimepicker('update');
    }
};

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
