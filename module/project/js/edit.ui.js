/* 切换项目管理模型的逻辑. */
$(document).on('click', '.dropdown-menu .menu-item', function()
{
    let text  = $(this).find('.model-drop').attr('data-value');
    let model = $(this).find('.model-drop').attr('data-key');

    const btnClass = labelClass[model];

    $('#project-model .text').text(text);
    $('#project-model').removeClass('secondary-outline special-outline warning-outline');
    $('#project-model').addClass(btnClass);
    $('#model').val(model);
})


window.productChange = function(e)
{
    loadBranches(e.target);

    let current    = $(e.target).val();
    let last       = $(e.target).attr('last');
    let lastBranch = $(e.target).attr('data-lastBranch');

    $(e.target).attr('data-last', current);

    let $branch = $(e.target).closest('.has-branch').find("[name^='branch']");
    if($branch.length)
    {
        let branchID = $branch.val();
        $(e.target).attr('data-lastBranch', branchID);
    }
    else
    {
        $(e.target).removeAttr('data-lastBranch');
    }

    if(current != last && unmodifiableProducts.includes(last))
    {
        if(lastBranch != 0)
        {
            if(unmodifiableBranches.includes(lastBranch)) zui.Modal.alert(unLinkProductTip.replace("%s", allProducts[last] + branchGroups[last][lastBranch]));
        }
        else
        {
            zui.Modal.alert(unLinkProductTip.replace("%s", allProducts[last]));
        }
    }

    let chosenProducts = 0;
    $(".productsBox select[name^='products']").each(function()
    {
        if($(e.target).val() > 0) chosenProducts ++;
    });

    if(chosenProducts > 1)  $('.stageBy').removeClass('hide');
    if(chosenProducts <= 1) $('.stageBy').addClass('hide');
}

window.branchChange = function(e)
{
    let current = $(e.target).val();
    let last    = $(e.target).attr('data-last');
    $(e.target).attr('data-last', current);

    let $product = $(e.target).closest('.form-row').find("[name^='products']");
    $product.attr('data-lastBranch', current);

    loadPlans($product, $(e.target));

    if(unmodifiableBranches.includes(last))
    {
        let productID = $product.val();
        if(unmodifiableBranches.includes(productID))
        {
            if((last == 0 && unmodifiableMainBranches[productID]) || last != 0)
            {
                zui.Modal.alert(unLinkProductTip.replace("%s", branchGroups[productID][last]));
            }
        }
    }
}
