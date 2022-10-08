$(function()
{
    $('#copyProjects a').click(function()
    {
        setCopyProject($(this).data('id'));
        $('#copyProjectModal').modal('hide')
    });

    setAclList($("#parent").val());

    if(typeof(currentPlanID) == 'undefined')
    {
        $('#productsBox select[id^="products"]').each(function()
        {
            var branchID = 0;
            if($(this).closest('.input-group').find('select[id^="branch"]').size() > 0)
            {
                var branchID = $(this).closest('.input-group').find('select[id^="branch"]').val();
            }
            loadPlans($(this), branchID);
        });
    }

    $('[data-toggle="popover"]').popover();

    var acl = $("[name^='acl']:checked").val();
    setWhite(acl);

    $('#submit').click(function()
    {
        var products      = new Array();
        var existedBranch = false;

        /* Remove init tips. */
        $('#name').removeClass('has-info');
        $('#nameLabelInfo').remove();
        $('#code').removeClass('has-info');
        $('#codeLabelInfo').remove();
        $('#end').removeClass('has-info');
        $('#endLabelInfo').remove();
        $('#days').removeClass('has-info');
        $('#daysLabelInfo').remove();

        /* Determine whether the products of the same branch are linked. */
        $("#productsBox select[name^='products']").each(function()
        {
            var productID = $(this).val();
            if(typeof(products[productID]) == 'undefined') products[productID] = new Array();
            if(multiBranchProducts[productID])
            {
                var branchID = $(this).closest('.input-group').find("select[id^=branch]").val();
                if(products[productID][branchID])
                {
                    existedBranch = true;
                }
                else
                {
                    products[productID][branchID] = branchID;
                }
                if(existedBranch) return false;
            }
        });

        if(existedBranch)
        {
            bootbox.alert(errorSameBranches);
            return false;
        }
    });

    if(selectedProductID)
    {
        $('#products0').val(selectedProductID);
        $('#products0').trigger("chosen:updated");

        loadBranches($('#products0'), selectedBranchID);
    }

    /* Init for copy execution. */
    $("select[id^=branch]").each(disableSelectedBranch);
    disableSelectedProduct();

    /* Check the all products and branches control when uncheck the product. */
    $(document).on('change', "select[id^='products']", function()
    {
        if($(this).val() == 0)
        {
            $("select[id^='branch']").each(disableSelectedBranch);

            disableSelectedProduct();
        }
    });

    $(document).on('change', "select[id^='branch']", disableSelectedBranch);

    if(copyProjectID > 0 && copyType != 'previous')
    {
        $('#name').addClass('has-info')
        $('#name').after('<div id="nameLabelInfo" class="text-info">' + nameTips + '</div>')
        $('#code').addClass('has-info')
        $('#code').after('<div id="codeLabelInfo" class="text-info">' + codeTips + '</div>')
        $('#end').addClass('has-info')
        $('#end').parent().after('<div id="endLabelInfo" class="text-info">' + endTips + '</div>')
        $('#days').addClass('has-info')
        $('#days').parent().after('<div id="daysLabelInfo" class="text-info">' + daysTips + '</div>')
    }
});

/**
 * Set parent program.
 *
 * @param  int    $parentProgram ParentProgram is the ID of the currently selected program.
 * @return void
 */
function setParentProgram(parentProgram)
{
    var lastSelectedID     = $('#parent').attr('data-lastSelected');
    var lastSelectedParent = 0;
    var selectedParent     = 0;

    if(parentProgram == 0) $('#budgetBox').find('input').removeAttr("placeholder");

    $.get(createLink('project', 'ajaxGetObjectInfo', 'objectType=program&objectID=' + lastSelectedID + "&selectedProgramID=" + parentProgram), function(data)
    {
        var data = JSON.parse(data);
        selectedParent = parentProgram != 0 ? data.selectedProgramPath[1] : 0;
        lastSelectedParent = lastSelectedID != 0 ? data.objectPath[1] : 0;

        if(selectedParent != lastSelectedParent)
        {
            $('#budget').val('');
            /* Hide product and plan dropdown controls. */
            $('#productsBox .row .col-sm-4:not(:last)').remove();
            $('#productsBox .row .col-sm-4:last select').remove();
            $('#productsBox .row .col-sm-4:last .chosen-container').remove();
            var select = data.allProducts;
            $('#productsBox .row .col-sm-4 .input-group').prepend(select)
            $('#productsBox .row .col-sm-4 .input-group select').chosen();

            $('#plansBox .col-sm-4:not(:last)').remove();
            $('#plansBox .col-sm-4').children().remove();
            var planSelect = data.plans;
            $('#plansBox .col-sm-4').prepend(planSelect);
            $('#plansBox .col-sm-4 select').chosen();
        }

        if(parentProgram != 0)
        {
            $('.aclBox').html($('#programAcl').html());
        }
        else
        {
            $('.aclBox').html($('#projectAcl').html());
        }

        budgetOverrunTips();
        outOfDateTip();
        refreshBudgetUnit(data);
    });

    $('#parent').attr('data-lastSelected', parentProgram);

    if(parentProgram != '0')
    {
        $('#productsBox .addProduct .input-group:first').addClass('required');
        $('#productsBox .row .input-group:first').addClass('required');
    }
    else
    {
        $('#productsBox .addProduct .input-group').removeClass('required');
        $('#productsBox .row .input-group').removeClass('required');
    }
}

/**
 * Set copy project.
 *
 * @param  int $copyProjectID
 * @access public
 * @return void
 */
