$(function()
{
    if($('#typeHover').length) new zui.Tooltip('#typeHover', {title: typeTip, trigger: 'hover', placement: 'right', type: 'white', 'className': 'text-gray border border-light'});

    if(isWaterfall) hidePlanBox(executionAttr);
});

/**
 * Change product interaction.
 *
 * @access public
 * @return void
 */
function productChange(e)
{
    loadBranches(e);

    let $product   = $(e.target);
    let current    = $product.val();
    let last       = $product.attr('last');
    let lastBranch = $product.attr('lastBranch');

    $product.attr('data-last', current);

    let $branch = $product.closest('.productBox').find("[name^='branch']");
    if($branch.val())
    {
        $product.attr('lastBranch', $branch.val());
    }
    else
    {
        $product.removeAttr('lastBranch');
    }

    if(current != last && unmodifiableProducts.includes(Number(last)))
    {
        if(lastBranch != 0)
        {
            if(unmodifiableBranches.includes(Number(lastBranch)))
            {
                if(linkedStoryIDList[last][lastBranch]) zui.Modal.alert(unLinkProductTip.replace("%s", allProducts[last] + branchGroups[last][lastBranch]));
            }
        }
        else
        {
            zui.Modal.alert(unLinkProductTip.replace("%s", allProducts[last]));
        }
    }
}

/**
 * Change branch interaction.
 *
 * @access public
 * @return void
 */
function branchChange(e)
{
    let $branch = $(e.target);
    let current = $branch.val();
    let last    = $branch.attr('data-last');
    $branch.attr('data-last', current);

    let $product = $branch.closest('.form-row').find("[name^='products']");
    $product.attr('lastBranch', current);
    loadPlans($product, $branch);

    if(unmodifiableBranches.includes(last))
    {
        let productID = $product.val();
        if(unmodifiableBranches.includes(productID) && linkedStoryIDList[productID][last])
        {
            zui.Modal.alert(tip.replace('%s', linkedStoryIDList[productID][last]));
        }
    }
}

/**
 * Change project interaction.
 *
 * @access public
 * @return void
 */
function changeProject(e)
{
    let projectID = $(e.target).val();
    if($('#syncStories').length == 0) $('button[type=submit]').after("<input type='hidden' id='syncStories' name='syncStories' value='no' />");

    zui.Modal.confirm(confirmSync).then((res) =>
    {
        if(res)
        {
            $("#syncStories").val('yes');
            lastProjectID = projectID;
        }
        else
        {
            $("#syncStories").val('no');
            $('#project').val(lastProjectID);
        }
    });
};
