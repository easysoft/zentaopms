window.checkUnlink = function()
{
    const $elem = $(this);
    if($elem.prop('checked')) return true;

    const productID = +$elem.val();
    if(unmodifiableProducts.includes(productID))
    {
        const $branch = $elem.closest('.product-block').find('[name^=branch]');
        if($branch.length)
        {
            const branchID = +$branch.val();
            if((branchID == BRANCH_MAIN && unmodifiableMainBranches[productID]) || (branchID != BRANCH_MAIN && $.inArray(branchID, unmodifiableBranches) != -1))
            {
                zui.Modal.alert(unLinkProductTip.replace("%s", branchGroups[productID][branchID]));
            }
        }
        else
        {
            zui.Modal.alert(unLinkProductTip.replace("%s", allProducts[productID]));
        }
    }
}
