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
    while($('[name="roadmap[' + index + '][]"]').length > 0) index ++;

    /* Disable chosen products. */
    options.items.forEach(function(item)
    {
        if(chosenProducts.includes(item.value)) item.disabled = true;
    });

    /* 处理新一行控件的显示/隐藏，宽度/是否居中等样式问题. */
    newLine.addClass('newLine');
    newLine.find('.form-label').remove();
    //newLine.find('.form-group').eq(0).addClass('w-1/2').removeClass('w-1/4');
    //newLine.find('.form-group').eq(1).addClass('hidden');
    newLine.find("div[id^='roadmap']").attr('id', 'roadmap' + index);
    newLine.find("[name^='addRoadmap']").attr('name', 'addRoadmap[' + index + ']');
    newLine.find('.removeLine').removeClass('hidden');
    newLine.find('.c-actions').removeClass('first-action');

    /* 重新初始化新一行的下拉控件. */
    newLine.find('.form-group .linkProduct').html(`<div id=product${index} class='form-group-wrapper picker-box'></div>`);
    newLine.find('.form-group .linkBranch').html(`<div  id=branch${index} class='form-group-wrapper picker-box'></div>`);
    newLine.find('.form-group .linkRoadmap').html(`<div id=roadmap${index} class='form-group-wrapper picker-box'></div>`);

    $(obj).closest('.productBox').after(newLine);

    options.name         = `products[${index}]`;
    options.defaultValue = '';
    options.disabled     = false;
    options.placeholder  = '';
    new zui.Picker(`.distributeProduct #product${index}`, options);
    new zui.Picker(`#branch${index}`, {name: `branch[${index}]`, multiple: true, items: []});
    new zui.Picker(`#roadmap${index}`, {items:[], multiple: true, name: `roadmap[${index}]`});
}

window.removeLine = function(e)
{
    const obj = e.target;

    let removeProduct = $(obj).closest('.productBox').find("[name^='product']").val();
    $(".productsBox .pick-value[name^='product']").each(function()
    {
        let items = zui.Picker.query(this).options.items;
        for(i = 0; i < items.length; i++)
        {
            if(items[i].value == removeProduct) items[i].disabled = false;
        }
    });

    $(obj).closest('.productBox').remove();
}
