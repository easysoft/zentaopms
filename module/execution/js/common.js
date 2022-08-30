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
    isBactchEdit = false;
    if(currentID)
    {
        index = currentID.replace(/\w*\[|\]/g, '');
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

/**
 * Load branches.
 *
 * @param  int $product
 * @access public
 * @return void
 */
function loadBranches(product)
{
    if($('#productsBox .input-group:last select:first').val() != 0)
    {
        var length = $('#productsBox .input-group').size();
        $('#productsBox .row').append('<div class="col-sm-4">' + $('#productsBox .col-sm-4:last').html().replace('required', '') + '</div>');
        if($('#productsBox .input-group:last select').size() >= 2) $('#productsBox .input-group:last select:last').remove();
        $('#productsBox .input-group:last .chosen-container').remove();
        $('#productsBox .input-group:last select:first').attr('name', 'products[' + length + ']').attr('id', 'products' + length);
        $('#productsBox .input-group:last .chosen').chosen();

        adjustProductBoxMargin();
    }

    var $inputgroup = $(product).closest('.input-group');
    if($inputgroup.find('select').size() >= 2) $inputgroup.removeClass('has-branch').find('select:last').remove();
    if($inputgroup.find('.chosen-container').size() >= 2) $inputgroup.find('.chosen-container:last').remove();

    var projectID = (typeof(systemMode) != 'undefined' && systemMode == 'new') ? $('#project').val() : 0;
    if(typeof(projectID) == 'undefined') projectID = 0;

    var index = $inputgroup.find('select:first').attr('id').replace('products' , '');
    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + $(product).val() + "&oldBranch=&param=active&projectID=" + projectID + "&withMainBranch=true"), function(data)
    {
        if(data)
        {
            $inputgroup.addClass('has-branch').append(data);
            $inputgroup.find('select:last').attr('name', 'branch[' + index + ']').attr('id', 'branch' + index).attr('onchange', "loadPlans('#products" + index + "', this.value)").chosen();

            $inputgroup.find('select:last').each(disableSelectedBranch);
            disableSelectedProduct();
        }

        var branchID = $('#branch' + index).val();
        loadPlans(product, branchID);
    });

    if(!multiBranchProducts[$(product).val()]) disableSelectedProduct();
}

/**
 * Load plans by product id.
 *
 * @param  int $product
 * @param  int $branchID
 * @access public
 * @return void
 */
function loadPlans(product, branchID)
{
    if($('#plansBox').size() == 0) return false;

    var productID = $(product).val();
    var branchID  = typeof(branchID) == 'undefined' ? 0 : branchID;
    var index     = $(product).attr('id').replace('products', '');

    $.get(createLink('product', 'ajaxGetPlans', "productID=" + productID + '&branch=0,' + branchID + '&planID=0&fieldID&needCreate=&expired=noclosed,unexpired&param=skipParent,multiple'), function(data)
    {
        if(data)
        {
            if($("div#plan" + index).size() == 0) $("#plansBox .row").append('<div class="col-sm-4" id="plan' + index + '"></div>');
            $("div#plan" + index).html(data).find('select').attr('name', 'plans[' + productID + '][' + branchID + '][]').attr('id', 'plans' + productID).chosen();

            adjustPlanBoxMargin();
        }
    });
}

/**
 * Adjust product box margin.
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
 * Adjust plan box margin.
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

/* Auto compute the work days. */
$(function()
{
    $(".date").bind('dateSelected', function()
    {
        computeWorkDays(this.id);
    })
});

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

    adjustProductBoxMargin();
    adjustPlanBoxMargin();
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
