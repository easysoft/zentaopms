window.ignoreTips = {
    'beyondBudgetTip' : false,
    'dateTip'         : false
};
var batchEditDateTips = new Array();

/**
 * Access rights are equal to private, and the white list settings are displayed.
 *
 * @param  string acl
 * @access public
 * @return void
 */
function setWhite(acl)
{
    acl != 'open' ? $('#whitelistBox').removeClass('hidden') : $('#whitelistBox').addClass('hidden');
}

/**
 * Convert a date string like 2011-11-11 to date object in js.
 *
 * @param  string dateString
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
 * @param  string date1
 * @param  string date2
 * @access public
 * @return int
 */
function computeDaysDelta(date1, date2)
{
    date1 = convertStringToDate(date1);
    date2 = convertStringToDate(date2);
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
 * Compute work days.
 *
 * @param  string currentID
 * @access public
 * @return void
 */
function computeWorkDays(currentID)
{
    isBactchEdit = false;
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
        beginDate = $('#begin').val();
        endDate   = $('#end').val();

        var begin = new Date(beginDate.replace(/-/g,"/"));
        var end   = new Date(endDate.replace(/-/g,"/"));
        var time  = end.getTime() - begin.getTime();
        var days  = parseInt(time / (1000 * 60 * 60 * 24)) + 1;
        if(days != $("input:radio[name='delta']:checked").val()) $("input:radio[name='delta']:checked").attr('checked', false);
        if(endDate == longTime) $("#delta999").prop('checked', true);
    }

    if(beginDate && endDate)
    {
        if(isBactchEdit)  $('#dayses\\[' + index + '\\]').val(computeDaysDelta(beginDate, endDate));
        if(!isBactchEdit) $('#days').val(computeDaysDelta(beginDate, endDate));
    }
    else if($('input[checked="true"]').val())
    {
        computeEndDate();
    }
    outOfDateTip(isBactchEdit ? index : 0);
}

/**
 * Compute the end date for project.
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
    if(delta == 999)
    {
        $('#end').val(longTime).trigger('mousedown');
        $('#daysBox').addClass('hidden');
        $('#days').val(0).trigger('mousedown');
        outOfDateTip();
        return false;
    }
    $('#daysBox').removeClass('hidden');
    beginDate = convertStringToDate(beginDate);
    if((delta == 7 || delta == 14) && (beginDate.getDay() == 1))
    {
        delta = (weekend == 2) ? (delta - 2) : (delta - 1);
    }

    endDate = $.zui.formatDate(beginDate.addDays(delta - 1), 'yyyy-MM-dd');
    $('#end').val(endDate).datetimepicker('update').trigger('mousedown');
    computeWorkDays();
    $('#days').trigger('mousedown');
}

/**
 * Initialization.
 *
 * @access public
 * @return void
 */
$(function()
{
    $('#privList > tbody > tr > th input[type=checkbox]').change(function()
    {
        var id      = $(this).attr('id');
        var checked = $(this).prop('checked');

        if(id == 'allChecker')
        {
            $('input[type=checkbox]').prop('checked', checked);
        }
        else
        {
            $(this).parents('tr').find('input[type=checkbox]').prop('checked', checked);
        }
    });

    $('#subNavbar').find('li[data-id=module] a').attr('data-app', 'project');
})

/**
 * Change budget input.
 *
 * @access public
 * @return void
 */
$(function()
{
    $('#future').on('change', function()
    {
        if($(this).prop('checked'))
        {
            $('#budget').val('').attr('disabled', 'disabled');
            if($('#beyondBudgetTip').length > 0) $('#beyondBudgetTip').parent().parent().remove();
        }
        else
        {
            $('#budget').removeAttr('disabled');
        }
    });
})

$(document).on('change', "#plansBox select[name^='plans']", function()
{
    var $plan = $(this);
    $("#plansBox select[name^='plans']").each(function()
    {
        var planIDList = $plan.val() == null ? 0 : $plan.val();
        for(var i = 0; i < planIDList.length; i++)
        {
            var planID = planIDList[i];
            if(planID != 0 && $(this).val() && $(this).val().includes(planID) && $plan.closest('div').attr('id') != $(this).closest('div').attr('id'))
            {
                bootbox.alert(errorSamePlans);
                $plan.val(0);
                $plan.trigger("chosen:updated");
                return false;
            }
        }
    });
});

/**
 * Append prompt when the budget exceeds the parent project set.
 *
 * @param  int    $projectID
 * @access public
 * @return void
 */
