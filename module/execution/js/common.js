function switchStatus(projectID, status)
{
  if(status) location.href = createLink('project', 'task', 'project=' + projectID + '&type=' + status);
}

function setWhite(acl)
{
    acl != 'open' ? $('#whitelistBox').removeClass('hidden') : $('#whitelistBox').addClass('hidden');
}

function switchGroup(projectID, groupBy)
{
    link = createLink('project', 'groupTask', 'project=' + projectID + '&groupBy=' + groupBy);
    location.href=link;
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
 * @param  string $date1
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
 * @access public
 * @return void
 */
function computeWorkDays(currentID)
{
    isBatchEdit   = false;
    var beginDate = '';
    var endDate   = '';
    if(currentID)
    {
        index = currentID.replace(/[a-zA-Z]*/g, '');
        if(!isNaN(index)) isBatchEdit = true;
    }

    if(isBatchEdit)
    {
        beginDate = $("input[name=begins\\[" + index + "\\]]").val();
        endDate   = $("input[name=ends\\[" + index + "\\]]").val();
    }
    else
    {
        beginDate = $('#begin').val();
        endDate   = $('#end').val();
    }

    if(beginDate && endDate)
    {
        if(isBatchEdit)  $("input[name=dayses\\[" + index + "\\]]").val(computeDaysDelta(beginDate, endDate));
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
function computeEndDate(delta)
{
    beginDate = $('#begin').val();
    if(!beginDate) return;

    delta     = parseInt(delta);
    beginDate = convertStringToDate(beginDate);
    if((delta == 7 || delta == 14) && (beginDate.getDay() == 1))
    {
        delta = (weekend == 2) ? (delta - 2) : (delta - 1);
    }

    endDate = $.zui.formatDate(beginDate.addDays(delta - 1), 'yyyy-MM-dd');
    $('#end').val(endDate).datetimepicker('update');
    computeWorkDays();
}

/* Auto compute the work days. */
$(function()
{
    $(".date").bind('dateSelected', function()
    {
        computeWorkDays(this.id);
    })
});

/**
 * Load branches.
 *
 * @param  int $product
 * @access public
 * @return void
 */
function loadBranches(product)
{
    /* When selecting a product, delete a plan that is empty by default. */
    $("#planDefault").remove();

    $(".productsBox select[name^='products']").each(function()
    {
        var $product  = $(product);
        var productID = $(this).val();
        if($product.val() != 0 && $product.val() == $(this).val() && $product.attr('id') != $(this).attr('id') && !multiBranchProducts[$product.val()])
        {
            bootbox.alert(errorSameProducts);
            $product.val(0);
            $product.trigger("chosen:updated");
            return false;
        }
    });

    var $tableRow = $(product).closest('.table-row');
    var index     = $tableRow.find('select:first').attr('id').replace('products' , '');
    var oldBranch = $(product).attr('data-branch') !== undefined ? $(product).attr('data-branch') : 0;
    if($(product).val() != 0)
    {
        $(product).closest('tr').find('.newProduct').addClass('hidden')
    }
    else
    {
        $(product).closest('tr').find('.newProduct').removeClass('hidden')
    }

    if(!multiBranchProducts[$(product).val()])
    {
        $tableRow.find('.table-col:last select').val('').trigger('chosen:updated');
        $tableRow.find('.table-col:last').addClass('hidden');
    }

    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + $(product).val() + "&oldBranch=" + oldBranch + "&param=active&projectID=" + projectID + "&withMainBranch=true"), function(data)
    {
        if(data)
        {
            $tableRow.find("select[name^='branch']").replaceWith(data);
            $tableRow.find('.table-col:last .chosen-container').remove();
            $tableRow.find('.table-col:last').removeClass('hidden');
            $tableRow.find("select[name^='branch']").attr('multiple', '').attr('name', 'branch[' + index + '][]').attr('id', 'branch' + index).attr('onchange', "loadPlans('#products" + index + "', this)").chosen();

            disableSelectedProduct();
        }

        if(typeof isStage != 'undefined' && isStage == true)
        {
            $tableRow.find("select[name^='branch'] option").attr('selected', 'selected');
            $tableRow.find("select[name^='branch']").trigger('chosen:updated');
            $tableRow.find("div[id^='branch']").addClass('chosen-disabled');
        }
    });

    var branch = $('#branch' + index);
    loadPlans(product, branch);
}

/**
 * Load plans.
 *
 * @param  obj $product
 * @param  obj $branchID
 * @access public
 * @return void
 */
function loadPlans(product, branch)
{
    var productID = $(product).val();
    var branchID  = $(branch).val() == null ? 0 : '0,' + $(branch).val();
    var planID    = $(product).attr('data-plan') !== undefined ? $(product).attr('data-plan') : 0;
    var index     = $(product).attr('id').replace('products', '');

    $.get(createLink('product', 'ajaxGetPlans', "productID=" + productID + '&branch=' + branchID + '&planID=' + planID + '&fieldID&needCreate=&expired=unexpired,noclosed&param=skipParent,multiple'), function(data)
    {
        if(data)
        {
            $("div#plan" + index).find("select[name^='plans']").replaceWith(data);
            $("div#plan" + index).find('.chosen-container').remove();
            $("div#plan" + index).find('select').attr('name', 'plans[' + productID + ']' + '[]').attr('id', 'plans' + productID).chosen();
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
function addNewLine(obj)
{
    var newLine = $(obj).closest('tr').clone();
    var index   = 0;
    $(".productsBox select[name^='products']").each(function()
    {
        var id = $(this).attr('id').replace('products' , '');

        id = parseInt(id);
        id ++;

        index = id > index ? id : index;
    })

    newLine.find('.newProduct').remove();
    newLine.find('.addProduct').remove();
    newLine.addClass('newLine');
    newLine.find('th').html('');
    newLine.find('.removeLine').css('visibility', 'visible');
    newLine.find('.chosen-container').remove();
    newLine.find('.productsBox .table-col:last').addClass('hidden');
    newLine.find("select[name^='products']").attr('name', 'products[' + index + ']').attr('id', 'products' + index).val('').chosen();
    newLine.find("select[name^='plans']").attr('name', 'plans[' + index + '][' + 0 + '][]').chosen();
    newLine.find("div[id^='plan']").attr('id', 'plan' + index);
    newLine.find('[name*=products]').removeAttr('data-last');

    $(obj).closest('tr').after(newLine);
    var product = newLine.find("select[name^='products']");
    var branch  = newLine.find("select[name^='branch']");
    loadPlans(product, branch);
    disableSelectedProduct();
}

function removeLine(obj)
{
    $(obj).closest('tr').remove();
    disableSelectedProduct();
}

$(function()
{
    $(document).on('click', '.task-toggle', function(e)
    {
        var $toggle = $(this);
        var id = $(this).data('id');
        var isCollapsed = $toggle.toggleClass('collapsed').hasClass('collapsed');
        $toggle.closest('[data-ride="table"]').find('tr.parent-' + id).toggle(!isCollapsed);

        e.stopPropagation();
        e.preventDefault();
    });
});

/**
 * Set card count.
 *
 * @param  string $heightType
 * @access public
 * @return void
 */
function setCardCount(heightType)
{
    heightType != 'custom' ? $('#cardBox').addClass('hidden') : $('#cardBox').removeClass('hidden');
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
        $('.productsBox .planBox').addClass('hide');
        $('.productsBox .planBox select').attr('disabled', 'disabled');
        $('#productTitle').text(manageProductsLang);

        $('#plansBox').closest('tr').addClass('hide');
        $('#plansBox').attr('disabled', 'disabled');
    }
    else
    {
        $('.productsBox .planBox').removeClass('hide');
        $('.productsBox .planBox select').removeAttr('disabled');
        $('#productTitle').text(manageProductPlanLang);

        $('#plansBox').closest('tr').removeClass('hide');
        $('#plansBox').removeAttr('disabled');
    }
}
