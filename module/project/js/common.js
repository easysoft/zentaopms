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
    if(beginDate && endDate) 
    {
      $('#days').val(computeDaysDelta(beginDate, endDate));
    }
    else if($('input[checked="true"]').val()) 
    {
      computeEndDate();
    }
}

function computeEndDate()
{
  beginDate = $('#begin').val();
  workDays  = $('input:checked').val();
  beginDate = convertStringToDate(beginDate);
  if(workDays == '2weeks')
  {
    begin   = beginDate.valueOf();
    endDate = begin + 14 * 24 * 60 * 60 * 1000;
    endDate = new Date(endDate);
    year    = endDate.getFullYear();
    month   = endDate.getMonth();
    day     = endDate.getDate() - 1;
  }
  else if(workDays == '12') 
  {
    year  = beginDate.getFullYear() + 1;
    month = beginDate.getMonth();
    day   = beginDate.getDate() - 1;
  }
  else
  {
    year  = beginDate.getFullYear();
    month = beginDate.getMonth() + Number(workDays);
    if(month > 12)
    {
      year  = year + 1;
      month = month - 12; 
    }
    day   = beginDate.getDate() - 1;
  }
  endDate = year + '-' + month + '-' + day;
  if( month < 10) month = '0' + month;
  if( day < 10)   day   = '0' + day;
  end = year + '-' + month + '-' + day;
  $('#end').val(end);
  endDate =  convertStringToDate(endDate);
  $('#days').val((endDate - beginDate) / (1000 * 60 * 60 * 24) + 1);
}

/* Auto compute the work days. */
$(function() 
{
    $(".date").bind('dateSelected', function()
    {
        computeWorkDays();
    })
});

