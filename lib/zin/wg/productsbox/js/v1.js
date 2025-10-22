$(function()
{
    $('.productBox .picker-box').last().on('inited', function(){updateItems();});
});

window.branchChange = function(e)
{
    let $product     = $(e.target).closest('.productBox').find("[name^='products']");
    const objectType = $('.productsBox').data('linkType');

    if(objectType == 'roadmap')
    {
        loadRoadmaps($product, $(e.target));
    }
    else
    {
        loadPlans($product, $(e.target));
    }
}

window.loadBranches = function(e)
{
    /* When selecting a product, delete a plan that is empty by default. */
    $("#planDefault").remove();

    updateItems();

    let $product       = $(e.target);
    let currentProduct = $product.val();
    let $formRow       = $product.closest('.productBox');
    let index          = $formRow.find("[name^='products']").first().attr('name').match(/\d+/)[0];
    let oldBranch      = $(e.target).attr('data-branch') !== undefined ? $product.attr('data-branch') : 0;
    let charterID      = $('[name="charter"]').length ? $('[name="charter"]').val() : 0;
    const objectType   = $('.productsBox').data('linkType');
    if(!multiBranchProducts[currentProduct])
    {
        $formRow.find('.form-group.linkProduct').addClass('w-1/2').removeClass('w-1/4');
        $formRow.find('.form-group.linkBranch').addClass('hidden');
    }

    if(typeof projectID == 'undefined') projectID = 0;
    $.getJSON($.createLink('branch', 'ajaxGetBranches', "productID=" + currentProduct + "&oldBranch=" + oldBranch + "&param=active&projectID=" + projectID + "&withMainBranch=true&isTwins=no&fieldID=0&multiple=&charterID=" + charterID), function(data)
    {
        if(data.length > 0)
        {
            $formRow.find('.form-group').eq(0).addClass('w-1/4').removeClass('w-1/2');
            $formRow.find('.form-group').eq(1).removeClass('hidden');
            const $branchPicker = $formRow.find('[name^=branch]').zui('picker');
            $branchPicker.render({items: data, multiple: true});
            $branchPicker.$.setValue(oldBranch).then(() =>
            {
                let branch = $(e.target).closest('.productBox').find("[name^='branch']");

                if(objectType == 'roadmap')
                {
                    loadRoadmaps(e.target, branch.length > 0 ? branch[0] : branch);
                }
                else
                {
                    loadPlans(e.target, branch.length > 0 ? branch[0] : branch);
                }
            });
        }
        else
        {
            let branch = $(e.target).closest('.productBox').find("[name^='branch']");

            if(objectType == 'roadmap')
            {
                loadRoadmaps(e.target, branch.length > 0 ? branch[0] : branch);
            }
            else
            {
                loadPlans(e.target, branch.length > 0 ? branch[0] : branch);
            }
        }
    });
}

window.addNewLine = function(e)
{
    const obj        = e.target
    const newLine    = $(obj).closest('.productBox').clone();
    const objectType = $('.productsBox').data('linkType');

    let index          = 0;
    let chosenProducts = [];
    let options        = zui.Picker.query("[name^='products']").options;
    options = JSON.parse(JSON.stringify(options));

    /* 将已有产品下拉的最大name属性的值加1赋值给新行. */
    $(".productsBox [name^='products']").each(function()
    {
        chosenProducts.push($(this).val());

        let id = $(this).attr('name').replace(/[^\d]/g, '');
        id = parseInt(id);
        id ++;
        index = id > index ? id : index;
    })
    while($('[name="' + objectType + 's[' + index + '][]"]').length > 0) index ++;

    /* Disable chosen products. */
    options.items.forEach(function(item)
    {
        if(chosenProducts.includes(item.value)) item.disabled = true;
    });

    /* 处理新一行控件的显示/隐藏，宽度/是否居中等样式问题. */
    newLine.addClass('newLine');
    newLine.find('.form-label').remove();
    newLine.find('.form-group').eq(0).addClass('w-1/2').removeClass('w-1/4');
    newLine.find('.form-group').eq(1).addClass('hidden');
    newLine.find("div[id^='" + objectType + "']").attr('id', objectType + index);
    newLine.find('.removeLine').removeClass('hidden');
    newLine.find('.c-actions').removeClass('first-action');

    /* 重新初始化新一行的下拉控件. */
    newLine.find('.form-group.linkProduct').html(`<div id=products${index} class='form-group-wrapper picker-box' data-call="loadBranches(event)" data-on="change"></div>`, false);
    newLine.find('.branchBox').html(`<div id=branch${index} class='form-group-wrapper picker-box' data-call="branchChange(event)" data-on="change"></div>`, false);
    newLine.find('div[id^=' + objectType + ']').html(`<div id=${objectType}s${index} class='form-group-wrapper picker-box'></div>`, false);

    $(obj).closest('.productBox').after(newLine.zuiInit());

    options.name         = `products[${index}]`;
    options.defaultValue = '';
    options.disabled     = false;
    options.placeholder  = '';
    new zui.Picker(`.linkProduct #products${index}`, options);
    new zui.Picker(`#branch${index}`, {name: `branch[${index}]`, multiple: true, items: []});
    new zui.Picker(`#${objectType}s${index}`, {items:[], multiple: true, name: `${objectType}s[${index}]`});

    if($('[name="charter"]').length && $('[name="charter"]').val() > 0)
    {
        if(options.items.length == $('.productBox').length) $('.addLine').addClass('hidden');
    }
}

