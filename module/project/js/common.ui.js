const DAY_MILLISECONDS = 24 * 60 * 60 * 1000;

window.ignoreTips = {
    'beyondBudgetTip' : false,
    'dateTip'         : false
};

var batchEditDateTips = new Array();

/**
 * 处理项目类型改变的交互。
 * Handle project type change style.
 *
 * @param  string $type
 * @access public
 * @return void
 */
function changeType(type)
{
    if($('.project-type-' + type).hasClass('disabled')) return;
    $('.project-type-1, .project-type-0').removeClass('primary-pale');
    $('.project-type-' + type).addClass('primary-pale');
    $('input[name=hasProduct]').val(type);

    if(type == 0)
    {
        $('.productBox').addClass('hidden');
    }
    else
    {
        $('.productBox').removeClass('hidden');
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
    $('.project-stageBy-1, .project-stageBy-0').removeClass('primary-pale');
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
    const begin = $('#begin').zui('datePicker').$.state.value;
    const end   = $('#end').zui('datePicker').$.state.value;

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

    outOfDateTip();
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
    const beginDate = $('#begin').zui('datePicker').$.state.value;
    if(!beginDate) return;

    const delta      = parseInt($('input[name=delta]:checked').val());
    const isLongTime = delta == 999;
    const endDate    = isLongTime ? LONG_TIME : formatDate(beginDate, delta - 1);

    $('#end').toggleClass('hidden', isLongTime).zui('datePicker').$.changeState({value: endDate});
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
 * 提示并删除项目。
 * Delete project with tips.
 *
 * @param  int    projectID
 * @param  string projectName
 * @access public
 * @return void
 */
window.confirmDelete = function(projectID, projectName)
{
    zui.Modal.confirm({message: confirmDeleteTip.replace('%s', projectName), icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('project', 'delete', 'projectID=' + projectID)});
    });
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
    $.get($.createLink('project', 'ajaxGetProjectFormInfo', 'objectType=project&objectID=' + projectID + "&selectedProgramID=" + selectedProgramID), function(data)
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
    if($('#dateTip.text-remind').length > 0) $('#dateTip').closest('.form-row').remove();
    if(currentID) $('#dateTip\\[' + currentID + '\\]').remove();

    if(end == longTime) end = LONG_TIME;
    if(end.length > 0 && begin.length > 0)
    {
        var selectedProgramID = currentID ? $("[name='parents\[" + currentID + "\]']").val() : $('#parent').val();

        if(selectedProgramID == 0 || selectedProgramID == undefined) return;

        if(typeof(projectID) == 'undefined') projectID = 0;
        projectID = currentID ? $('#projectIdList\\['+ currentID + '\\]').val() : projectID;
        $.get($.createLink('project', 'ajaxGetProjectFormInfo', 'objectType=project&objectID=' + projectID + '&selectedProgramID=' + selectedProgramID), function(data)
        {
            var data         = JSON.parse(data);
            var parentEnd    = new Date(data.selectedProgramEnd);
            var parentBegin  = new Date(data.selectedProgramBegin);
            var projectEnd   = new Date(end);
            var projectBegin = new Date(begin);

            var beginLessThanParentTip = beginLessThanParent + data.selectedProgramBegin;
            var endGreatThanParentTip  = endGreatThanParent + data.selectedProgramEnd;

            if(projectBegin >= parentBegin && projectEnd <= parentEnd) return;

            var dateTip = "";
            if(projectBegin < parentBegin)
            {
                dateTip = currentID ? beginLessThanParentTip + "'><p>" + beginLessThanParentTip : beginLessThanParentTip;
            }
            else if(projectEnd > parentEnd)
            {
                dateTip = currentID ? endGreatThanParentTip + "'><p>" + endGreatThanParentTip : endGreatThanParentTip;
            }

            if(currentID)
            {
                $("#projects\\[" + currentID + "\\]").after("<tr><td colspan='5'></td><td class='c-name' colspan='3'><span id='dateTip" + currentID + "' class='text-remind' title='" + dateTip + "</p><p id='ignore' onclick='ignoreTip(this," + currentID + ")'>" + ignore + "</p></span></td></tr>");
                $('#dateTip'+ currentID).parent().css('line-height', '0')
            }
            else
            {
                $('#begin').closest('.form-row').after("<div class='form-row' id='dateTipBox'><div class='form-group'><div class='input-group'><span id='dateTip' class='text-remind'><p>" + dateTip + "</p><p id='ignore' onclick='ignoreTip(this)'>" + ignore + "</p></span></div></div></div>");
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
window.ignoreTip = function(obj, currentID)
{
    var parentID = obj.parentNode.id;
    currentID ? $('#dateTip' + currentID).parent().parent().remove() : $('#' + parentID).closest('.form-row').remove();

    if(parentID == 'dateTip') window.ignoreTips['dateTip'] = true;
    if(parentID == 'dateTip' + currentID) batchEditDateTips.push(currentID);
    if(parentID == 'beyondBudgetTip') window.ignoreTips['beyondBudgetTip'] = true;
}

/**
 * Add new line for link product.
 *
 * @param  obj e
 * @access public
 * @return void
 */
window.addNewLine = function(e)
{
    const obj     = e.target
    const newLine = $(obj).closest('.form-row').clone();

    let index   = 0;
    let options = zui.Picker.query("[name^='products']").options;

    /* 将已有产品下拉的最大name属性的值加1赋值给新行. */
    $("[name^='products']").each(function()
    {
        let id = $(this).attr('name').replace(/[^\d]/g, '');

        id = parseInt(id);
        id ++;

        index = id > index ? id : index;
    })

    /* 处理新一行控件的显示/隐藏，宽度/是否居中等样式问题. */
    newLine.addClass('newLine');
    newLine.find('.form-label').html('');
    newLine.find('.removeLine').removeClass('disabled');
    newLine.find('[name="newProduct"]').closest('div.items-center').remove();
    newLine.find('.form-group').eq(0).addClass('w-1/2').removeClass('w-1/4');
    newLine.find('.form-group').eq(1).addClass('hidden');
    newLine.find("div[id^='plan']").attr('id', 'plan' + index);

    $(obj).closest('.form-row').after(newLine);

    /* 重新初始化新一行的下拉控件. */
    newLine.find('.form-group').eq(0).find('.picker-box').empty();
    newLine.find('.form-group').eq(0).find('.picker-box').append(`<div id=products${index}></div>`);

    newLine.find('div[id^=plan] .picker-box').empty();
    newLine.find('div[id^=plan] .picker-box').append(`<div id=plans${index}></div>`);

    options.name         = `products[${index}]`;
    options.defaultValue = '';
    new zui.Picker(`#products${index}`, options);

    new zui.Picker(`#plans${index}`, {
        items:[],
        multiple: true,
        name: `plans[${index}]`,
    });
}

/**
 * Remove line for link product.
 *
 * @param  obj e
 * @access public
 * @return void
 */
window.removeLine = function(e)
{
    const obj = e.target;

    /* Dsiabled btn can't remove line. */
    if($(obj).closest('.btn').hasClass('disabled')) return false;

    $(obj).closest('.form-row').remove();

    let chosenProducts = 0;
    $("[name^='products']").each(function()
    {
        if($(this).val() > 0) chosenProducts ++;
    });

    (chosenProducts.length > 1 && (model == 'waterfall' || model == 'waterfallplus')) ? $('.stageBy').removeClass('hidden') : $('.stageBy').addClass('hidden');
}

/**
 * Load branches.
 *
 * @param  int $product
 * @access public
 * @return void
 */
window.loadBranches = function(product)
{
    /* When selecting a product, delete a plan that is empty by default. */
    $("#planDefault").remove();

    let chosenProducts = [];
    let $product       = $(product);
    $("[name^='products']").each(function()
    {
        let productID = $(this).val();
        if(productID > 0 && chosenProducts.indexOf(productID) == -1) chosenProducts.push(productID);
        if($product.val() != 0 && $product.val() == $(this).val() && $product.attr('id') != $(this).attr('id') && !multiBranchProducts[$product.val()])
        {
            zui.Modal.alert(errorSameProducts);
            $(`#${product.id}`).zui('picker').$.setValue(0);
            return false;
        }
    });

    (chosenProducts.length > 1 && (model == 'waterfall' || model == 'waterfallplus')) ? $('.stageBy').removeClass('hidden') : $('.stageBy').addClass('hidden');

    let $formRow  = $(product).closest('.form-row');
    let index     = $formRow.find("[name^='products']").first().attr('name').match(/\d+/)[0];
    let oldBranch = $(product).attr('data-branch') !== undefined ? $(product).attr('data-branch') : 0;

    if(!multiBranchProducts[$(product).val()])
    {
        $formRow.find('.form-group').last().find('select').val('');
        $formRow.find('.form-group').eq(0).addClass('w-1/2').removeClass('w-1/4');
        $formRow.find('.form-group').eq(1).addClass('hidden').find('select').val('');
        $formRow.find('.form-group').eq(0).find('.newProductBox').removeClass('hidden');
    }

    $.get($.createLink('branch', 'ajaxGetBranches', "productID=" + $(product).val() + "&oldBranch=" + oldBranch + "&param=active"), function(data)
    {
        if(data)
        {
            $formRow.find('.form-group').eq(1).find('.picker-box').empty();
            $formRow.find('.form-group').eq(1).find('.picker-box').append(`<div id='branch${index}'></div>`);

            $formRow.find('.form-group').eq(0).addClass('w-1/4').removeClass('w-1/2');
            $formRow.find('.form-group').eq(1).removeClass('hidden');
            $formRow.find('.form-group').eq(0).find('.newProductBox').addClass('hidden');

            data = JSON.parse(data);
            new zui.Picker(`#branch${index}`, {
                items: data,
                multiple: true,
                name: `branch[${index}]`,
            });
        }

        let branch = $('#branch' + index);
        loadPlans(product, branch);
    });
}

/**
 * Load plans.
 *
 * @param  obj $product
 * @param  obj $branchID
 * @access public
 * @return void
 */
window.loadPlans = function(product, branch)
{
    let productID = $(product).val();
    let branchID  = $(branch).val() == null ? 0 : '0,' + $(branch).val();
    let planID    = $(product).attr('data-plan') !== undefined ? $(product).attr('data-plan') : 0;
    let index     = $(product).attr('name').match(/\d+/)[0];

    $.get($.createLink('product', 'ajaxGetPlans', "productID=" + productID + '&branch=' + branchID + '&planID=' + planID + '&fieldID&needCreate=&expired=unexpired,noclosed&param=skipParent,multiple'), function(data)
    {
        if(data)
        {
            data = JSON.parse(data);

            $("div#plan" + index).find('.picker-box').empty();
            $("div#plan" + index).find('.picker-box').append(`<div id='plans${productID}'></div>`);

            new zui.Picker(`#plans${productID}`, {
                items: data,
                multiple: true,
                name: `plans[${productID}]`,
            });
        }
    });
}
