window.ignoreTips = {
    'beyondBudgetTip' : false,
    'dateTip'         : false
};

var batchEditDateTips = new Array();

/**
 * 设置计划结束时间。
 * Set plan end date.
 *
 * @access public
 * @return void
 */
function setDate()
{
    const delta = $('input[name=delta]:checked').val();
    computeEndDate(delta);
}

/**
 * 计算两个时间的天数。
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
    delta = (date2 - date1) / (1000 * 60 * 60 * 24) + 1;

    if(isNaN(delta)) return;

    weekEnds = 0;
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
 * 计算可用工作日天数。
 * Compute work days.
 *
 * @param  string currentID
 * @access public
 * @return void
 */
window.computeWorkDays = function(e)
{
    isBactchEdit  = false;
    let currentID = typeof e == 'object' ? $(e.target).attr('id') : '';
    if(currentID)
    {
        index = currentID.replace('begins[', '');
        index = index.replace('ends[', '');
        index = index.replace(']', '');
        if(!isNaN(index)) isBactchEdit = true;
    }

    if(isBactchEdit)
    {
        beginDate = $('#begins\\[' + index + '\\]').val();
        endDate   = $('#ends\\[' + index + '\\]').val();
    }
    else
    {
        beginDate = $('#begin').zui('datepicker').$.state.value;
        endDate   = $('#end').zui('datepicker').$.state.value;

        var begin = zui.createDate(beginDate);
        var end   = zui.createDate(endDate);
        var time  = end.getTime() - begin.getTime();
        var days  = String(parseInt(time / zui.TIME_DAY) + 1);

        $("#delta" + days).prop('checked', true);
        if(endDate === longTime) $("#delta999").prop('checked', true);
    }

    outOfDateTip(isBactchEdit ? index : 0);
}

/**
 * 计算并设置计划完成时间。
 * Compute the end date for project.
 *
 * @param  int    delta
 * @access public
 * @return void
 */
function computeEndDate(delta)
{
    let $begin = $('#begin').zui('datepicker');
    let $end   = $('#end').zui('datepicker');
    let beginDate = $begin.$.state.value;
    if(!beginDate) return;

    delta = parseInt(delta);
    if(delta == 999)
    {
        $end.$.setDate('');
        $end.render({disabled: true});
        outOfDateTip();
        $('#end').addClass('hidden');
        $('#end').next().removeClass('hidden');
        return false;
    }

    $end.render({disabled: false});
    $('#days').closest('.form-row').removeClass('hidden');

    beginDate = zui.createDate(beginDate);
    if((delta == 7 || delta == 14) && (beginDate.getDay() == 1))
    {
        delta = (weekend == 2) ? (delta - 2) : (delta - 1);
    }

    $('#end').removeClass('hidden');
    $('#end').next().addClass('hidden');

    const endDate = zui.formatDate(zui.addDate(beginDate, delta - 1), 'yyyy-MM-dd');
    $end.$.setValue(endDate);
    computeWorkDays();

}

/**
 * If future is checked, disable budget input.
 *
 * @param  object e
 * @access public
 * @return void
 */
window.toggleBudget = function(e)
{
    const future = e.target;
    if($(future).prop('checked'))
    {
        $('#budget').val('').attr('disabled', 'disabled');
    }
    else
    {
        $('#budget').removeAttr('disabled');
    }
}

/**
 * If change multiple, set delta.
 *
 * @param  object e
 * @access public
 * @return void
 */
window.toggleMultiple = function(e)
{
    const multiple = e.target;
    $('#delta_999').closest('.radio-primary').toggle($(multiple).val() != 0);
    if($('#delta_999').prop('checked') && $(multiple).val() == 0)
    {
        $('#delta_999').prop('checked', false);
        $('#end').removeAttr('disabled').val('');
        $('#days').closest('.form-row').removeClass('hidden');
    }
}
/**
 * compare childlish date.
 *
 * @access public
 * @return void
 */
function compareChildDate()
{
    if(window.ignoreTips['dateTip']) return;
    if(page == 'create') return;

    let begin = $('#begin').zui('datepicker').$.state.value;
    let end   = $('#end').zui('datepicker').$.state.value;
    let selectedProgramID = $('#parent').val();
    if($('#dateTip').length > 0) $('#dateTip').closest('.form-row').remove();

    if(end == longTime) end = LONG_TIME;
    if(end.length > 0 && begin.length > 0)
    {
        var programEnd   = new Date(end);
        var programBegin = new Date(begin);

        if(typeof(programID) == 'undefined') programID = 0;
        $.get($.createLink('project', 'ajaxGetProjectFormInfo', 'objectType=program&objectID=' + programID + '&selectedProgramID=' + selectedProgramID), function(data)
        {
            var childInfo = JSON.parse(data);
            if(childInfo.maxChildEnd == '' || childInfo.minChildBegin == '') return;

            var childBegin = new Date(childInfo.minChildBegin);
            var childEnd   = new Date(childInfo.maxChildEnd);
            if(programBegin <= childBegin && programEnd >= childEnd) return;

            var dateTip = '';
            if(programBegin > childBegin)
            {
                dateTip = "<tr><td></td><td colspan='2'><span id='dateTip' class='text-remind'><p>" + beginGreatEqualChild + childInfo.minChildBegin + "</p><p id='ignore' onclick='ignoreTip(this)'>" + ignore + "</p></span></td></tr>";
            }
            else if(programEnd < childEnd)
            {
                dateTip = "<tr><td></td><td colspan='2'><span id='dateTip' class='text-remind'><p>" + endLessThanChild + childInfo.maxChildEnd + "</p><p id='ignore' onclick='ignoreTip(this)'>" + ignore + "</p></span></td></tr>";
            }

            $('#dateBox').parent().parent().after(dateTip);
            $('#dateTip').parent().css('line-height', '0');
        });
    }
}

