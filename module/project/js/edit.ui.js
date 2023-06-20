/* 切换项目管理模型的逻辑. */
$(document).on('click', '.dropdown-menu .menu-item', function()
{
    let text  = $(this).find('.model-drop').attr('data-value');
    let model = $(this).find('.model-drop').attr('data-key');
    $('#project-model .text').text(text);
    $('#model').val(model);
})

$(document).on('change', '[name*=products]', function()
{
    loadBranches(this);

    let current    = $(this).val();
    let last       = $(this).attr('last');
    let lastBranch = $(this).attr('data-lastBranch');

    $(this).attr('data-last', current);

    let $branch = $(this).closest('.has-branch').find("[name^='branch']");
    if($branch.length)
    {
        let branchID = $branch.val();
        $(this).attr('data-lastBranch', branchID);
    }
    else
    {
        $(this).removeAttr('data-lastBranch');
    }

    if(current != last && unmodifiableProducts.includes(last))
    {
        if(lastBranch != 0)
        {
            if(unmodifiableBranches.includes(lastBranch)) bootbox.alert(unLinkProductTip.replace("%s", allProducts[last] + branchGroups[last][lastBranch]));
        }
        else
        {
            bootbox.alert(unLinkProductTip.replace("%s", allProducts[last]));
        }
    }

    let chosenProducts = 0;
    $(".productsBox select[name^='products']").each(function()
    {
        if($(this).val() > 0) chosenProducts ++;
    });

    if(chosenProducts > 1)  $('.stageBy').removeClass('hide');
    if(chosenProducts <= 1) $('.stageBy').addClass('hide');
});

$(document).on('change', '[name*=branch]', function()
{
    let current = $(this).val();
    let last    = $(this).attr('data-last');
    $(this).attr('data-last', current);

    let $product = $(this).closest('.form-row').find("[name^='products']");
    $product.attr('data-lastBranch', current);

    loadPlans($product, $(this));

    if(unmodifiableBranches.includes(last))
    {
        let productID = $product.val();
        if(unmodifiableBranches.includes(productID))
        {
            if((last == 0 && unmodifiableMainBranches[productID]) || last != 0)
            {
                bootbox.alert(unLinkProductTip.replace("%s", branchGroups[productID][last]));
            }
        }
    }
})

/**
 * Load branches.
 *
 * @param  int $product
 * @access public
 * @return void
 */
function loadBranches(product)
{
    /* When selecting a product, delete a plan that is empty by default. */
    $("#planDefault").remove();

    let chosenProducts = [];
    $("select[name^='products']").each(function()
    {
        let $product  = $(product);
        let productID = $(this).val();
        if(productID > 0 && chosenProducts.indexOf(productID) == -1) chosenProducts.push(productID);
        if($product.val() != 0 && $product.val() == $(this).val() && $product.attr('id') != $(this).attr('id') && !multiBranchProducts[$product.val()])
        {
            bootbox.alert(errorSameProducts);
            $product.val(0);
            $product.trigger("chosen:updated");
            return false;
        }
    });

    (chosenProducts.length > 1 && (model == 'waterfall' || model == 'waterfallplus')) ? $('.stageBy').removeClass('hide') : $('.stageBy').addClass('hide');

    let $formRow  = $(product).closest('.form-row');
    let index     = $formRow.find('select').first().attr('id').match(/\d+/)[0];
    let oldBranch = $(product).attr('data-branch') !== undefined ? $(product).attr('data-branch') : 0;

    if(!multiBranchProducts[$(product).val()])
    {
        $formRow.find('.form-group').last().find('select').val('').trigger('chosen:updated');
        $formRow.find('.form-group').eq(1).addClass('hidden');
    }

    $.get($.createLink('branch', 'ajaxGetBranches', "productID=" + $(product).val() + "&oldBranch=" + oldBranch + "&param=active"), function(data)
    {
        if(data)
        {
            $formRow.find("select[name^='branch']").replaceWith(data);
            $formRow.find('.form-group').eq(1).removeClass('hidden');
            $formRow.find("select[name^='branch']").attr('multiple', '').attr('name', 'branch[' + index + '][]').attr('id', 'branch' + index);
        }

        let branch = $('#branch' + index);
        loadPlans(product, branch);
    });
}

/**
 * Load plans.
 *
 * @param  obj $product
 * @param  obj $branchID
 * @access public
 * @return void
 */
function loadPlans(product, branch)
{
    let productID = $(product).val();
    let branchID  = $(branch).val() == null ? 0 : '0,' + $(branch).val();
    let planID    = $(product).attr('data-plan') !== undefined ? $(product).attr('data-plan') : 0;
    let index     = $(product).attr('id').match(/\d+/)[0];

    $.get($.createLink('product', 'ajaxGetPlans', "productID=" + productID + '&branch=' + branchID + '&planID=' + planID + '&fieldID&needCreate=&expired=unexpired,noclosed&param=skipParent,multiple'), function(data)
    {
        if(data)
        {
            $("div#plan" + index).find("select[name^='plans']").replaceWith(data);
            $("div#plan" + index).find('.chosen-container').remove();
            $("div#plan" + index).find('select').attr('name', 'plans[' + productID + ']' + '[]').attr('id', 'plans' + productID);
        }
    });
}

/**
 * Add new line for link product.
 *
 * @param  obj e 
 * @access public
 * @return void
 */
function addNewLine(e)
{
    const obj     = e.target
    const newLine = $(obj).closest('.form-row').clone();
    let index = 0;

    newLine.find('.addLine').on('click', addNewLine);
    newLine.find('.removeLine').on('click', removeLine);

    $("select[name^='products']").each(function()
    {
        let id = $(this).attr('id').replace('products' , '');

        id = parseInt(id);
        id ++;

        index = id > index ? id : index;
    })

    newLine.addClass('newLine');
    newLine.find('.form-label').html('');
    newLine.find('.removeLine').removeClass('disabled');
    newLine.find('.chosen-container').remove();
    newLine.find("select[name^='products']").attr('name', 'products[' + index + ']').attr('id', 'products' + index);
    newLine.find("select[name^='plans']").attr('name', 'plans[' + index + '][' + 0 + '][]');
    newLine.find("div[id^='plan']").attr('id', 'plan' + index);

    $(obj).closest('.form-row').after(newLine);
    let product = newLine.find("select[name^='products']");
    let branch  = newLine.find("select[name^='branch']");
}

/**
 * Remove line for link product.
 *
 * @param  obj e 
 * @access public
 * @return void
 */
function removeLine(e)
{
    const obj = e.target

    /* Dsiabled btn can't remove line. */
    if($(obj).closest('.btn').hasClass('disabled')) return false;

    $(obj).closest('.form-row').remove();

    let chosenProducts = 0;
    $("select[name^='products']").each(function()
    {
      if($(this).val() > 0) chosenProducts ++;
    });

    (chosenProducts.length > 1 && (model == 'waterfall' || model == 'waterfallplus')) ? $('.stageBy').removeClass('hide') : $('.stageBy').addClass('hide');
}
