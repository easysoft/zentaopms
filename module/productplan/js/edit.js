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
        if(conflictStories != '')
        {
            var result = confirm(conflictStories) ? true : false;
            if(!result)
            {
                $('#branch').val(oldBranch[planID]);
                $('#branch').trigger("chosen:updated");
            }
        }

        if(conflictStories == '' || result)
        {
            var link = createLink('productplan', 'ajaxGetTopPlan', "productID=" + productID + "&branch=" + branch);
            $.post(link, function(data)
            {
                $('#parent').replaceWith(data);
                $('#parent_chosen').remove();
                $('#parent').chosen();
            })
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

$('#future').on('change', function()
{
    if($(this).prop('checked'))
    {
        $('#begin').attr('disabled', 'disabled');
        $('#end').parents('tr').hide();
    }
    else
    {
        $('#begin').removeAttr('disabled');
        $('#end').parents('tr').show();
    }
});


$('#future').change();
