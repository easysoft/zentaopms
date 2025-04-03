$(function()
{
    $('.productBox .picker-box').last().on('inited', function(){updateItems();});
});

window.addNewLine = function(e)
{
    const obj     = e.target
    const newLine = $(obj).closest('.productBox').clone();

    $(obj).closest('.productBox').find('.removeLine').removeClass('hidden');

    let index          = 0;
    let chosenProducts = [];
    let options        = zui.Picker.query("[name^='product']").options;
    options = JSON.parse(JSON.stringify(options));

    /* 将已有产品下拉的最大name属性的值加1赋值给新行. */
    $(".productsBox .pick-value[name^='product']").each(function()
    {
        chosenProducts.push($(this).val());

        let id = $(this).attr('name').replace(/[^\d]/g, '');
        id = parseInt(id);
        id ++;
        index = id > index ? id : index;
    })

    /* Disable chosen products. */
    options.items.forEach(function(item)
    {
        if(chosenProducts.includes(item.value)) item.disabled = true;
    });

    const objectType = $(obj).closest('.productsBox').data('objecttype');
    while($('[name="' + objectType + '[' + index + ']"]').length > 0) index ++;

    /* 处理新一行控件的显示/隐藏，宽度/是否居中等样式问题. */
    newLine.addClass('newLine');
    newLine.find('.form-label').addClass('hidden');
    newLine.find('.removeLine').removeClass('hidden');
    newLine.find('.c-actions').removeClass('first-action');
    newLine.find('.form-tip').remove();

    /* 重新初始化新一行的下拉控件. */
    const objectClass = objectType == 'roadmap' ? '.linkRoadmap' : '.linkPlan';
    newLine.find('.linkProduct').html(`<div id=product${index} class='form-group-wrapper picker-box' data-on='change' data-call='refreshPicker' data-params='event'></div>`);
    newLine.find(objectClass).html(`<div id=${objectType}${index} class='form-group-wrapper picker-box'></div>`);
    newLine.find('.branchBox').html(`<div id=branch${index} class='form-group-wrapper picker-box' data-call="branchChange(event)" data-on="change"></div>`, false);

    $(obj).closest('.productBox').after(newLine);

    /* 初始化产品。*/
    options.name         = `product[${index}]`;
    options.defaultValue = '';
    options.disabled     = false;
    options.placeholder  = '';
    new zui.Picker(`#product${index}`, options);
    new zui.Picker(`#branch${index}`, {name: `branch[${index}]`, multiple: true, items: []});
    new zui.Picker(`#${objectType}${index}`, {name: `${objectType}[${index}]`, items: [], multiple: true});

    refreshPicker();
}

window.removeLine = function(e)
{
    const obj = e.target;
    $(obj).closest('.productBox').remove();

    const $productBox = $('.productsBox').find('.productBox');
    if($productBox.length == 1) $productBox.find('.removeLine').addClass('hidden');
    $productBox.first().find('.form-label').removeClass('hidden');

    refreshPicker();
    updateItems();
}

/**
 * Load roadmap.
 *
 * @param  object product
 * @access public
 * @return void
 */
window.loadRoadmap = function(product)
{
    const objectType = product.closest('.productsBox').data('objecttype');
    if(objectType != 'roadmap') return;

    let productID    = product.val();
    let branchID     = product.closest('.productBox').find('.linkBranch .pick-value').val();
    if(typeof branchID == 'undefined' || branchID === '') branchID = 'all';
    let link         = $.createLink('demand', 'ajaxGetRoadmaps', 'productID=' + productID + '&branch=' + branchID + '&param=nolaunching');
    const $items     = [{text: '', value: '', key: ''}];
    const $roadmap   = product.closest('.productBox').find('.roadmapBox .pick-value');
    const oldRoadmap = product.closest('.productBox').find('[name^=roadmap]').val();

    $roadmap.closest('.picker-box').removeClass('hidden');
    $roadmap.closest('.linkRoadmap').find('[name^=roadmapName]').addClass('hidden');
    $roadmap.closest('.linkRoadmap').find('[name^=roadmapName]').attr('disabled', 'disabled');
    $roadmap.zui('picker').render({disabled: false});

    $.getJSON(link, function(data)
    {
        $roadmap.zui('picker').render({items: data});
        if(productID == '' || productID === undefined) $roadmap.zui('picker').render({items: $items});
        $roadmap.zui('picker').$.setValue(oldRoadmap);

        let optionCount = $roadmap.zui('picker').options.items.length;

        $roadmap.closest('.input-group').find('.addRoadmap').toggleClass('hidden', !productID || (productID != 0 && optionCount));
        $roadmap.closest('.input-group').find('[name^=addRoadmap]').prop('checked', false);
    });
}

/**
 * Load plan.
 *
 * @param  object product
 * @access public
 * @return void
 */
