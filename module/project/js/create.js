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
    $("select[id^=branch]").each(nonClickableSelectedBranch);
    nonClickableSelectedProduct();

    /* Check the all products and branches control when uncheck the product. */
    $(document).on('change', "select[id^='products']", function()
    {
        if($(this).val() == 0)
        {
            $("select[id^='branch']").each(nonClickableSelectedBranch);

            nonClickableSelectedProduct();
        }
    });

    $(document).on('change', "select[id^='branch']", nonClickableSelectedBranch);
});

/**
 * Set parent program.
 *
 * @param  $parentProgram
 * @access public
 * @return void
 */
function setParentProgram(parentProgram)
{
    location.href = createLink('project', 'create', 'model=' + model + '&programID=' + parentProgram);
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
 * @param  int $branchID
 * @access public
 * @return void
 */
function loadBranches(product, branchID)
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
        var $html  = $('#productsBox .row .col-sm-4:last').html();
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

    var oldBranchID = typeof(branchID) == 'undefined' ? 0 : branchID;
    var index       = $inputgroup.find('select:first').attr('id').replace('products' , '');
    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + $(product).val() + "&oldBranch=" + oldBranchID + "&param=active"), function(data)
    {
        if(data)
        {
            $inputgroup.addClass('has-branch').append(data);
            $inputgroup.find('select:last').attr('name', 'branch[' + index + ']').attr('id', 'branch' + index).attr('onchange', "loadPlans('#products" + index + "', this.value)").chosen();

            $inputgroup.find('select:last').each(nonClickableSelectedBranch);
            nonClickableSelectedProduct();
        }
    });

    if(!multiBranchProducts[$(product).val()]) nonClickableSelectedProduct();

    loadPlans(product, oldBranchID);
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

    if(productID != 0)
    {
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
    $.post(link, {name: name, cpoyProjectID : copyProjectID}, function(data)
    {
        $('#copyProjects').html(data);
        $('#copyProjects a').click(function()
        {
            setCopyProject($(this).data('id'));
            $('#copyProjectModal').modal('hide');
        });
    })
})