window.removeLine = function(e)
{
    const obj = e.target;

    let removeProduct = $(obj).closest('.productBox').find("[name^='products']").val();
    $(".productsBox [name^='products']").each(function()
    {
        let items = zui.Picker.query(this).options.items;
        for(i = 0; i < items.length; i++)
        {
            if(items[i].value == removeProduct) items[i].disabled = false;
        }
    });

    $(obj).closest('.productBox').remove();
    if(typeof toggleStageBy == 'function') toggleStageBy();

    if($('[name="charter"]').length && $('[name="charter"]').val() > 0)
    {
        $('.addLine').removeClass('hidden');
    }
}

window.loadPlans = function(product, branch)
{
    const $productPicker = $(product).zui('picker').$;
    const $branchPicker  = $(branch).zui('picker').$;
    let productID = $productPicker.value;
    let branchID  = $branchPicker.value == null || ($branchPicker.value.length == 1 && $branchPicker.value[0] == '') ? 0 : '0,' + $branchPicker.value;

    $.get($.createLink('product', 'ajaxGetPlans', "productID=" + productID + '&branch=' + branchID + '&params=multiple&skipParent=true'), function(data)
    {
        if(data)
        {
            data = JSON.parse(data);

            const $planPicker = $(product).closest('.productBox').find('[name^=plans]').zui('picker');
            const oldPlan     = $(product).closest('.productBox').find('[name^=plans]').val();
            $planPicker.render({items: data, multiple: true, name: `plans[${productID}][]`});
            $planPicker.$.setValue(oldPlan);
        }
    });
}

window.loadRoadmaps = function(product, branch)
{
    const $productPicker = $(product).zui('picker').$;
    const $branchPicker  = $(branch).zui('picker').$;
    let productID = $productPicker.value;
    let branchID  = $branchPicker.value == null || ($branchPicker.value.length == 1 && $branchPicker.value[0] == '') ? 0 : $branchPicker.value;
    let charterID = $('[name="charter"]').length ? $('[name="charter"]').val() : 0;

    $.get($.createLink('demand', 'ajaxGetRoadmaps', "productID=" + productID + '&branch=' + branchID + '&param=&charter=' + charterID), function(data)
    {
        if(data)
        {
            data = JSON.parse(data);

            const $roadmapPicker = $(product).closest('.productBox').find('[name^=roadmaps]').zui('picker');
            const oldRoadmap     = $(product).closest('.productBox').find('[name^=roadmaps]').val();
            $roadmapPicker.render({items: data, multiple: true, name: `roadmaps[${productID}][]`});
            $roadmapPicker.$.setValue(oldRoadmap);
        }
    });
}

window.toggleNewProduct = function()
{
    if($(event.target).attr('id').indexOf('addProduct') == -1 && $(event.target).attr('id').indexOf('newProduct') == -1) return;
    if($(event.target).prop('checked'))
    {
        $('.productBox').addClass('hidden');
        $('.addProductBox').removeClass('hidden');
        $("[name='newProduct']").prop('checked', true);
        $("[name='addProduct']").prop('checked', true);
    }
    else
    {
        $('.productBox').removeClass('hidden');
        $('.addProductBox').addClass('hidden');
        $("[name='newProduct']").prop('checked', false);
        $("[name='addProduct']").prop('checked', false);
    }
    if(typeof toggleStageBy == 'function') toggleStageBy();
}

window.updateItems = function()
{
    if($('.productsBox .linkProduct').length <= 0) return;

    let chosenProducts = [];
    $(".productsBox [name^='products']").each(function()
    {
        const productID = $(this).val();
        if(productID > 0 && chosenProducts.indexOf(productID) == -1) chosenProducts.push(productID);
    });

    let allItems = zui.Picker.query("[name^='products']").options.items;
    let productItems = [];
    for(i = 0; i < allItems.length; i++)
    {
        allItems[i].disabled = false;
        if(chosenProducts.includes(allItems[i].value)) allItems[i].disabled = true;
        productItems[allItems[i].value] = Object.assign({},allItems[i]);
        productItems[allItems[i].value].i = i;
    }

    $(".productsBox [name^='products']").each(function()
    {
        const $product = $(this).zui('picker');
        const productID = $(this).val();
        let currentProductItems = JSON.parse(JSON.stringify(allItems));
        if(productID != 0) currentProductItems[productItems[productID].i].disabled = false;

        $product.render({items: currentProductItems});
    });
}
