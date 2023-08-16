$().ready(function()
{
    $('#submit').click(function()
    {
        $('#products0').removeAttr("disabled");
        $('#branch0').removeAttr("disabled");
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
});

$(function()
{
    $(document).on('change', '[name*=products]', function()
    {
        var current    = $(this).val();
        var last       = $(this).attr('data-last');
        var lastBranch = $(this).attr('data-lastBranch') !== undefined ? $(this).attr('data-lastBranch') : 0;

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
                if($.inArray(lastBranch, unmodifiableBranches) != -1)
                {
                    if(linkedStoryIDList[last][lastBranch]) bootbox.alert(unLinkProductTip.replace("%s", allProducts[last] + branchGroups[last][lastBranch]));
                }
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
            if($.inArray(productID, unmodifiableProducts) != -1 && linkedStoryIDList[productID][last])
            {
                bootbox.alert(tip.replace('%s', linkedStoryIDList[productID][last]));
            }
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

    if($('.disabledBranch').length > 0)
    {
        $('.disabledBranch div[id^="branch"]').addClass('chosen-disabled');
    }

    $('[data-toggle="popover"]').popover();

    if(isWaterfall)
    {
        $('#attribute').change(function()
        {
            var attribute = $(this).val();
            hidePlanBox(attribute);
        })

        $('#attribute').change();
    }
})
var lastProjectID = $("#project").val();

function changeProject(projectID)
{
    if($('#submit').closest('td').find('#syncStories').length == 0)
    {
        $('#submit').after("<input type='hidden' id='syncStories' name='syncStories' value='no' />");
    }

    var confirmVal = confirm(confirmSync);
    $("#syncStories").val(confirmVal ? 'yes' : 'no');

    if(!confirmVal)
    {
        $('#project').val(lastProjectID).trigger("chosen:updated");
        return false;
    }

    lastProjectID = projectID;
};
