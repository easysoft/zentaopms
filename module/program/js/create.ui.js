window.onParentChange = (event) =>
{
    const parentID = $(event.target).val();
    const url      = $.createLink('program', 'create', parentID ? ('parentProgramID=' + parentID) : '');
    loadPage(url, '#budgetRow>*, #acl');
};

window.onBudgetChange = (event) =>
{
    const $budget       = $(event.target);
    const currentBudget = $budget.val();
    const budgetLeft    = $budget.data('budget-left');
    if(currentBudget > budgetLeft)
    {
        $('<div class="form-tip text-warning" id="budgetTip"></div>').text(lang.budgetOverrun + $budget.data('currency-symbol') + budgetLeft).append($('<a class="text-inherit ml-2 underline"></a>').text(lang.ignore).on('click', () => $('#budgetTip').remove())).appendTo($budget.closest('.form-group'));
    }
};

window.onFutureChange = (event) =>
{
    $('#budget,#budgetUnit').attr('disabled', $(event.target).prop('checked') ? 'disabled' : null);
    $('#budgetTip').remove();
};

window.outOfDateTip = function()
{
    console.warn('The method outOfDateTip is not implemented.');
};

/**
 * Compute delta of two days.
 *
 * @param  string date1
 * @param  string date2
 * @access public
 * @return int
 */
function computeDaysDelta(date1, date2)
{
    date1 = zui.createDate(date1);
    date2 = zui.createDate(date2);
    const delta = (date2 - date1) / (1000 * 60 * 60 * 24) + 1;

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
 * Compute work days.
 *
 * @param  string currentID
 * @access public
 * @return void
 */
window.computeWorkDays = function(currentID)
{
    if(typeof currentID === 'object') currentID = $(currentID.target).val();
    let isBactchEdit = false;
    let index;
    if(currentID)
    {
        index = currentID.replace('begins[', '');
        index = index.replace('ends[', '');
        index = index.replace(']', '');
        if(!isNaN(index)) isBactchEdit = true;
    }

    let beginDate;
    let endDate;
    if(isBactchEdit)
    {
        beginDate = $('#begins\\[' + index + '\\]').val();
        endDate   = $('#ends\\[' + index + '\\]').val();
    }
    else
    {
        beginDate = $('#begin').val();
        endDate   = $('#end').val();

        var begin = new Date(beginDate.replace(/-/g,"/"));
        var end   = new Date(endDate.replace(/-/g,"/"));
        var time  = end.getTime() - begin.getTime();
        var days  = parseInt(time / (1000 * 60 * 60 * 24)) + 1;
        if(days != $('input[name="delta"]:checked').val()) $('input[name="delta"]:checked').attr('checked', false);
        if(endDate == LONG_TIME) $('#delta999').prop('checked', true);
    }

    if(beginDate && endDate)
    {
        if(isBactchEdit)  $('#dayses\\[' + index + '\\]').val(computeDaysDelta(beginDate, endDate));
        else              $('#days').val(computeDaysDelta(beginDate, endDate));
    }
    else if($('input[checked="true"]').val())
    {
        computeEndDate();
    }
    outOfDateTip();
};

/**
 * Compute the end date for project.
 *
 * @param  int    $delta
 * @access public
 * @return void
 */
window.computeEndDate = function(delta)
{
    delta = +$('input[name="delta"]:checked').val();
    let beginDate = $('#begin').val();
    if(!beginDate) return;

    if(delta === 999)
    {
        $('#end').val(LONG_TIME);
        outOfDateTip();
        return false;
    }

    beginDate = zui.createDate(beginDate);
    if((delta === 7 || delta === 14) && (beginDate.getDay() === 1))
    {
        delta = (weekend === 2) ? (delta - 2) : (delta - 1);
    }

    const endDate = zui.formatDate(beginDate.getTime() + ((delta - 1) * zui.TIME_DAY), 'yyyy-MM-dd');
    $('#end').val(endDate);
    computeWorkDays();
};

window.onAclChange = () =>
{
    $('#whitelistRow').toggleClass('hidden', $('#acl_open').prop('checked'));
};
