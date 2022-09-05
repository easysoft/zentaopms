window.ignoreTips = {
    'beyondBudgetTip' : false,
    'dateTip'         : false
};

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
    outOfDateTip();
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
        $('#end').val(longTime);
        outOfDateTip();
        return false;
    }

    beginDate = convertStringToDate(beginDate);
    if((delta == 7 || delta == 14) && (beginDate.getDay() == 1))
    {
        delta = (weekend == 2) ? (delta - 2) : (delta - 1);
    }

    endDate = $.zui.formatDate(beginDate.addDays(delta - 1), 'yyyy-MM-dd');
    $('#end').val(endDate).datetimepicker('update');
    computeWorkDays();
}

/**
 * Load branches.
 *
 * @param  object   product
 * @access public
 * @return void
 */
function loadBranches(product)
{
    /* When selecting a product, delete a plan that is empty by default. */
    $("#planDefault").remove();

    $('#productsBox select').each(function()
    {
        var $product = $(product);
        if($product.val() != 0 && $product.val() == $(this).val() && $product.attr('id') != $(this).attr('id'))
        {
            alert(errorSameProducts);
            $product.val(0);
            $product.trigger("chosen:updated");
            return false;
        }
    });

    if($('#productsBox .input-group:last select:first').val() != 0)
    {
        var length = $('#productsBox .input-group').size();
        $('#productsBox .row').append('<div class="col-sm-4">' + $('#productsBox .col-sm-4:last').html() + '</div>');
        if($('#productsBox .input-group:last select').size() >= 2) $('#productsBox .input-group:last select:last').remove();
        $('#productsBox .input-group:last .chosen-container').remove();
        $('#productsBox .input-group:last select:first').attr('name', 'products[' + length + ']').attr('id', 'products' + length);
        $('#productsBox .input-group:last .chosen').chosen();

        adjustProductBoxMargin();
    }

    var $inputgroup = $(product).closest('.input-group');
    if($inputgroup.find('select').size() >= 2) $inputgroup.removeClass('has-branch').find('select:last').remove();
    if($inputgroup.find('.chosen-container').size() >= 2) $inputgroup.find('.chosen-container:last').remove();

    var index = $inputgroup.find('select:first').attr('id').replace('products' , '');
    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + $(product).val()), function(data)
    {
        if(data)
        {
            $inputgroup.addClass('has-branch').append(data);
            $inputgroup.find('select:last').attr('name', 'branch[' + index + ']').attr('id', 'branch' + index).attr('onchange', "loadPlans('#products" + index + "', this.value)").chosen();
        }
    });

    loadPlans(product);
}

function loadPlans(product, branchID)
{
    if($('#plansBox').size() == 0) return false;

    var productID = $(product).val();
    var branchID  = typeof(branchID) == 'undefined' ? 0 : branchID;
    var index     = $(product).attr('id').replace('products', '');

    if(productID != 0)
    {
        if(typeof(planID) == 'undefined') planID = 0;
        planID = $("select#plans" + productID).val() != '' ? $("select#plans" + productID).val() : planID;
        $.get(createLink('product', 'ajaxGetPlans', "productID=" + productID + '&branch=' + branchID + '&planID=' + planID + '&fieldID&needCreate=&expired=' + ((config.currentMethod == 'create' || config.currentMethod == 'edit') ? 'unexpired' : '')), function(data)
        {
            if(data)
            {
                if($("div#plan" + index).size() == 0) $("#plansBox .row").append('<div class="col-sm-4" id="plan' + index + '"></div>');
                $("div#plan" + index).html(data).find('select').attr('name', 'plans[' + productID + ']').attr('id', 'plans' + productID).chosen();

                adjustPlanBoxMargin();
            }
        });
    }
}

/**
 * Adjust the layout of product selection.
 *
 * @access public
 * @return void
 */
function adjustProductBoxMargin()
{
    var productRows = Math.ceil($('#productsBox > .row > .col-sm-4').length / 3);
    if(productRows > 1)
    {
        for(i = 1; i <= productRows - 1; i++)
        {
            $('#productsBox .col-sm-4:lt(' + (i * 3) + ')').css('margin-bottom', '10px');
        }
    }
}

/**
 * Adjust the layout of the plan selection.
 *
 * @access public
 * @return void
 */
function adjustPlanBoxMargin()
{
    var planRows = Math.ceil($('#plansBox > .row > .col-sm-4').length / 3);
    if(planRows > 1)
    {
        for(j = 1; j <= planRows - 1; j++)
        {
            $('#plansBox .col-sm-4:lt(' + (j * 3) + ')').css('margin-bottom', '10px');
        }
    }
}

/**
 * Initialization operation.
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

/**
 * Set budget tips and acl list.
 *
 * @param  int    $programID
 * @access public
 * @return void
 */
