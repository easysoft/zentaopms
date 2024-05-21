window.addNewLine = function(e)
{
    const obj     = e.target
    const newLine = $(obj).closest('.productBox').clone();

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

    while($('[name="roadmap[' + index + ']"]').length > 0) index ++;

    /* 处理新一行控件的显示/隐藏，宽度/是否居中等样式问题. */
    newLine.addClass('newLine');
    newLine.find('.form-label').remove();
    newLine.find('.linkBranch').addClass('hidden');
    newLine.find("[name^='addRoadmap']").attr({'id': 'addRoadmap[' + index + ']_', 'name': 'addRoadmap[' + index + ']'});
    newLine.find("[name^='addRoadmap']").siblings('label').attr('for', 'addRoadmap[' + index + ']_');
    newLine.find('.removeLine').removeClass('hidden');
    newLine.find('.c-actions').removeClass('first-action');

    /* 重新初始化新一行的下拉控件. */
    newLine.find('.linkProduct').html(`<div id=product${index} class='form-group-wrapper picker-box'></div>`);
    newLine.find('.linkBranch').html(`<div  id=branch${index}  class='form-group-wrapper picker-box'></div>`);
    newLine.find('.linkRoadmap').html(`<div id=roadmap${index} class='form-group-wrapper picker-box'></div><input class='form-control hidden' disabled name='roadmapName[${index}]'/>`);

    $(obj).closest('.productBox').after(newLine);

    options.name         = `products[${index}]`;
    options.defaultValue = '';
    options.disabled     = false;
    options.placeholder  = '';
    new zui.Picker(`#product${index}`, options);
    new zui.Picker(`#branch${index} `, {name: `branch[${index}]`,  items: []});
    new zui.Picker(`#roadmap${index}`, {name: `roadmap[${index}]`, items: []});

    refreshPicker();
}

window.removeLine = function(e)
{
    const obj = e.target;

    $(obj).closest('.productBox').remove();

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
    let link       = $.createLink('demand', 'ajaxGetRoadmaps', 'productID=' + productID + '&branch=' + branchID + '&param=distributable');
    const $roadmap = product.closest('.productBox').find('.roadmapBox .pick-value').zui('picker');
    $.getJSON(link, function(data)
    {
        $roadmap.render({items: data});

        let optionCount = $roadmap.options.items.length;

        $roadmap.closest('.input-group').find('.addRoadmap').toggleClass('hidden', !optionCount && productID != 0);
    });
}

/**
 * Load branches when change product.
 *
 * @param  object   obj
 * @access public
 * @return void
 */
window.loadProductBranches = function(obj)
{
    const $product  = $(obj);
    const productID = $product.val();

    $.getJSON($.createLink('demand', 'ajaxGetBranches', "productID=" + productID), function(data)
    {
        const branchPicker = $product.closest('.input-group').find('.linkBranch .pick-value').zui('picker');
        $product.closest('.input-group').find('.linkBranch').toggleClass('hidden', !data.length);
        branchPicker.render({items: data});
        branchPicker.$.setValue(data[0].value);

        loadRoadmap($product);
    })
}

/**
 * Load branch.
 *
 * @access public
 * @return void
 */
window.loadBranch = function()
{
    const $product = $(this).closest('.distributeProduct').find('.linkProduct .pick-value');
    loadRoadmap($product);
}

/**
 * Add new product.
 *
 * @access public
 * @return void
 */
window.addProduct = function()
{
    if($(this).prop('checked'))
    {
        /* Hide product dropdown controls. */
        $('.productBox .linkProduct .picker-box').addClass('hidden');
        $('.productBox .linkProduct .picker-box').zui('picker').render({disabled: true});

        /* Displays the input box for creating a product. */
        $('[name=productName]').removeClass('hidden');
        $('[name=productName]').removeAttr('disabled');
    }
    else
    {
        /* Show product dropdown controls. */
        $('.productBox .linkProduct .picker-box').removeClass('hidden');
        $('.productBox .linkProduct .picker-box').zui('picker').render({disabled: false});

        /* Hide the input box for creating a product. */
        $('[name=productName]').addClass('hidden');
        $('[name=productName]').attr('disabled', 'disabled');
    }
}

/**
 * Add new roadmap.
 *
 * @access public
 * @return void
 */
window.addRoadmap = function()
{
    if($(this).prop('checked'))
    {
        $(this).closest('.roadmapBox').find('.linkRoadmap .picker-box').addClass('hidden');
        $(this).closest('.roadmapBox').find('.linkRoadmap .pick-value').zui('picker').render({disabled: true});
        $(this).closest('.roadmapBox').find('[name^=roadmapName]').removeAttr('disabled');
        $(this).closest('.roadmapBox').find('[name^=roadmapName]').removeClass('hidden');
    }
    else
    {
        $(this).closest('.roadmapBox').find('.linkRoadmap .picker-box').removeClass('hidden');
        $(this).closest('.roadmapBox').find('.linkRoadmap .pick-value').zui('picker').render({disabled: false});
        $(this).closest('.roadmapBox').find('[name^=roadmapName]').attr('disabled', 'disabled');
        $(this).closest('.roadmapBox').find('[name^=roadmapName]').addClass('hidden');
    }
}

window.refreshPicker = function(product)
{
    let $products        = $('.distributeForm').find('.productBox .linkProduct .pick-value');
    let selectedProducts = [];
    $products.each(function()
    {
        if($(this).val() != '') selectedProducts.push($(this).val());
    });

    $products.each(function()
    {
        let $product       = $(this);
        let product        = $product.val();
        let $productPicker = $product.zui('picker');
        let productItems   = $productPicker.options.items;

        $.each(productItems, function(i, item)
        {
            if(item.value == '') return;
            productItems[i].disabled = selectedProducts.includes(item.value) && item.value != product;
        })

        $productPicker.render({items: productItems});
    });

    if(typeof product != undefined) loadProductBranches(product);
}
