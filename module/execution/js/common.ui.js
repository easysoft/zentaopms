/**
 * Compute work days.
 *
 * @access public
 * @return void
 */
function computeWorkDays(currentID)
{
    isBatchEdit = false;
    if(currentID)
    {
        index = currentID.replace(/[a-zA-Z]*\[|\]/g, '');
        if(!isNaN(index)) isBatchEdit = true;
    }

    let beginDate, endDate;
    if(isBatchEdit)
    {
        beginDate = $("input[name=begin\\[" + index + "\\]]").val();
        endDate   = $("input[name=end\\[" + index + "\\]]").val();
    }
    else
    {
        beginDate = $('input[name=begin]').val();
        endDate   = $('input[name=end]').val();
    }

    if(beginDate && endDate)
    {
        if(isBatchEdit)  $("input[name=days\\[" + index + "\\]]").val(computeDaysDelta(beginDate, endDate));
        if(!isBatchEdit) $('#days').val(computeDaysDelta(beginDate, endDate));
    }
    else if($('input[checked="true"]').val())
    {
        computeEndDate();
    }
}

/**
 * Compute the end date for project.
 *
 * @param  int    $delta
 * @access public
 * @return void
 */
function computeEndDate()
{
    let delta     = $('input[name^=delta]:checked').val();
    let beginDate = $('input[name=begin]').val();
    if(!beginDate) return;

    delta     = currentDelta = parseInt(delta);
    beginDate = convertStringToDate(beginDate);
    if((delta == 7 || delta == 14) && (beginDate.getDay() == 1))
    {
        delta = (weekend == 2) ? (delta - 2) : (delta - 1);
    }

    let endDate = formatDate(beginDate, delta - 1);

    $('input[name=end]').zui('datePicker').$.setValue(endDate);
    computeWorkDays();
    setTimeout(function(){$('[name=delta]').val(`${currentDelta}`)}, 0);
}

/**
 * 给指定日期加上具体天数，并返回格式化后的日期.
 *
 * @param  string dateString
 * @param  int    days
 * @access public
 * @return date
 */
function formatDate(dateString, days)
{
  const date = new Date(dateString);
  date.setDate(date.getDate() + days);

  const year  = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day   = String(date.getDate()).padStart(2, '0');

  return `${year}-${month}-${day}`;
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
    dateString = dateString.split('-');
    return new Date(dateString[0], dateString[1] - 1, dateString[2]);
}

/**
 * Compute delta of two days.
 *
 * @param  string $date1
 * @param  string $date2
 * @access public
 * @return int
 */
function computeDaysDelta(date1, date2)
{
    date1 = convertStringToDate(date1);
    date2 = convertStringToDate(date2);
    delta = (date2 - date1) / (1000 * 60 * 60 * 24) + 1;

    let weekEnds = 0;
    for(i = 0; i < delta; i++)
    {
        if((weekend == 2 && date1.getDay() == 6) || date1.getDay() == 0) weekEnds ++;
        date1 = date1.valueOf();
        date1 += 1000 * 60 * 60 * 24;
        date1 = new Date(date1);
    }
    return delta - weekEnds;
}

/**
 * Hide plan box by stage's attribute.
 *
 * @param  string    attribute
 * @access public
 * @return void
 */
function hidePlanBox(attribute)
{
    if(attribute == 'request' || attribute == 'review')
    {
        $('.productsBox .planBox').addClass('hidden');
        $('.productsBox .planBox select').attr('disabled', 'disabled');

        $('#plansBox').closest('.form-row').addClass('hidden');
        $('#plansBox').attr('disabled', 'disabled');
    }
    else
    {
        $('.productsBox .planBox').removeClass('hidden');
        $('.productsBox .planBox select').removeAttr('disabled');

        $('#plansBox').closest('.form-row').removeClass('hidden');
        $('#plansBox').removeAttr('disabled');
    }
}

/**
 * Set white.
 *
 * @param  string  $acl
 * @access public
 * @return void
 */
function setWhite()
{
    const acl = $("[name^='acl']:checked").val();
    acl != 'open' ? $('#whitelistBox').removeClass('hidden') : $('#whitelistBox').addClass('hidden');
}

/**
 * Show lifetime tips.
 *
 * @access public
 * @return void
 */
function showLifeTimeTips()
{
    const lifetime = $('#lifetime').val();
    if(lifetime == 'ops')
    {
        $('#lifeTimeTips').removeClass('hidden');
    }
    else
    {
        $('#lifeTimeTips').addClass('hidden');
    }
}

/**
 * 提示并删除执行。
 * Delete execution with tips.
 *
 * @param  int    executionID
 * @param  string executionName
 * @access public
 * @return void
 */
window.confirmDeleteExecution = function(executionID, confirmDeleteTip)
{
    zui.Modal.confirm({message: confirmDeleteTip, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('execution', 'delete', 'executionID=' + executionID + '&comfirm=yes')});
    });
}
