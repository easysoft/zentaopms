function setWhite(acl)
{
    acl == 'custom' ? $('#whitelistBox').removeClass('hidden') : $('#whitelistBox').addClass('hidden');
}

function switchGroup(projectID, groupBy)
{
    link = createLink('project', 'groupTask', 'project=' + projectID + '&groupBy=' + groupBy);
    location.href=link;
}

/**
 * Convert a date string like 2011-11-11 to date object in js.
 * 
 * @param  string $date 
 * @access public
 * @return date
 */
function convertStringToDate(dateString)
{
    var myDate = dateString.split('-')
    return new Date(myDate[0], myDate[1], myDate[2]);
}

/**
 * Compute delta of two days.
 * 
 * @param  string $date1 
 * @param  string $date1 
 * @access public
 * @return int
 */
function computeDaysDelta(date1, date2)
{
    date1 = convertStringToDate(date1);
    date2 = convertStringToDate(date2);
    return (date2 - date1) / (1000 * 60 * 60 * 24) + 1
}

/**
 * Compute work days.
 * 
 * @access public
 * @return void
 */
function computeWorkDays()
{
    beginDate = $('#begin').val();
    endDate   = $('#end').val();
    if(beginDate && endDate) $('#days').val(computeDaysDelta(beginDate, endDate));
}

/* Auto compute the work days. */
$(function() 
{
    $(".date").bind('dateSelected', function()
    {
        computeWorkDays();
    })
});