function budgetOverrunTips()
{
    if(window.ignoreTips['beyondBudgetTip']) return;

    var selectedProgramID = $('#parent').val();
    var budget            = $('#budget').val();

    if(selectedProgramID == 0)
    {
        if($('#beyondBudgetTip').length > 0) $('#beyondBudgetTip').parent().parent().remove();
        return false;
    }

    if(typeof(projectID) == 'undefined') projectID = 0;
    $.get(createLink('project', 'ajaxGetObjectInfo', 'objectType=project&objectID=' + projectID + "&selectedProgramID=" + selectedProgramID), function(data)
    {
        var data = JSON.parse(data);
        if(typeof(data.availableBudget) == 'undefined') return;

        var tip = "";
        if(budget != 0 && budget !== null && budget > data.availableBudget) tip = "<tr><td></td><td colspan='2'><span id='beyondBudgetTip' class='text-remind'><p>" + budgetOverrun + currencySymbol[data.budgetUnit] + data.availableBudget.toFixed(2) + "</p><p id='ignore' onclick='ignoreTip(this)'>" + ignore + "</p></span></td></tr>"
        if($('#beyondBudgetTip').length > 0) $('#beyondBudgetTip').parent().parent().remove();
        $('#budgetBox').parent().parent().after(tip);
        $('#beyondBudgetTip').parent().css('line-height', '0');

        var placeholder = '';
        if(selectedProgramID) placeholder = parentBudget + currencySymbol[data.budgetUnit] + data.availableBudget.toFixed(2);
        if($('#budget').attr('placeholder')) $('#budget').removeAttr('placeholder')
        $('#budget').attr('placeholder', placeholder);
    });
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
    if(typeof(systemMode) != 'undefined' && systemMode == 'light') return;
    if(batchEditDateTips.includes(Number(currentID))) return;

    var end   = currentID ? $('#ends\\[' + currentID + '\\]').val() : $('#end').val();
    var begin = currentID ? $('#begins\\[' + currentID + '\\]').val() : $('#begin').val();
    if($('#dateTip.text-remind').length > 0) $('#dateTip').parent().parent().remove();
    if(currentID) $('#dateTip\\[' + currentID + '\\]').remove();

    if(end == longTime) end = LONG_TIME;
    if(end.length > 0 && begin.length > 0)
    {
        var selectedProgramID = currentID ? $("select[name='parents\[" + currentID + "\]']").val() : $('#parent').val();

        if(selectedProgramID == 0 || selectedProgramID == undefined) return;

        if(typeof(projectID) == 'undefined') projectID = 0;
        projectID = currentID ? $('#projectIdList\\['+ currentID + '\\]').val() : projectID;
        $.get(createLink('project', 'ajaxGetObjectInfo', 'objectType=project&objectID=' + projectID + '&selectedProgramID=' + selectedProgramID), function(data)
        {
            var data         = JSON.parse(data);
            var parentEnd    = new Date(data.selectedProgramEnd);
            var parentBegin  = new Date(data.selectedProgramBegin);
            var projectEnd   = new Date(end);
            var projectBegin = new Date(begin);

            var beginLetterParentTip = beginLetterParent + data.selectedProgramBegin;
            var endGreaterParentTip  = endGreaterParent + data.selectedProgramEnd;

            if(projectBegin >= parentBegin && projectEnd <= parentEnd) return;

            var dateTip = "";
            if(projectBegin < parentBegin)
            {
                dateTip = currentID ? beginLetterParentTip + "'><p>" + beginLetterParentTip : beginLetterParentTip;
            }
            else if(projectEnd > parentEnd)
            {
                dateTip = currentID ? endGreaterParentTip + "'><p>" + endGreaterParentTip : endGreaterParentTip;
            }

            if(currentID)
            {
                $("#projects\\[" + currentID + "\\]").after("<tr><td colspan='5'></td><td class='c-name' colspan='3'><span id='dateTip" + currentID + "' class='text-remind' title='" + dateTip + "</p><p id='ignore' onclick='ignoreTip(this," + currentID + ")'>" + ignore + "</p></span></td></tr>");
                $('#dateTip'+ currentID).parent().css('line-height', '0')
            }
            else
            {
                $('#dateRange').parent().after("<tr><td></td><td colspan='2'><span id='dateTip' class='text-remind'><p>" + dateTip + "</p><p id='ignore' onclick='ignoreTip(this)'>" + ignore + "</p></span></td><tr>");
                $('#dateTip').parent().css('line-height', '0')
            }
        });
    }
}

/**
 * Make this prompt no longer appear.
 *
 * @param  string  $obj
 * @param  string  $currentID
 * @access public
 * @return void
 */
function ignoreTip(obj, currentID)
{
    var parentID = obj.parentNode.id;
    currentID ? $('#dateTip' + currentID).parent().parent().remove() : $('#' + parentID).parent().parent().remove();

    if(parentID == 'dateTip') window.ignoreTips['dateTip'] = true;
    if(parentID == 'dateTip' + currentID) batchEditDateTips.push(currentID);
    if(parentID == 'beyondBudgetTip') window.ignoreTips['beyondBudgetTip'] = true;
}
