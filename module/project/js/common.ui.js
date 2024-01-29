const DAY_MILLISECONDS = 24 * 60 * 60 * 1000;

const ignoreTips = {
    budgetTip: false,
    dateTip: false
}

/**
 * 处理项目类型改变的交互。
 * Handle project type change style.
 *
 * @param  string $type
 * @access public
 * @return void
 */
function changeType()
{
    if($(this).val() == 0)
    {
        $('.productsBox').addClass('hidden');
        $('.stageByBox').addClass('hidden');
    }
    else
    {
        $('.productsBox').removeClass('hidden');
    }
}

/**
 * 处理阶段类型改变的交互。
 * Handle stage by change style.
 *
 * @param  string $type
 * @access public
 * @return void
 */
function changeStageBy(type)
{
    if($('.project-stageBy-' + type).hasClass('disabled')) return;
    $('.project-stageBy-project, .project-stageBy-product').removeClass('primary-pale');
    $('.project-stageBy-' + type).addClass('primary-pale');
    $('input[name=stageBy]').val(type);
}

/**
 * 计算两个日期之间可用的工作日。
 * Compute work days between two dates.
 *
 * @param  string date1
 * @param  string date2
 * @access public
 * @return int
 */
function computeDaysDelta(date1, date2)
{
    date1 = new Date(date1);
    date2 = new Date(date2);
    const time = date2 - date1;
    const days = parseInt(time / DAY_MILLISECONDS) + 1;
    if(isNaN(days)) return;

    let weekendDays = 0;
    for(i = 0; i < days; i++)
    {
        if((weekend == 2 && date1.getDay() == 6) || date1.getDay() == 0) weekendDays ++;
        date1 = date1.valueOf() + DAY_MILLISECONDS;
        date1 = new Date(date1);
    }
    return days - weekendDays;
}

/**
 * 更新可用工作日天数。
 * Update work days.
 *
 * @access public
 * @return void
 */
function computeWorkDays()
{
    const begin = $('[name=begin]').val();
    const end   = $('[name=end]').val();

    if(end == LONG_TIME)
    {
        $('#delta999').prop('checked', true);
        $('#days').val(0).trigger('change');
    }
    else
    {
        $('#days').val(computeDaysDelta(begin, end)).trigger('change');
        const time = new Date(end) - new Date(begin);
        const days = parseInt(time / DAY_MILLISECONDS) + 1;
        if(days != $("input[name='delta']:checked").val())
        {
            $("input[name='delta']:checked").prop('checked', false);
            $('#delta' + days).prop('checked', true);
        }
    }
    checkProjectInfo();
}

/**
 * 计算并设置计划完成时间。
 * Compute the end date for project.
 *
 * @access public
 * @return void
 */
function computeEndDate()
{
    const beginDate = $('#begin').zui('datePicker').$.value;
    if(!beginDate) return;

    const delta = parseInt($('input[name=delta]:checked').val());
    if(isNaN(delta)) return;

    const isLongTime = delta == 999;
    const endDate    = isLongTime ? LONG_TIME : formatDate(beginDate, delta - 1);

    $('#end').toggleClass('hidden', isLongTime).zui('datePicker').$.setValue(endDate);
    $('#end').next().toggleClass('hidden', !isLongTime);
    $('#days').closest('.form-row').toggleClass('hidden', isLongTime);
}

/**
 * 给指定日期加上具体天数，并返回格式化后的日期.
 * Add days to date, and return formatted date.
 *
 * @param  string dateString
 * @param  int    days
 * @access public
 * @return string
 */
function formatDate(dateString, days)
{
    const date = new Date(dateString);
    date.setDate(date.getDate() + days);

    return date.toLocaleDateString('en-CA')
}

/**
 * Access rights are equal to private, and the white list settings are displayed.
 *
 * @param  string acl
 * @access public
 * @return void
 */
function setWhite()
{
    const acl = $("input[name='acl']:checked").val();
    acl != 'open' ? $('select[name^=whitelist]').closest('.form-row').removeClass('hidden') : $('select[name^=whitelist]').closest('.form-row').addClass('hidden');
}

$(document).on('change', "input[name='acl']", setWhite);

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
        $('[name=budget]').val('').addClass('disabled pointer-events-none');
        $('#budgetUnit-toggle').addClass('disabled pointer-events-none');
    }
    else
    {
        $('[name=budget]').removeAttr('disabled');
        $('[name=budget]').removeClass('disabled').removeClass('pointer-events-none');
        $('#budgetUnit-toggle').removeClass('disabled').removeClass('pointer-events-none');
    }
}

window.toggleBudgetUnit = function(unit)
{
    $('[data-name="budget"] > .has-prefix > .input-control-prefix > a').text(currencySymbol[unit]);
    $('[name="budgetUnit"]').val(unit);
};

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
 * 检查项目预算是否超出父项目集剩余预算。
 * Check if the project budget exceeds the remaining budget of the parent program.
 *
 * @access public
 * @return void
 */
function checkBudget()
{
    if(ignoreTips['budgetTip']) return;

    $('#budgetTip').addClass('hidden');
    checkProjectInfo();
}

