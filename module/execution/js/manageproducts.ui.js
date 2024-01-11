window.checkUnlink = function()
{
    const $elem = $(this);
    if($elem.prop('checked')) return true;

    const productID = +$elem.val();
    if(unmodifiableProducts.includes(productID))
    {
        const $branch = $elem.closest('.product-block').find('.pick-value[name^=branch]');
        if($branch.length)
        {
            const branchID = +$branch.val();
            if(unmodifiableBranches.includes(branchID) && linkedStoryIDList[productID][branchID])
            {
                zui.Modal.alert(unLinkProductTip.replace("%s", allProducts[productID] + branchGroups[productID][branchID]));
            }
        }
        else
        {
            zui.Modal.alert(unLinkProductTip.replace("%s", allProducts[productID]));
        }
    }
}
