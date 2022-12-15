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

$('#dataform').on('change', '#branch', function()
{
    var newBranch = $('#branch').val() ? $('#branch').val().toString() : '';
    $.get(createLink('productplan', 'ajaxGetConflict', 'planID=' + planID + '&newBranch=' + newBranch), function(conflictStories)
    {
        if(conflictStories != '')
        {
            var result = confirm(conflictStories) ? true : false;
            if(!result)
            {
                $('#branch').val(oldBranch[planID].split(','));
                $('#branch').trigger("chosen:updated");
            }
        }
    });
});

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

$('#parent').change(function()
{
    var parentID        = $(this).val();
    var currentBranches = $('#branch').val() ? $('#branch').val().toString() : '';
    $.post(createLink('productplan', 'ajaxGetParentBranches', "productID=" + productID + "&parentID=" + parentID + "&currentBranches=" + currentBranches), function(data)
    {
        $('#branch').replaceWith(data);
        $('#branch_chosen').remove();
        $('#branch').chosen();
    })
})

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

$('#submit').click(function()
{
    var parentPlan = $('#parent').val();
    var branches   = $('#branch').val();
    if(parentPlan > 0 && branches)
    {
        link = createLink('productplan', 'ajaxGetDiffBranchesTip', "produtID=" + productID + "&parentID=" + parentPlan + "&branches=" + branches.toString());
        $.post(link, function(diffBranchesTip)
        {
            if((diffBranchesTip != '' && confirm(diffBranchesTip)) || !diffBranchesTip) $('form#dataform').submit();
        });
        return false;
    }
});