function checkProjectInfo()
{
    const programID = $('[name=parent]').val();
    if(programID == 0)
    {
        $('[name=budget]').removeAttr('placeholder');
        $('#dateTip').addClass('hidden');
        $('#budgetTip').addClass('hidden');
        $('#budgetTip').addClass('text-danger');
        $('#budgetTip').removeClass('text-warning');
        $('#budgetTip').heml('');
        return false;
    }

    if(typeof(currentProject) == 'undefined') currentProject = 0;

    $.getJSON($.createLink('project', 'ajaxGetProjectFormInfo', 'objectType=project&objectID=' + currentProject + "&selectedProgramID=" + programID), function(data)
    {
        let dateTip = '';
        if(typeof(data.selectedProgramBegin) != 'undefined' && $('[name=begin]').val() != '' && $('[name=begin]').val() < data.selectedProgramBegin) dateTip += beginLessThanParent.replace('%s', data.selectedProgramBegin);
        if(typeof(data.selectedProgramEnd) != 'undefined' && $('[name=end]').val() != '' && $('[name=end]').val() > data.selectedProgramEnd) dateTip += endGreatThanParent.replace('%s', data.selectedProgramEnd);
        if(dateTip != '')
        {
            $('#dateTip').html(dateTip);
            $('#dateTip').append($('<span id="ignoreDate" class="underline">' + ignore + '</span>'));
            $('#dateTip').removeClass('hidden');
            $('#dateTip').off('click', '#ignoreDate').on('click', '#ignoreDate', function(){ignoreTip('dateTip')});
        }

        if(typeof(data.availableBudget) != 'undefined')
        {
            const budget          = $('[name=budget]').val();
            const currency        = currencySymbol[data.budgetUnit];
            const availableBudget = data.availableBudget.toFixed(2);
            $('[name=budget]').attr('placeholder', parentBudget + currency + availableBudget);
            if(budget != 0 && budget !== null && budget > data.availableBudget)
            {
                $('#budgetTip').html(budgetOverrun.replace('%s', currency + availableBudget));
                $('#budgetTip').append($('<span id="ignoreBudget" class="underline">' + ignore + '</span>'));
                $('#budgetTip').removeClass('hidden');
                $('#budgetTip').removeClass('text-danger');
                $('#budgetTip').addClass('text-warning');
                $('#budgetTip').off('click', '#ignoreBudget').on('click', '#ignoreBudget', function(){ignoreTip('budgetTip')});
            }
        }
    });
}

/**
 * The date is out of the range of the parent project set, and a prompt is given.
 *
 * @access public
 * @return void
 */
function checkDate()
{
    if(ignoreTips['dateTip']) return;

    const begin = $('#begin').zui('datePicker').$.value;
    const end   = $('#end').zui('datePicker').$.value;
    if(!begin || !end) return;

    const selectedProgramID = $('[name=parent]').val();
    if(selectedProgramID == 0 || selectedProgramID == undefined)
    {
        $('#dateTip, #beginLess, #endGreater').addClass('hidden');
        return;
    }

    if(typeof(currentProject) == 'undefined') currentProject = 0;
    $.get($.createLink('project', 'ajaxGetProjectFormInfo', 'objectType=project&objectID=' + currentProject + '&selectedProgramID=' + selectedProgramID), function(response)
    {
        const data         = JSON.parse(response);
        const parentEnd    = new Date(data.selectedProgramEnd);
        const parentBegin  = new Date(data.selectedProgramBegin);
        const projectEnd   = new Date(end);
        const projectBegin = new Date(begin);

        if(projectBegin >= parentBegin && projectEnd <= parentEnd)
        {
            $('#dateTip, #beginLess, #endGreater').addClass('hidden');
            return;
        }

        $('#dateTip').removeClass('hidden').find('#beginLess').toggleClass('hidden', projectBegin >= parentBegin).find('.parentBegin').text(data.selectedProgramBegin);
        $('#dateTip').removeClass('hidden').find('#endGreater').toggleClass('hidden', projectEnd <= parentEnd).find('.parentEnd').text(data.selectedProgramEnd);
    });
}

/**
 * 忽略提示信息。
 * Ignore tips.
 *
 * @param  string $tip
 * @access public
 * @return void
 */
function ignoreTip(tip)
{
    $('#' + tip).remove();
    ignoreTips[tip] = true;
}

function toggleStageBy()
{
    let chosenProducts = 0;
    $(".productsBox [name^='products']").each(function()
    {
        if($(this).val() > 0) chosenProducts ++;
    });

    if(chosenProducts > 1)  $('.stageByBox').removeClass('hidden');
    if(chosenProducts <= 1) $('.stageByBox').addClass('hidden');
}

window.getDateMenu = function()
{
    if(!endList) return [];

    const begin = $('input[name=begin]').val();
    if(!begin) return [];

    let endMenu     = [];
    const beginDate = new Date(begin);
    for(let key in endList)
    {
        endMenu.push({'text': endList[key], 'data-set-date': zui.formatDate(new Date(beginDate.getTime() + 1000 * 60 * 60 * 24 * parseInt(key)), 'yyyy-MM-dd')});
    }
    return endMenu;
}
