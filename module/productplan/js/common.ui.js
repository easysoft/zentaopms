/**
 * Compute the end date for productplan.
 *
 * @access public
 * @return void
 */
function computeEndDate(e)
{
    let delta     = $(e.target).val();
    let beginDate = $('input[name=begin]').val();
    if(!beginDate) return;

    delta     = currentDelta = parseInt(delta);
    beginDate = convertStringToDate(beginDate);
    if((delta == 7 || delta == 14) && (beginDate.getDay() == 1))
    {
        delta = (weekend == 2) ? (delta - 2) : (delta - 1);
    }

    let currentBeginDate = window.zui.formatDate(beginDate, 'yyyy-MM-dd');
    let endDate          = formatDate(beginDate, delta - 1);

    $('[name=begin]').zui('datePicker').$.setValue(currentBeginDate);
    $('[name=end]').zui('datePicker').$.setValue(endDate);
    setTimeout(function(){$('[name=delta]').val(`${currentDelta}`)}, 0);
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
 * Toggle date box.
 *
 * @access public
 * @return void
 */
function toggleDateBox(e)
{
    if($(e.target).prop('checked'))
    {
        $('#begin').attr('disabled', 'disabled');
        $('#end').attr('disabled', 'disabled').parents('.form-row').addClass('hidden');
    }
    else
    {
        $('#begin').removeAttr('disabled');
        $('#end').removeAttr('disabled').parents('.form-row').removeClass('hidden');
    }
}

/**
 * Load branches.
 *
 * @access public
 * @return void
 */
function loadBranches(e)
{
    let parentID        = $(e.target).val();
    let currentBranches = $('select[name^=branch]').val() ? $('select[name^=branch]').val().toString() : '';
    $.get($.createLink('productplan', 'ajaxGetParentBranches', "productID=" + productID + "&parentID=" + parentID), function(data)
    {
        if(data)
        {
            data = JSON.parse(data);
            const $branchPicker = $('select[name^=branch]').zui('picker');
            $branchPicker.render({items: data, multiple: true});
            $branchPicker.$.setValue(currentBranches);
        }
    })
}

/**
 * Load title.
 *
 * @access public
 * @return void
 */
function loadTitle(e)
{
    if(parentPlanID) return;

    let branchIdList = $(e.target).val();
    if(!branchIdList) return;

    branchIdList = branchIdList.toString();
    $.get($.createLink('productplan', 'ajaxGetLast', "productID=" + productID + "&branch=" + branchIdList), function(data)
    {
        data = JSON.parse(data);
        let planTitle = data ? '(' + lastLang + ': ' + data.title + ')' : '';
        $('#lastTitleBox').text(planTitle);
    })
}
