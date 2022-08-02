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
    /* If the story of the product which linked the execution under the project, you don't allow to remove the product. */
    $("#productsBox select[name^='products']").each(function()
    {
        var isExistedProduct = $.inArray($(this).attr('data-last'), unmodifiableProducts);
        var productType      = $(this).attr('data-type');
        if(isExistedProduct != -1 && productType == 'normal')
        {
            $(this).prop('disabled', true).trigger("chosen:updated");

            var productTip = tip.replace('%s', linkedStoryIDList[$(this).attr('data-last')][0]);
            $(this).siblings('div').find('span').attr('title', productTip);
        }
    });

    $("#productsBox select[name^='branch']").each(function()
    {
        var isExistedBranch = $.inArray($(this).attr('data-last'), unmodifiableBranches);
        if(isExistedBranch != -1)
        {
            var $product = $(this).closest('.has-branch').find("[name^='products']");
            if($.inArray($product.val(), unmodifiableProducts) != -1 && linkedStoryIDList[$product.val()][$(this).attr('data-last')])
            {
                $(this).prop('disabled', true).trigger("chosen:updated");
                $product.prop('disabled', true).trigger("chosen:updated");

                var productTip = tip.replace('%s', linkedStoryIDList[$product.val()][$(this).attr('data-last')]);
                $product.siblings('div').find('span').attr('title', productTip);
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
