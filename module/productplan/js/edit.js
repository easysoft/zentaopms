/**
 * Convert a date string like 2011-11-11 to date object in js.
 *
 * @param  string $date
 * @access public
 * @return date
 */
function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    dateString = dateString[1] + '/' + dateString[2] + '/' + dateString[0];

    return Date.parse(dateString);
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
            $('#branch').val(oldBranch[planID]);
            $('#branch').trigger("chosen:updated");
        }
    });
}

/**
 * Compute the end date for productplan.
 *
 * @param  int    $delta
 * @access public
 * @return void
 */
function computeEndDate(delta)
{
    beginDate = $('#begin').val();
    if(!beginDate) return;

    delta     = parseInt(delta);
    beginDate = convertStringToDate(beginDate);
    if((delta == 7 || delta == 14) && (beginDate.getDay() == 1))
    {
        delta = (weekend == 2) ? (delta - 2) : (delta - 1);
    }

    currentBeginDate = $.zui.formatDate(beginDate, 'yyyy-MM-dd');
    endDate = $.zui.formatDate(beginDate.addDays(delta - 1), 'yyyy-MM-dd');

    $('#begin').val(currentBeginDate);
    $('#end').val(endDate).datetimepicker('update');
}

/**
 * Set plan status.
 *
 * @access public
 * @return void
 */
function setPlanStatus()
{
    var status = $('#status').val();
    if(status != 'wait')
    {
        $('#checkBox').closest('div').addClass('hidden');
        $('#future').val(0);
        $('#begin').removeAttr('disabled');
        $('#end').parents('tr').show();
    }
    else
    {
        var isFuture = $('#future').prop('checked');

        $('#checkBox').closest('div').removeClass('hidden');
        if(isFuture)
        {
            $('#begin').attr('disabled', 'disabled');
            $('#end').parents('tr').hide();
        }
    }
}

$('#future').on('change', function()
{
    if($(this).prop('checked'))
    {
        $('#begin').attr('disabled', 'disabled');
        $('#end').parents('tr').hide();
    }
    else
    {
        var begin = $('#begin').val();
        if(begin == '') $('#begin').val(today);

        $('#begin').removeAttr('disabled');
        $('#end').parents('tr').show();
    }
});

$('#future').change();
