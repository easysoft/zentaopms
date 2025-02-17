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

        if(item.value != 0 && multiBranchProducts[item.value])
        {
            let chosenBranches = [];
            $('[name^=product][value="' + item.value + '"]').closest('.productBox').find('[name^="branch"]').each(function()
            {
                if(!isNaN($(this).val()) && $(this).val() !== '') chosenBranches.push($(this).val().toString());
            });

            $('[name^=product][value="' + item.value + '"]').closest('.productBox').find('[name^="branch"]').each(function()
            {
                const branchID       = $(this).val();
                const $branch        = $(this).zui('picker');
                let branchItems      = $branch.options.items;
                let selectedBranches = 0;
                for(let i = 0; i < branchItems.length; i++)
                {
                    branchItems[i].disabled = branchItems[i].value != branchID && chosenBranches.includes(branchItems[i].value.toString());
                    if(chosenBranches.includes(branchItems[i].value.toString())) selectedBranches ++;
                    branchItems[i].i = i;
                }
                $branch.render({items: branchItems});

                if(selectedBranches != branchItems.length) item.disabled = false;
            });
        }
    });

    const objectType = $(obj).closest('.productsBox').data('objecttype');
    while($('[name="' + objectType + '[' + index + ']"]').length > 0) index ++;

    /* 处理新一行控件的显示/隐藏，宽度/是否居中等样式问题. */
    newLine.addClass('newLine');
    newLine.find('.form-label').addClass('hidden');
    newLine.find('.removeLine').removeClass('hidden');
    newLine.find('.c-actions').removeClass('first-action');
    newLine.find('.form-tip').remove();
    newLine.find('.form-group').eq(0).addClass('w-1/2').removeClass('w-1/4');
    newLine.find('.form-group').eq(1).addClass('hidden');

    /* 重新初始化新一行的下拉控件. */
    const objectClass = objectType == 'roadmap' ? '.linkRoadmap' : '.linkPlan';
    newLine.find('.linkProduct').html(`<div id=product${index} class='form-group-wrapper picker-box' data-on='change' data-call='refreshPicker' data-params='event'></div>`);
    newLine.find('.branchBox').html(`<div id=branch${index} class='form-group-wrapper picker-box' data-call="branchChange(event)" data-on="change"></div>`, false);
    newLine.find(objectClass).html(`<div id=${objectType}${index} class='form-group-wrapper picker-box'></div>`);

    $(obj).closest('.productBox').after(newLine);

    /* 初始化产品。*/
    options.name         = `product[${index}]`;
    options.defaultValue = '';
    options.disabled     = false;
    options.placeholder  = '';
    new zui.Picker(`#product${index}`, options);
    new zui.Picker(`#branch${index}`, {name: `branch[${index}]`, items: []});
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
    let productID  = product.val();
    let branchID   = product.closest('.productBox').find('.linkBranch .pick-value').val();
    if(typeof branchID == 'undefined' || branchID === '') $branchID = 'all';
    let link       = $.createLink('demand', 'ajaxGetRoadmaps', 'productID=' + productID + '&branch=' + branchID + '&param=nolaunching');
    const $items   = [{text: '', value: '', key: ''}];
    const $roadmap = product.closest('.productBox').find('.roadmapBox .pick-value');

    $roadmap.closest('.picker-box').removeClass('hidden');
    $roadmap.closest('.linkRoadmap').find('[name^=roadmapName]').addClass('hidden');
    $roadmap.closest('.linkRoadmap').find('[name^=roadmapName]').attr('disabled', 'disabled');
    $roadmap.zui('picker').render({disabled: false});
    $roadmap.zui('picker').$.setValue('');

    $.getJSON(link, function(data)
    {
        $roadmap.zui('picker').render({items: data});
        if(productID == '' || productID === undefined) $roadmap.zui('picker').render({items: $items});

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
    let productID = product.val();
    let branchID  = product.closest('.productBox').find('.linkBranch .pick-value').val();
    if(typeof branchID == 'undefined') branchID = '';
    let link      = $.createLink('charter', 'ajaxGetPlans', 'productID=' + productID + '&branchID=' + branchID);
    const $items  = [{text: '', value: '', key: ''}];
    const $plan   = product.closest('.productBox').find('.planBox .pick-value');

    $plan.closest('.picker-box').removeClass('hidden');
    $plan.zui('picker').render({disabled: false});
    $plan.zui('picker').$.setValue('');

    $.getJSON(link, function(data)
    {
        $plan.zui('picker').render({items: data});
        if(productID == '' || productID === undefined) $plan.zui('picker').render({items: $items});
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
    const objectType   = $('.productsBox').data('linkType');

    if(typeof projectID == 'undefined') projectID = 0;
    $.getJSON($.createLink('branch', 'ajaxGetBranches', "productID=" + currentProduct + "&oldBranch=&param=active&projectID=" + projectID + "&withMainBranch=true&isTwins=no&fieldID=0&multiple=&charterID=" + charterID), function(data)
    {
        if(data.length > 0)
        {
            let chosenBranches = [];
            $('[name^=product][value="' + currentProduct + '"]').closest('.productBox').find('[name^="branch"]').each(function()
            {
                if(!isNaN($(this).val()) && $(this).val() !== '') chosenBranches.push($(this).val().toString());
            });
            data.forEach(function(item)
            {
                item.value = item.value.toString();
                if(chosenBranches.includes(item.value)) item.disabled = true;
            });

            $formRow.find('.form-group').eq(0).addClass('w-1/4').removeClass('w-1/2');
            $formRow.find('.form-group').eq(1).removeClass('hidden');
            const $branchPicker = $formRow.find('[name^=branch]').zui('picker');
            $branchPicker.render({items: data}).then(() => updateItems());
            $branchPicker.$.setValue('').then(() =>
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
            updateItems();
        }
    });
}

window.updateItems = function(currentProduct = '', currentBranch = '')
{
    if($("[name^='product']").length == 0) return;

    let chosenProducts = [];
    $(".productsBox [name^='product']").each(function()
    {
        const productID = $(this).val();
        if(productID > 0 && chosenProducts.indexOf(productID) == -1) chosenProducts.push(productID);
    });

    let chosenBranches = {};
    if(currentProduct !== '') chosenBranches[currentProduct] = [];
    if(currentBranch !== '') chosenBranches[currentProduct].push(currentBranch);

    let allItems       = zui.Picker.query("[name^='product']").options.items;
    let productItems   = [];
    for(let i = 0; i < allItems.length; i++)
    {
        const productID = allItems[i].value;
        allItems[i].disabled = false;
        if(chosenProducts.includes(productID)) allItems[i].disabled = true;
        productItems[productID] = Object.assign({},allItems[i]);
        productItems[productID].i = i;

        if(allItems[i].disabled && multiBranchProducts[productID])
        {
            if($('[name^=product][value="' + productID + '"]').length > 0)
            {
                const $branch     = $('[name^=product][value="' + productID + '"]').closest('.productBox').find('[name^="branch"]');
                const branchItems = $branch.zui('picker').options.items

                if(!chosenBranches[productID]) chosenBranches[productID] = [];
                $branch.each(function()
                {
                    const branchID = $(this).val().toString();
                    if(!isNaN(branchID) && !chosenBranches[productID].includes(branchID) && branchID !== '') chosenBranches[productID].push(branchID);
                });

                if(chosenBranches[productID].length != branchItems.length) allItems[i].disabled = false;
            }
            else
            {
                allItems[i].disabled = false;
            }
        }
    }
    if(currentProduct !== '')
    {
        $('[name^=product][value="' + currentProduct + '"]').closest('.productBox').find('[name^="branch"]').each(function()
        {
            const $branch     = $(this).zui('picker');
            const branchID    = $(this).val().toString();
            let   branchItems = $branch.options.items
            branchItems.forEach(function(item)
            {
                item.disabled = false;
                if(chosenBranches[currentProduct].includes(item.value)) item.disabled = true;
                if(branchID == item.value) item.disabled = false;
            });
            $branch.render({items: branchItems});
        });
    }

    $(".productsBox [name^='product']").each(function()
    {
        const $product  = $(this).zui('picker');
        const productID = $(this).val();
        let currentProductItems = JSON.parse(JSON.stringify(allItems));

        if(productID != 0) currentProductItems[productItems[productID].i].disabled = false;

        $product.render({items: currentProductItems});
    });
}

window.branchChange = function(event)
{
    const productID  = $(event.target).closest('.productBox').find('.linkProduct .picker-box').zui('picker').$.value;
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

    updateItems(productID, $(event.target).val());
}
