$(function()
{
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
    let current     = $(e.target).val();
    let $productBox = $(e.target).closest('.picker-box');
    let last        = $productBox.attr('last');
    let lastBranch  = $productBox.attr('lastBranch');
    $productBox.attr('last', current);

    let $branch = $(e.target).closest('.productBox').find("[name^='branch']");
    if($branch.val())
    {
        $productBox.attr('lastBranch', $branch.val());
    }
    else
    {
        $productBox.removeAttr('lastBranch');
    }

    if(current != last && unmodifiableProducts.includes(Number(last)))
    {
        if(lastBranch != undefined && lastBranch != 0)
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
    let current    = $(e.target).val();
    let $productBox = $(e.target).closest('.productBox ').find('.linkProduct .picker-box');
    let last       = $productBox.attr('lastBranch').split(',');
    let changed    = last.filter(item => !current.includes(item));
    $productBox.attr('lastBranch', current);

    if(changed.length > 0 && unmodifiableBranches.includes(parseInt(changed[0])))
    {
        let productID = $productBox.find('input').val();
        if(unmodifiableProducts.includes(productID))
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