window.loadPlan = function(product)
{
    const objectType = product.closest('.productsBox').data('objecttype');
    if(objectType != 'plan') return;

    let productID = product.val();
    let branchID  = product.closest('.productBox').find('.linkBranch .pick-value').val();
    if(!multiBranchProducts[productID] || typeof branchID == 'undefined') branchID = 'all';
    let link      = $.createLink('charter', 'ajaxGetPlans', 'productID=' + productID + '&branchID=' + branchID);
    const $items  = [{text: '', value: '', key: ''}];
    const $plan   = product.closest('.productBox').find('.planBox .pick-value');
    const oldPlan = product.closest('.productBox').find('[name^=plan]').val();

    $plan.closest('.picker-box').removeClass('hidden');
    $plan.zui('picker').render({disabled: false});

    $.getJSON(link, function(data)
    {
        $plan.zui('picker').render({items: data});
        if(productID == '' || productID === undefined) $plan.zui('picker').render({items: $items});
        $plan.zui('picker').$.setValue(oldPlan);
    });
}

window.refreshPicker = function(event)
{
    if(typeof event !== "undefined")
    {
        const $product  = $(event.target);
        const productID = $product.val();
        if(multiBranchProducts[productID])
        {
            loadBranches($product);
        }
        else
        {
            updateItems();

            const objectType = $product.closest('.productsBox').data('objecttype');
            if(objectType == 'roadmap') loadRoadmap($product);
            if(objectType == 'plan')    loadPlan($product);

            let $formRow = $product.closest('.productBox');
            $formRow.find('.form-group').eq(0).addClass('w-1/2').removeClass('w-1/4');
            $formRow.find('.form-group').eq(1).addClass('hidden');
        }
    }
}

window.loadRoadmapStories = function(event)
{
    let $roadmaps = $(event.target).closest('.roadmapBox').find('.pick-value').val()
    let $product  = $(event.target).closest('.productBox').find('.linkProduct .pick-value').val()

    const link = $.createLink('charter', 'loadRoadmapStories', 'product=' + $product + '&roadmaps=' + $roadmaps.join(), '', true);

    openUrl(link, {load: 'modal', size: 'lg', type: 'iframe'});
}

window.loadBranches = function(product)
{
    let currentProduct = product.val();
    let $formRow       = product.closest('.productBox');
    let charterID      = $('[name="charter"]').length ? $('[name="charter"]').val() : 0;
    const objectType   = product.closest('.productsBox').data('objecttype');

    if(typeof projectID == 'undefined') projectID = 0;
    $.getJSON($.createLink('branch', 'ajaxGetBranches', "productID=" + currentProduct + "&oldBranch=&param=active&projectID=" + projectID + "&withMainBranch=true&isTwins=no&fieldID=0&multiple=&charterID=" + charterID), function(data)
    {
        if(data.length > 0)
        {
            $formRow.find('.form-group').eq(0).addClass('w-1/4').removeClass('w-1/2');
            $formRow.find('.form-group').eq(1).removeClass('hidden');
            const $branchPicker = $formRow.find('[name^=branch]').zui('picker');
            $branchPicker.render({items: data, multiple: true});
            $branchPicker.$.setValue('0').then(() =>
            {
                if(objectType == 'roadmap')
                {
                    loadRoadmap(product);
                }
                else
                {
                    loadPlan(product);
                }
            });
        }
        else
        {
            if(objectType == 'roadmap')
            {
                loadRoadmap(product);
            }
            else
            {
                loadPlan(product);
            }
        }
    });

    updateItems();
}

window.branchChange = function(event)
{
    const objectType = $(event.target).closest('.productsBox').data('objecttype');
    const $product   = $(event.target).closest('.productBox').find('.linkProduct .pick-value');
    if(objectType == 'roadmap')
    {
        loadRoadmap($product);
    }
    else
    {
        loadPlan($product);
    }

    updateItems();
}

window.updateItems = function()
{
    let chosenProducts = [];
    $(".productsBox [name^='product']").each(function()
    {
        const productID = $(this).val();
        if(productID > 0 && chosenProducts.indexOf(productID) == -1) chosenProducts.push(productID);
    });

    let allItems = zui.Picker.query("[name^='product']").options.items;
    let productItems = [];
    for(i = 0; i < allItems.length; i++)
    {
        allItems[i].disabled = false;
        if(chosenProducts.includes(allItems[i].value)) allItems[i].disabled = true;
        productItems[allItems[i].value] = Object.assign({},allItems[i]);
        productItems[allItems[i].value].i = i;
    }

    $(".productsBox [name^='product']").each(function()
    {
        const $product = $(this).zui('picker');
        const productID = $(this).val();
        let currentProductItems = JSON.parse(JSON.stringify(allItems));
        if(productID != 0) currentProductItems[productItems[productID].i].disabled = false;

        $product.render({items: currentProductItems});
    });
}