/**
 *The date is out of the range of the parent project set, and a prompt is given.
 *
 * @param  string $currentID
 * @access public
 * @return void
 */
function outOfDateTip(currentID)
{
    if(window.ignoreTips['dateTip']) return;

    let begin = $('#begin').zui('datepicker').$.state.value;
    let end   = $('#end').zui('datepicker').$.state.value;
    if($('#dateTip').length > 0) $('#dateTip').closest('.form-row').remove();

    if(end == longTime) end = LONG_TIME;
    if(end.length > 0 && begin.length > 0)
    {
        var selectedProgramID = $('#parent').val();
        var programEnd        = new Date(end);
        var programBegin      = new Date(begin);

        if(selectedProgramID == 0)
        {
            compareChildDate();
            return;
        }

        if(typeof(programID) == 'undefined') programID = 0;
        $.get($.createLink('project', 'ajaxGetProjectFormInfo', 'objectType=program&objectID=' + programID + "&selectedProgramID=" + selectedProgramID), function(data)
        {
            var dateTip     = '';
            var data        = JSON.parse(data);
            var parentEnd   = new Date(data.selectedProgramEnd);
            var parentBegin = new Date(data.selectedProgramBegin);

            if(programBegin >= parentBegin && programEnd <= parentEnd)
            {
                compareChildDate();
                return;
            }

            if(programBegin < parentBegin)
            {
                dateTip = "<div class='form-row' id='dateTipBox'><div class='form-group'><div class='input-group'><span id='dateTip' class='text-remind'><p>" + beginLessThanParent + data.selectedProgramBegin + "</p><p id='ignore' onclick='ignoreTip(this)'>" + ignore + "</p></span></div></div></div>";
            }
            else if(programEnd > parentEnd)
            {
                dateTip = "<div class='form-row' id='dateTipBox'><div class='form-group'><div class='input-group'><span id='dateTip' class='text-remind'><p>" + endGreatThanParent + data.selectedProgramEnd + "</p><p id='ignore' onclick='ignoreTip(this)'>" + ignore + "</p></span></div></div></div>";
            }

            $('#begin').closest('.form-row').after(dateTip);
        });
    }
}

/**
 * Append prompt when the budget exceeds the parent project set.
 *
 * @access public
 * @return void
 */
window.budgetOverrunTips = function(e)
{
    if(window.ignoreTips['beyondBudgetTip']) return;

    var selectedProgramID = $('[name=parent]').zui('picker').$.state.value;
    var budget            = $('#budget').val();
    if(selectedProgramID == 0)
    {
        if($('#beyondBudgetTip').length > 0) $('#beyondBudgetTip').closest('.form-row').remove();
        return false;
    }

    if(typeof(programID) == 'undefined') programID = 0;
    $.get($.createLink('project', 'ajaxGetProjectFormInfo', 'objectType=program&objectID=' + programID + "&selectedProgramID=" + selectedProgramID), function(data)
    {
        var data = JSON.parse(data);

        var tip = "";
        if(budget !=0 && budget !== null && budget > data.availableBudget) var tip = "<div class='form-row'><div class='form-group'><div class='input-group'><span id='beyondBudgetTip' class='text-remind'><p>" + budgetOverrun + currencySymbol[data.budgetUnit] + data.availableBudget + "</p><p id='ignore' onclick='ignoreTip(this)'>" + ignore + "</p></span></div></div></div>";

        if($('#beyondBudgetTip').length > 0) $('#beyondBudgetTip').closest('.form-row').remove();
        $('[name=budget]').parent().after(tip);
    });
}

/**
 * Make this prompt no longer appear.
 *
 * @param  string  $obj
 * @access public
 * @return void
 */
window.ignoreTip = function(obj)
{
    var parentID = obj.parentNode.id;
    $('#' + parentID).closest('.form-row').remove();

    if(parentID == 'dateTip') window.ignoreTips['dateTip'] = true;
    if(parentID == 'beyondBudgetTip') window.ignoreTips['beyondBudgetTip'] = true;
}