function setCopyProject(copyProjectID)
{
    location.href = createLink('project', 'create', 'model=' + model + '&programID=' + programID + '&copyProjectID=' + copyProjectID);
}

/**
 * Add new product.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function addNewProduct(obj)
{
    if($(obj).attr('checked'))
    {
        /* Hide product and plan dropdown controls. */
        $('#productsBox .row .col-sm-4').addClass('hidden');
        $('#productsBox .row .col-sm-4 .input-group').find('select').attr('disabled', true).trigger("chosen:updated");
        $('#plansBox').closest('tr').addClass('hidden');
        $('#plansBox .col-sm-4').find('select').attr('disabled', true).trigger("chosen:updated");

        /* Displays the input box for creating a product. */
        $("[name^='newProduct']").prop('checked', true);
        $('#productName').removeAttr('disabled', true);
        $('#productsBox .addProduct').removeClass('hidden');
        $('#productTitle').html(productName);
    }
    else
    {
        /* Show product and product dropdown controls. */
        $('#productsBox .row .col-sm-4').removeClass('hidden');
        $('#productsBox .row .col-sm-4 .input-group').find('select').removeAttr('disabled').trigger("chosen:updated");
        $('#plansBox').closest('tr').removeClass('hidden');
        $('#plansBox .col-sm-4').find('select').removeAttr('disabled', true).trigger("chosen:updated");

        /* Hide the input box for creating a product. */
        $("[name^='newProduct']").prop('checked', false);
        $('#productName').attr('disabled', true);
        $('#productsBox .addProduct').addClass('hidden');

        $('#productTitle').html(manageProducts);
    }
    $('#productsBox div + .text-danger.help-text').remove();
}

/**
 * Set access control box.
 *
 * @param  int $programID
 * @access public
 * @return void
 */
function setAclList(programID)
{
    if(programID != 0)
    {
        $('.aclBox').html($('#programAcl').html());
    }
    else
    {
        $('.aclBox').html($('#projectAcl').html());
    }
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
    $("#productsBox select[name^='products']").each(function()
    {
        var $product = $(product);
        if($product.val() != 0 && $product.val() == $(this).val() && $product.attr('id') != $(this).attr('id') && !multiBranchProducts[$product.val()])
        {
            bootbox.alert(errorSameProducts);
            $product.val(0);
            $product.trigger("chosen:updated");
            return false;
        }
    });

    if($('#productsBox .row .input-group:last select:first').val() != 0)
    {
        var length = $('#productsBox .row .input-group').size();
        var $html  = $('#productsBox .row .col-sm-4:last').html().replace('required', '');
        $('#productsBox .row .col-sm-4:last').find('.input-group-addon').remove();
        $('#productsBox .row').append('<div class="col-sm-4">' + $html + '</div>');
        if($('#productsBox .row .input-group:last select').size() >= 2) $('#productsBox .row .input-group:last select:last').remove();
        $('#productsBox .row .input-group:last .chosen-container').remove();
        $('#productsBox .row .input-group:last select:first').attr('name', 'products[' + length + ']').attr('id', 'products' + length);
        $('#productsBox .row .input-group:last .chosen').chosen();

        $('[data-toggle="popover"]').popover();

        adjustProductBoxMargin();
    }

    var $inputgroup = $(product).closest('.input-group');
    if($inputgroup.find('select').size() >= 2) $inputgroup.removeClass('has-branch').find('select:last').remove();
    if($inputgroup.find('.chosen-container').size() >= 2) $inputgroup.find('.chosen-container:last').remove();

    var index       = $inputgroup.find('select:first').attr('id').replace('products' , '');
    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + $(product).val() + "&oldBranch=0&param=active"), function(data)
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
 * Load plans.
 *
 * @param  obj $product
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

    $.get(createLink('product', 'ajaxGetPlans', "productID=" + productID + '&branch=0,' + branchID + '&planID=0&fieldID&needCreate=&expired=unexpired,noclosed&param=skipParent,multiple'), function(data)
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
            $('#productsBox .row .col-sm-4:lt(' + (i * 3) + ')').css('margin-bottom', '10px');
            $('#productsBox .row .col-sm-4').eq(i * 3).css('padding-right', '6px');
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
            $('#plansBox .col-sm-4').eq(j * 3).css('padding-right', '6px');
        }
    }
}

/**
 * Fuzzy search projects by project name.
 *
 * @access public
 * @return void
 */
$('#projectName').on('keyup', function()
{
    var name = $(this).val();
    name = name.replace(/\s+/g, '');
    link = createLink('project', 'ajaxGetCopyProjects');
    $.post(link, {name: name, cpoyProjectID: copyProjectID, model: model}, function(data)
    {
        $('#copyProjects').html(data);
        $('#copyProjects a').click(function()
        {
            setCopyProject($(this).data('id'));
            $('#copyProjectModal').modal('hide');
        });
    })
})

/* Click remove tips.  */
$("#name").click(function()
{
    $('#name').removeClass('has-info');
    $('#nameLabelInfo').remove();
});
$("#code").click(function()
{
    $('#code').removeClass('has-info');
    $('#codeLabelInfo').remove();
});
$("#end").click(function()
{
    $('#end').removeClass('has-info');
    $('#endLabelInfo').remove();
});
$("#days").click(function()
{
    $('#days').removeClass('has-info');
    $('#daysLabelInfo').remove();
});
$("#endList input[type=radio]").click(function()
{
    $('#end').removeClass('has-info');
    $('#endLabelInfo').remove();
    $('#days').removeClass('has-info');
    $('#daysLabelInfo').remove();
});
