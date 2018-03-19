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
    delta     = parseInt(delta);

    if(delta == 9999)
    {
        $('#begin').attr("disabled", "disabled");
        $('#end').val('').attr("disabled","disabled");
    }
    else
    {
        if(beginDate)
        {
            beginDate = convertStringToDate(beginDate);
            if((delta == 7 || delta == 14) && (beginDate.getDay() == 1))
            {
                delta = (weekend == 2) ? (delta - 2) : (delta - 1);
            }

            currentBeginDate = beginDate.toString('yyyy-MM-dd');
            endDate = beginDate.addDays(delta - 1).toString('yyyy-MM-dd');
        }
        else
        {
            currentBeginDate = '';
            endDate = '';
        }

        $('#begin').val(currentBeginDate).removeAttr('disabled')
        $('#end').val(endDate).removeAttr('disabled').datetimepicker('update');
    }
}
