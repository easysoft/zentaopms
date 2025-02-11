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

    $(obj).closest('.productBox').after(newLine);

    /* 初始化产品。*/
    options.name         = `product[${index}]`;
    options.defaultValue = '';
    options.disabled     = false;
    options.placeholder  = '';
    new zui.Picker(`#product${index}`, options);

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
    let link       = $.createLink('demand', 'ajaxGetRoadmaps', 'productID=' + productID + '&branch=all&param=nolaunching');
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
    let link      = $.createLink('charter', 'ajaxGetPlans', 'productID=' + productID);
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
    let $products        = $('.productsBox').find('.linkProduct .pick-value');
    let selectedProducts = [];
    $products.each(function()
    {
        if($(this).val() != '') selectedProducts.push($(this).val());
    });

    $products.each(function()
    {
        let $product       = $(this);
        let productID      = $product.val();
        let $productPicker = $product.zui('picker');
        let productItems   = $productPicker.options.items;

        $.each(productItems, function(i, item)
        {
            if(item.value == '') return;
            productItems[i].disabled = selectedProducts.includes(item.value) && item.value != productID;
        })

        $productPicker.render({items: productItems});
    });

    if(typeof event !== "undefined")
    {
        const objectType = $(event.target).closest('.productsBox').data('objecttype');
        if(objectType == 'roadmap') loadRoadmap($(event.target));
        if(objectType == 'plan')    loadPlan($(event.target));
    }
}

window.loadRoadmapStories = function(event)
{
    let $roadmaps = $(event.target).closest('.roadmapBox').find('.pick-value').val()
    let $product  = $(event.target).closest('.productBox').find('.linkProduct .pick-value').val()

    const link = $.createLink('charter', 'loadRoadmapStories', 'product=' + $product + '&roadmaps=' + $roadmaps.join(), '', true);

    openUrl(link, {load: 'modal', size: 'lg', type: 'iframe'});
}
