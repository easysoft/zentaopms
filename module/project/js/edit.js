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

                $('#productsBox .row .col-sm-4 .input-group').each(function(index)
                {
                    var selectedProduct = $(this).find('[name^=products]').val();
                    var selectedBranch  = $(this).find('[name^=branch]').val();
                    var selectedPlan    = $('#plan' + index ).find('[name^=plans]').val();

                    $(this).html(productSelectHtml);
                    $(this).find('[name^=products]').attr('name', 'products[' + index + ']').attr('id', 'products' + index).attr('data-branch', selectedBranch).attr('data-plan', selectedPlan);
                    $(this).find('[name^=products]').val(selectedProduct).chosen().change();
                });
            }
        });
    });

    adjustProductBoxMargin();
    adjustPlanBoxMargin();

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