function setBudgetTipsAndAclList(parentID)
{
    var selectedProgramID = $('#parent').val();

    if(parentID != 0)
    {
        $.get(createLink('project', 'ajaxGetObjectInfo', "objectType=program&objectID=" + parentID + "&selectedProgramID=" + selectedProgramID), function(data)
        {
            var data      = JSON.parse(data);
            parentProgram = programList[parentID];
            programBudget = parentProgram.budget;
            PGMBudgetUnit = currencySymbol[parentProgram.budgetUnit];

            budgetNotes = programBudget != 0 ? (PGMParentBudget + PGMBudgetUnit + data.availableBudget) : '';
            $('#budget').attr('placeholder', budgetNotes);
            refreshBudgetUnit(data);
        });
        $('.aclBox').html($('#subPGMAcl').html());
    }
    else
    {
        $('#budget').removeAttr('placeholder');
        $('.aclBox').html($('#PGMAcl').html());
    }

    if(typeof(programID) == 'undefined') programID = 0;
    budgetOverrunTips();
    outOfDateTip();
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

    var end               = $('#end').val();
    var begin             = $('#begin').val();
    var selectedProgramID = $('#parent').val();
    if($('#dateTip').length > 0) $('#dateTip').remove();

    if(end == longTime) end = LONG_TIME;
    if(end.length > 0 && begin.length > 0)
    {
        var programEnd   = new Date(end);
        var programBegin = new Date(begin);

        $.get(createLink('project', 'ajaxGetObjectInfo', 'objectType=program&objectID=' + programID + '&selectedProgramID=' + selectedProgramID), function(data)
        {
            var childInfo = JSON.parse(data);
            if(childInfo.maxChildEnd == '' || childInfo.minChildBegin == '') return;

            var childBegin = new Date(childInfo.minChildBegin);
            var childEnd   = new Date(childInfo.maxChildEnd);
            if(programBegin <= childBegin && programEnd >= childEnd) return;

            var dateTip = '';
            if(programBegin > childBegin)
            {
                dateTip = "<tr><td></td><td colspan='2'><span id='dateTip' class='text-remind'><p>" + beginGreateChild + childInfo.minChildBegin + "</p><p id='ignore' onclick='ignoreTip(this)'>" + ignore + "</p></span></td></tr>";
            }
            else if(programEnd < childEnd)
            {
                dateTip = "<tr><td></td><td colspan='2'><span id='dateTip' class='text-remind'><p>" + endLetterChild + childInfo.maxChildEnd + "</p><p id='ignore' onclick='ignoreTip(this)'>" + ignore + "</p></span></td></tr>";
            }

            $('#dateBox').parent().parent().after(dateTip);
            $('#dateTip').parent().css('line-height', '0');
        });
    }
}

/**
 * The date is out of the range of the parent project set, and a prompt is given.
 *
 * @access public
 * @return void
 */
function outOfDateTip()
{
    if(window.ignoreTips['dateTip']) return;

    var end   = $('#end').val();
    var begin = $('#begin').val();
    if($('#dateTip').length > 0) $('#dateTip').parent().parent().remove();

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
        $.get(createLink('project', 'ajaxGetObjectInfo', 'objectType=program&objectID=' + programID + "&selectedProgramID=" + selectedProgramID), function(data)
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
                dateTip = "<tr><td></td><td colspan='2'><span id='dateTip' class='text-remind'><p>" + beginLetterParent + data.selectedProgramBegin + "</p><p id='ignore' onclick='ignoreTip(this)'>" + ignore + "</p></span></td></tr>";
            }
            else if(programEnd > parentEnd)
            {
                dateTip = "<tr><td></td><td colspan='2'><span id='dateTip' class='text-remind'><p>" + endGreaterParent + data.selectedProgramEnd + "</p><p id='ignore' onclick='ignoreTip(this)'>" + ignore + "</p></span></td></tr>";
            }

            $('#dateBox').parent().parent().after(dateTip);
            $('#dateTip').parent().css('line-height', '0');
        });
    }
}

/**
 * Append prompt when the budget exceeds the parent project set.
 *
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

    if(typeof(programID) == 'undefined') programID = 0;
    $.get(createLink('project', 'ajaxGetObjectInfo', 'objectType=program&objectID=' + programID + "&selectedProgramID=" + selectedProgramID), function(data)
    {
        var data = JSON.parse(data);

        var tip = "";
        if(budget !=0 && budget !== null && budget > data.availableBudget) var tip = "<tr><td></td><td colspan='2'><span id='beyondBudgetTip' class='text-remind'><p>" + budgetOverrun + currencySymbol[data.budgetUnit] + data.availableBudget + "</p><p id='ignore' onclick='ignoreTip(this)'>" + ignore + "</p></span></td></tr>"
        if($('#beyondBudgetTip').length > 0) $('#beyondBudgetTip').parent().parent().remove();
        $('#budgetBox').parent().parent().after(tip);
        $('#beyondBudgetTip').parent().css('line-height', '0');
    });
}

/**
 * Make this prompt no longer appear.
 *
 * @param  string  $obj
 * @access public
 * @return void
 */
function ignoreTip(obj)
{
    var parentID = obj.parentNode.id;
    $('#' + parentID).addClass('hidden');

    if(parentID == 'dateTip') window.ignoreTips['dateTip'] = true;
    if(parentID == 'beyondBudgetTip') window.ignoreTips['beyondBudgetTip'] = true;
}
