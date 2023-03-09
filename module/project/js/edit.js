$(function()
{
    $('#parent').change(function()
    {
        var programID      = $(this).val();
        var lastSelectedID = $('#parent').attr('data-lastSelected');

        setAclList(programID);

        $.get(createLink('project', 'ajaxGetObjectInfo', 'objectType=program&objectID=' + lastSelectedID + "&selectedProgramID=" + programID), function(data)
        {
            var data = JSON.parse(data);
            selectedParent     = programID != 0 ? data.selectedProgramPath[1] : 0;
            lastSelectedParent = lastSelectedID != 0 ? data.objectPath[1] : 0;

            if(selectedParent != lastSelectedParent)
            {
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
        });
    });

    $(document).on('change', '[name*=products]', function()
    {
        var current    = $(this).val();
        var last       = $(this).attr('data-last');
        var lastBranch = $(this).attr('data-lastBranch');

        $(this).attr('data-last', current);

        var $branch = $(this).closest('.has-branch').find("[name^='branch']");
        if($branch.length)
        {
            var branchID = $branch.val();
            $(this).attr('data-lastBranch', branchID);
        }
        else
        {
            $(this).removeAttr('data-lastBranch');
        }

        if(current != last && $.inArray(last, unmodifiableProducts) != -1)
        {
            if(lastBranch != 0)
            {
                if($.inArray(lastBranch, unmodifiableBranches) != -1) bootbox.alert(unLinkProductTip.replace("%s", allProducts[last] + branchGroups[last][lastBranch]));
            }
            else
            {
                bootbox.alert(unLinkProductTip.replace("%s", allProducts[last]));
            }
        }
        var chosenProducts = 0;
        $(".productsBox select[name^='products']").each(function()
          {
            if($(this).val() > 0) chosenProducts ++;
          });
        if(chosenProducts > 1)  $('.division').removeClass('hide');
        if(chosenProducts <= 1) $('.division').addClass('hide');
    });

    $(document).on('change', '[name*=branch]', function()
    {
        var current = $(this).val();
        var last    = $(this).attr('data-last');
        $(this).attr('data-last', current);

        var $product = $(this).closest('.has-branch').find("[name^='products']");
        $product.attr('data-lastBranch', current);

        if($.inArray(last, unmodifiableBranches) != -1)
        {
            var productID = $product.val();
            if($.inArray(productID, unmodifiableProducts) != -1)
            {
                if((last == 0 && unmodifiableMainBranches[productID]) || last != 0)
                {
                    bootbox.alert(unLinkProductTip.replace("%s", branchGroups[productID][last]));
                }
            }
        }
    })

   /* If end is longtime, set the default date to today */
   var today = $.zui.formatDate(new Date(), 'yyyy-MM-dd');
   if($('#end').val() == longTime) $('#end').val(today).datetimepicker('update').val(longTime);

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

    /* Init. */
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

    if(requiredFields.indexOf('budget') >= 0 && budget == 0)
    {
        $('#budget').removeAttr('disabled');
        $('td .checkbox-primary').addClass('hidden');
    }
    if(requiredFields.indexOf('budget') >= 0 && budget != 0)
    {
        $('td .checkbox-primary').addClass('hidden');
    }

    $('[data-toggle="popover"]').popover();
});

/**
 * Set aclList.
 *
 * @param  int   $programID
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

    var chosenProducts = 0;
    $(".productsBox select[name^='products']").each(function()
    {
      if($(this).val() > 0) chosenProducts ++;
    });

    (chosenProducts.length > 1 && (model == 'waterfall' || model == 'waterfallplus')) ? $('.division').removeClass('hide') : $('.division').addClass('hide');
}
