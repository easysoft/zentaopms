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
    const begin = $('#begin').zui('datePicker').$.value;
    const end   = $('#end').zui('datePicker').$.value;

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
 * 检查项目预算是否超出父项目集剩余预算。
 * Check if the project budget exceeds the remaining budget of the parent program.
 *
 * @param  int    projectID
 * @access public
 * @return void
 */
function checkBudget(projectID)
{
    if(ignoreTips['budgetTip']) return;

    const programID = $('[name=parent]').val();
    if(programID == 0)
    {
        $('#budget').removeAttr('placeholder');
        $('#budgetTip').addClass('hidden');
        return false;
    }

    if(typeof(projectID) == 'undefined') projectID = 0;

    $.get($.createLink('project', 'ajaxGetProjectFormInfo', 'objectType=project&objectID=' + projectID + "&selectedProgramID=" + programID), function(response)
    {
        const data = JSON.parse(response);
        if(typeof(data.availableBudget) == 'undefined') return;

        const budget = $('#budget').val() * budgetUnitValue;
        if(budget != 0 && budget !== null && budget > data.availableBudget)
        {
            const currency = currencySymbol[data.budgetUnit];
            const availableBudget = (data.availableBudget / budgetUnitValue).toFixed(2);
            $('#budget').attr('placeholder', parentBudget + currency + availableBudget);
            $('#budgetTip').removeClass('hidden');
            $('#budgetTip').find('#currency').text(currency);
            $('#budgetTip').find('#parentBudget').text(availableBudget);
            $('#budgetTip').find('#budgetUnit').text(budgetUnitLabel);
            return;
        }

        $('#budgetTip').addClass('hidden');
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

    if(typeof(projectID) == 'undefined') projectID = 0;
    $.get($.createLink('project', 'ajaxGetProjectFormInfo', 'objectType=project&objectID=' + projectID + '&selectedProgramID=' + selectedProgramID), function(response)
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
        if($product.val() != 0 && $product.val() == $(this).val() && $product.attr('id') != $(this).attr('id'))
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

    $.getJSON($.createLink('branch', 'ajaxGetBranches', "productID=" + $(product).val() + "&oldBranch=" + oldBranch + "&param=active"), function(data)
    {
        if(data.length > 0)
        {
            $formRow.find('.form-group').eq(1).find('.picker-box').empty();
            $formRow.find('.form-group').eq(1).find('.picker-box').append(`<div id='branch${index}'></div>`);

            $formRow.find('.form-group').eq(0).addClass('w-1/4').removeClass('w-1/2');
            $formRow.find('.form-group').eq(1).removeClass('hidden');
            $formRow.find('.form-group').eq(0).find('.newProductBox').addClass('hidden');

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
