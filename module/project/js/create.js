$(function()
{
    $(document).on('change', '[name=hasProduct]', function()
    {
        const hasProduct = $('[name=hasProduct]:checked').val();

        $('#productTitle, #linkPlan').closest('tr').toggle(hasProduct == 1);

        if(hasProduct == 0) $('.division').addClass('hide');

        if(hasProduct == 1)
        {
            var chosenProducts = 0;
            $(".productsBox select[name^='products']").each(function()
            {
                if($(this).val() > 0) chosenProducts ++;
            });
            if(chosenProducts > 1) $('.division').removeClass('hide');
        }
    })

    $('[name=hasProduct]').change();

    $('#copyProjects a').click(function()
    {
        setCopyProject($(this).data('id'));
        $('#copyProjectModal').modal('hide')
    });

    setAclList($("#parent").val());

    if(typeof(currentPlanID) == 'undefined' && copyProjectID == 0)
    {
        $('.productsBox select[id^="products"]').each(function()
        {
            var branch = 0;
            if($(this).closest('.table-row').find('select[name^="branch"]').size() > 0)
            {
                var branch = $(this).closest('.table-row').find('select[name^="branch"]');
            }
            loadPlans($(this), branch);
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
        $(".productsBox select[name^='products']").each(function()
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
    disableSelectedProduct();

    /* Check the all products and branches control when uncheck the product. */
    $(document).on('change', "select[id^='products']", function()
    {
        if($(this).val() == 0)
        {
            disableSelectedProduct();
        }
    });

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

    $("[name='multiple']").change(function()
    {
        $('#endList #delta999').closest('.radio-inline').toggle($(this).val() != 0);
        if($('#endList #delta999').prop('checked') && $(this).val() == 0)
        {
            $('#endList #delta999').prop('checked', false);
            $('#dateBox #end').val('');
            $('#daysBox').removeClass('hidden');
        }
    })

    var hasProduct = $('[name=hasProduct]:checked').val();
    if(hasProduct == 0) $('.productsBox').parent().addClass('hidden')
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
        selectedParent     = parentProgram != 0 ? data.selectedProgramPath[1] : 0;
        lastSelectedParent = lastSelectedID != 0 ? data.objectPath[1] : 0;

        var hasProduct = $('[name=hasProduct]:checked').val();

        if((selectedParent != lastSelectedParent) && hasProduct == 1)
        {
            $('#budget').val('');

            var productSelectHtml = data.allProducts;
            var planSelectHtml    = data.plans;

            $('.productsBox .row .col-sm-6 .input-group').each(function(index)
            {
                var selectedProduct = $(this).find('[name^=products]').val();
                var selectedBranch  = $(this).find('[name^=branch]').val();
                var selectedPlan    = $('#plan' + index ).find('[name^=plans]').val();

                $(this).find('[name^=products]').html(productSelectHtml);
                $(this).find('[name^=products]').attr('name', 'products[' + index + ']').attr('id', 'products' + index).attr('data-branch', selectedBranch).attr('data-plan', selectedPlan);
                $(this).find('[name^=products]').val(selectedProduct).chosen().change();
            });
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
        $('.newLine').addClass('hidden');
        $('.productsBox .row').addClass('hidden');
        $('.productsBox .row .input-group').find('select').attr('disabled', true).trigger("chosen:updated");
        $('.division').addClass('hide');

        /* Displays the input box for creating a product. */
        $(obj).closest('td').attr('colspan', 1);
        $("[name^='newProduct']").prop('checked', true);
        $('#productName').removeAttr('disabled', true);
        $('.productsBox .addProduct').removeClass('hidden');
        $('#productTitle').html(productName);
    }
    else
    {
        /* Show product and product dropdown controls. */
        $('.newLine').removeClass('hidden');
        $('.productsBox .row').removeClass('hidden');
        $('.productsBox .row .input-group').find('select').removeAttr('disabled').trigger("chosen:updated");
        if($('.productsBox').find("select[name^=products]").length > 1) $('.division').removeClass('hide');

        /* Hide the input box for creating a product. */
        $(obj).closest('td').attr('colspan', 3);
        $("[name^='newProduct']").prop('checked', false);
        $('#productName').attr('disabled', true);
        $('.productsBox .addProduct').addClass('hidden');

        $('#productTitle').html(manageProductPlan);
    }

    $('.productsBox div + .text-danger.help-text').remove();
    disableSelectedProduct();
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
    /* When selecting a product, delete a plan that is empty by default. */
    $("#planDefault").remove();

    var chosenProducts = [];
    $(".productsBox select[name^='products']").each(function()
    {
        var $product  = $(product);
        var productID = $(this).val();
        if(productID > 0 && chosenProducts.indexOf(productID) == -1) chosenProducts.push(productID);
        if($product.val() != 0 && $product.val() == $(this).val() && $product.attr('id') != $(this).attr('id') && !multiBranchProducts[$product.val()])
        {
            bootbox.alert(errorSameProducts);
            $product.val(0);
            $product.trigger("chosen:updated");
            return false;
        }
    });

    (chosenProducts.length > 1 && (model == 'waterfall' || model == 'waterfallplus')) ? $('.division').removeClass('hide') : $('.division').addClass('hide');

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

    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + $(product).val() + "&oldBranch=" + oldBranch + "&param=active"), function(data)
    {
        if(data)
        {
            $tableRow.find("select[name^='branch']").replaceWith(data);
            $tableRow.find('.table-col:last .chosen-container').remove();
            $tableRow.find('.table-col:last').removeClass('hidden');
            $tableRow.find("select[name^='branch']").attr('multiple', '').attr('name', 'branch[' + index + '][]').attr('id', 'branch' + index).attr('onchange', "loadPlans('#products" + index + "', this)").chosen();

            disableSelectedProduct();
        }

        var branch = $('#branch' + index);
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
    if($("select[name^='products']").length < 2) $('.division').addClass('hide');
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
