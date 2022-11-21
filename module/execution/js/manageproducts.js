$(function()
{
    $('#productsBox input:checkbox').each(function()
    {
        var $cb = $(this);
        if($cb.prop('checked')) $cb.closest('.product').addClass('checked');
    });

    $('#productsBox input:checkbox').change(function()
    {
        var $cb = $(this);
        $cb.closest('.product').toggleClass('checked', $cb.prop('checked'));

        if($cb.prop('checked')) return true;

        var productID = String($cb.val());
        if($.inArray(productID, unmodifiableProducts) != -1)
        {
            var $branch = $cb.closest('.product').find('[name^=branch]');
            if($branch.length)
            {
                var branchID = String($branch.val());

                if($.inArray(branchID, unmodifiableBranches) != -1 && linkedStoryIDList[productID][branchID])
                {
                    bootbox.alert(unLinkProductTip.replace("%s", allProducts[productID] + branchGroups[productID][branchID]));
                }
            }
            else
            {
                bootbox.alert(unLinkProductTip.replace("%s", allProducts[productID]));
            }
        }
    });

    $("select[id^=branch]").change(function()
    {
        var checked = $(this).closest('div').hasClass('checked');
        if(!checked)
        {
            $(this).closest('div').addClass('checked');
            $(this).closest('div').find("input[id^=products]").prop('checked', true);
        }
    });
});
