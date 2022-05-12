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
            $(this).siblings('div').find('span').attr('title', tip);
        }
    });

    $("#productsBox select[name^='branch']").each(function()
    {
        var isExistedBranch = $.inArray($(this).attr('data-last'), unmodifiableBranches);
        if(isExistedBranch != -1)
        {
            var $product = $(this).closest('.has-branch').find("[name^='products']");
            if($.inArray($product.val(), unmodifiableProducts) != -1)
            {
                $(this).prop('disabled', true).trigger("chosen:updated");
                $product.prop('disabled', true).trigger("chosen:updated");
                $product.siblings('div').find('span').attr('title', tip);
            }
        }
    });
})

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
        $('#project').val(projectID).trigger("chosen:updated");
        console.log($('#project').val(projectID));
    }
};
