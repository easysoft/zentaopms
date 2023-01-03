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
 * @access public
 * @return void
 */
function getConflictStories(planID)
{
    var newBranch = $('#branch' + planID).val() ? $('#branch' + planID).val().toString() : '';
    $.get(createLink('productplan', 'ajaxGetConflict', 'planID=' + planID + '&newBranch=' + newBranch), function(conflictStories)
    {
        if(conflictStories != '' && !confirm(conflictStories))
        {
            $('#branch' + planID).val(oldBranch[planID].split(','));
            $('#branch' + planID).trigger("chosen:updated");
        }
    });
}
