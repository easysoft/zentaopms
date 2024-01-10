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
 * Load branches.
 *
 * @param  event  $e
 * @access public
 * @return void
 */
function loadBranches(e)
{
    /* When selecting a product, delete a plan that is empty by default. */
    $("#planDefault").remove();

    let chosenProducts = [];
    let $product       = $(e.target);
    $("[name^='products']").each(function()
    {
        let productID = $(this).val();
        if(productID > 0 && chosenProducts.indexOf(productID) == -1) chosenProducts.push(productID);
        if($product.val() != 0 && $product.val() == $(this).val() && $product.attr('id') != $(this).attr('id'))
        {
            zui.Modal.alert(errorSameProducts);
            $product.zui('picker').$.setValue(0);
            return false;
        }
    });

    let $formRow  = $product.closest('.form-row');
    let index     = $formRow.find("[name^='products']").first().attr('name').match(/\d+/)[0];
    let oldBranch = $(e.target).attr('data-branch') !== undefined ? $product.attr('data-branch') : 0;

    if(!multiBranchProducts[$product.val()])
    {
        $formRow.find('.form-group').last().find('select').val('');
        $formRow.find('.form-group').eq(0).addClass('w-1/2').removeClass('w-1/4');
        $formRow.find('.form-group').eq(1).addClass('hidden');
    }

    $.getJSON($.createLink('branch', 'ajaxGetBranches', "productID=" + $product.val() + "&oldBranch=" + oldBranch + "&param=active&projectID=" + projectID + "&withMainBranch=true"), function(data)
    {
        if(data.length > 0)
        {
            $formRow.find('.form-group').eq(0).addClass('w-1/4').removeClass('w-1/2');
            $formRow.find('.form-group').eq(1).removeClass('hidden');
            const $branchPicker = $formRow.find('select[name^=branch]').zui('picker');
            $branchPicker.render({items: data, multiple: true});
        }
    });

    let branch = $('#branch' + index);
    loadPlans(e.target, branch);
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
                name: `plans[${productID}][]`,
            });
        }
    });
}

/**
 * Add new line for link product.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function addNewLine(e)
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
    newLine.find('.form-group').eq(0).addClass('w-1/2').removeClass('w-1/4');
    newLine.find('.form-group').eq(1).addClass('hidden');
    newLine.find("div[id^='plan']").attr('id', 'plan' + index);
    newLine.find('.linkProduct > .form-label').html('').removeClass('required');
    newLine.find('.removeLine').removeClass('hidden');


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
 * @param  obj    e
 * @access public
 * @return void
 */
function removeLine(e)
{
    const obj = e.target;
    $(obj).closest('.form-row').remove();
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
    zui.Modal.confirm({message: confirmDeleteTip, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('execution', 'delete', 'executionID=' + executionID + '&comfirm=yes')});
    });
}
