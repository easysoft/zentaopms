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

/**
 * Load branches.
 *
 * @param  event  $e
 * @access public
 * @return void
 */
function loadBranches(e)
{
    /* When selecting a product, delete a plan that is empty by default. */
    $("#planDefault").remove();

    let chosenProducts = [];
    let $product       = $(e.target);
    $("[name^='products']").each(function()
    {
        let productID = $(this).val();
        if(productID > 0 && chosenProducts.indexOf(productID) == -1) chosenProducts.push(productID);
        if($product.val() != 0 && $product.val() == $(this).val() && $product.attr('id') != $(this).attr('id'))
        {
            zui.Modal.alert(errorSameProducts);
            $product.zui('picker').$.setValue(0);
            return false;
        }
    });

    let $formRow  = $product.closest('.form-row');
    let index     = $formRow.find("[name^='products']").first().attr('name').match(/\d+/)[0];
    let oldBranch = $(e.target).attr('data-branch') !== undefined ? $product.attr('data-branch') : 0;

    if(!multiBranchProducts[$product.val()])
    {
        $formRow.find('.form-group').last().find('select').val('');
        $formRow.find('.form-group').eq(0).addClass('w-1/2').removeClass('w-1/4');
        $formRow.find('.form-group').eq(1).addClass('hidden');
    }

    $.getJSON($.createLink('branch', 'ajaxGetBranches', "productID=" + $product.val() + "&oldBranch=" + oldBranch + "&param=active&projectID=" + projectID + "&withMainBranch=true"), function(data)
    {
        if(data.length > 0)
        {
            $formRow.find('.form-group').eq(0).addClass('w-1/4').removeClass('w-1/2');
            $formRow.find('.form-group').eq(1).removeClass('hidden');
            const $branchPicker = $formRow.find('select[name^=branch]').zui('picker');
            $branchPicker.render({items: data, multiple: true});
        }
    });

    let branch = $('#branch' + index);
    loadPlans(e.target, branch);
}

/**
 * Load plans.
 *
 * @param  obj $product
 * @param  obj $branchID
 * @access public
 * @return void
 */
window.loadPlans = function(product, branch)
{
    let productID = $(product).val();
    let branchID  = $(branch).val() == null ? 0 : '0,' + $(branch).val();
    let planID    = $(product).attr('data-plan') !== undefined ? $(product).attr('data-plan') : 0;
    let index     = $(product).attr('name').match(/\d+/)[0];

    $.get($.createLink('product', 'ajaxGetPlans', "productID=" + productID + '&branch=' + branchID + '&planID=' + planID + '&fieldID&needCreate=&expired=unexpired,noclosed&param=skipParent,multiple'), function(data)
    {
        if(data)
        {
            data = JSON.parse(data);

            $("div#plan" + index).find('.picker-box').empty();
            $("div#plan" + index).find('.picker-box').append(`<div id='plans${productID}'></div>`);

            new zui.Picker(`#plans${productID}`, {
                items: data,
                multiple: true,
                name: `plans[${productID}][]`,
            });
        }
    });
}

/**
 * Add new line for link product.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function addNewLine(e)
{
    const obj     = e.target
    const newLine = $(obj).closest('.form-row').clone();

    let index   = 0;
    let options = zui.Picker.query("[name^='products']").options;

    /* 将已有产品下拉的最大name属性的值加1赋值给新行. */
    $("[name^='products']").each(function()
    {
        let id = $(this).attr('name').replace(/[^\d]/g, '');

        id = parseInt(id);
        id ++;

        index = id > index ? id : index;
    })

    /* 处理新一行控件的显示/隐藏，宽度/是否居中等样式问题. */
    newLine.addClass('newLine');
    newLine.find('.form-label').html('');
    newLine.find('.form-group').eq(0).addClass('w-1/2').removeClass('w-1/4');
    newLine.find('.form-group').eq(1).addClass('hidden');
    newLine.find("div[id^='plan']").attr('id', 'plan' + index);
    newLine.find('.linkProduct > .form-label').html('').removeClass('required');
    newLine.find('.removeLine').removeClass('hidden');


    $(obj).closest('.form-row').after(newLine);

    /* 重新初始化新一行的下拉控件. */
    newLine.find('.form-group').eq(0).find('.picker-box').empty();
    newLine.find('.form-group').eq(0).find('.picker-box').append(`<div id=products${index}></div>`);

    newLine.find('div[id^=plan] .picker-box').empty();
    newLine.find('div[id^=plan] .picker-box').append(`<div id=plans${index}></div>`);

    options.name         = `products[${index}]`;
    options.defaultValue = '';
    new zui.Picker(`#products${index}`, options);

    new zui.Picker(`#plans${index}`, {
        items:[],
        multiple: true,
        name: `plans[${index}]`,
    });
}

/**
 * Remove line for link product.
 *
 * @param  obj    e
 * @access public
 * @return void
 */
function removeLine(e)
{
    const obj = e.target;
    $(obj).closest('.form-row').remove();
}
