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

    while($('[name="roadmap[' + index + ']"]').length > 0) index ++;
    while($('[name="storyGrade[' + index + ']"]').length > 0) index ++;

    /* 处理新一行控件的显示/隐藏，宽度/是否居中等样式问题. */
    newLine.addClass('newLine');
    newLine.find('.form-label').addClass('hidden');
    newLine.find('.removeLine').removeClass('hidden');
    newLine.find('.c-actions').removeClass('first-action');

    /* 重新初始化新一行的下拉控件. */
    newLine.find('.linkProduct').html(`<div id=product${index}    class='form-group-wrapper picker-box'></div>`);
    newLine.find('.storyGrade').html(`<div  id=storyGrade${index} class='form-group-wrapper picker-box'></div>`);
    newLine.find('.linkBranch').html(`<div  id=branch${index}     class='form-group-wrapper picker-box'></div>`);
    newLine.find('.linkRoadmap').html(`<div id=roadmap${index}    class='form-group-wrapper picker-box'></div>`);

    $(obj).closest('.productBox').after(newLine);

    /* 初始化产品。*/
    options.name         = `product[${index}]`;
    options.defaultValue = '';
    options.disabled     = false;
    options.placeholder  = '';
    new zui.Picker(`#product${index}`, options);

    /* 初始化需求层级。*/
    let gradeOptions   = $(obj).closest('.productBox').find('[name^=storyGrade]').zui('picker').options;
    gradeOptions.name  = `storyGrade[${index}]`;
    gradeOptions.items = [];
    new zui.Picker(`#storyGrade${index}`, gradeOptions);

    new zui.Picker(`#branch${index} `, {name: `branch[${index}]`,  items: [], emptyValue: ''});
    new zui.Picker(`#roadmap${index}`, {name: `roadmap[${index}]`, items: []});

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
    let   storyType  = 'epic';
    let   objectType = 'all';
    const storyGrade = product.closest('.productBox').find('[name^=storyGrade]').val();
    if(storyGrade.indexOf('-') !== -1) storyType = storyGrade.substr(0, storyGrade.indexOf('-'));
    if(storyType == 'story') objectType = 'plan';

    let productID  = product.val();
    let branchID   = product.closest('.productBox').find('.linkBranch .pick-value').val();
    let link       = $.createLink('demand', 'ajaxGetRoadmapPlans', 'productID=' + productID + '&branch=' + branchID + '&getObjectType=' + objectType);
    const $roadmap = product.closest('.productBox').find('.roadmapBox .pick-value');

    $roadmap.closest('.picker-box').removeClass('hidden');
    $roadmap.zui('picker').render({disabled: false});
    $.getJSON(link, function(data)
    {
        $roadmap.zui('picker').render({items: data});
    });
}

/**
 * Load branches when change product.
 *
 * @param  object   obj
 * @access public
 * @return bool
 */
window.loadProductBranches = function(obj)
{
    const $product     = $(obj);
    const productID    = $product.val();
    const branchPicker = $product.closest('.input-group').find('.linkBranch .pick-value').zui('picker');

    if(!productID)
    {
        $product.closest('.input-group').find('.linkBranch').addClass('hidden');
        branchPicker.render({items: []});
        $product.closest('.productBox').find('[name^=roadmap]').zui('picker').render({items: []});

        return false;
    }

    $.getJSON($.createLink('demand', 'ajaxGetBranches', "productID=" + productID), function(data)
    {
        $product.closest('.input-group').find('.linkBranch').toggleClass('hidden', !data.length);
        branchPicker.render({items: data, emptyValue: ''});
        if(data.length) branchPicker.$.setValue(data[0].value);

        loadRoadmap($product);
    })
}

window.loadStoryGrade = function(productBox)
{
    const productID    = $(productBox).find('[name^=product]').val();
    const addProduct   = $(productBox).find('[name^=addProduct]').prop('checked') ? 'true' : 'false';
    const $gradePicker = $(productBox).find('[name^=storyGrade]').zui('picker');

    $.getJSON($.createLink('demand', 'ajaxGetStoryGrade', "productID=" + productID + "&addProduct=" + addProduct), function(data)
    {
        $gradePicker.render({items: data});
        $gradePicker.$.setValue(data[0].value);
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
 * Load roadmap or plan.
 *
 * @access public
 * @return void
 */
window.loadRoadmapPlans = function()
{
    const $product = $(this).closest('.productBox').find('.linkProduct .pick-value');
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

    loadStoryGrade($(this).closest('.productBox'));
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

    if(typeof product !== "undefined")
    {
        loadProductBranches(product);
        loadStoryGrade(product.closest('.productBox'));
    }
}
