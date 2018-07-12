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

    currentBeginDate = beginDate.toString('yyyy-MM-dd');
    endDate = beginDate.addDays(delta - 1).toString('yyyy-MM-dd');

    $('#begin').val(currentBeginDate);
    $('#end').val(endDate);
}

$('#begin').on('change', function()
{
    $("#end").val('');
    $("input:radio[name='delta']").attr("checked",false);
});

$('#end').on('change', function()
{
    $("input:radio[name='delta']").attr("checked", false);
});

$('#future').on('change', function()
{
    if($(this).prop('checked'))
    {
        $('#begin').val('').attr('disabled', 'disabled');
        $('#end').val('').parents('tr').hide();
    }
    else
    {
        $('#begin').removeAttr('disabled');
        $('#end').val('').parents('tr').show();
    }
});
