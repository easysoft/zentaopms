$(function()
{
    new zui.Tooltip('#programHover', {title: programTip, trigger: 'hover', placement: 'right', type: 'white', 'className': 'text-gray border border-light programTip'});
    new zui.Tooltip('#stageByHover', {title: stageByTip, trigger: 'hover', placement: 'right', type: 'white', 'className': 'text-gray border border-light programTip'});

    setWhite();
});

/* 切换项目管理模型的逻辑. */
$(document).on('click', '.model-drop', function()
{
    let text  = $(this).find('.listitem').attr('data-value');
    let model = $(this).find('.listitem').attr('data-key');

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
    let lastBranch = $(e.target).attr('lastBranch');

    $(e.target).attr('data-last', current);

    let $branch = $(e.target).closest('.productBox').find("[name^='branch']");
    if($branch.val())
    {
        $(e.target).attr('lastBranch', $branch.val());
    }
    else
    {
        $(e.target).removeAttr('lastBranch');
    }

    if(current != last && unmodifiableProducts.includes(Number(last)))
    {
        if(lastBranch != 0)
        {
            if(unmodifiableBranches.includes(Number(lastBranch))) zui.Modal.alert(unLinkProductTip.replace("%s", allProducts[last] + branchGroups[last][lastBranch]));
        }
        else
        {
            zui.Modal.alert(unLinkProductTip.replace("%s", allProducts[last]));
        }
    }

    let chosenProducts = 0;
    $(".productsBox [name^='products']").each(function()
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
    $product.attr('lastBranch', current);

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

/**
 * Set acl list when change program.
 *
 * @access public
 * @return void
 */
window.setParentProgram = function()
{
    const programID = $('[name=parent]').val();
    const link      = $.createLink('project', 'edit', `projectID=${projectID}&from=${from}&pgoramID=${programID}`) ;
    loadPage(link, '#aclList');
    $('select[name^=whitelist]').closest('.form-row').removeClass('hidden')
}
