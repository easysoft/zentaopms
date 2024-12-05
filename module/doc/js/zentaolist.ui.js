function getType()
{
    return $('#zentaolist').data('type');
}

function getParams()
{
    return $('#zentaolist').data('params');
}

function getValue(name)
{
    return $('#zentaolist [name=' + name + ']').val();
}

function updatePicker(name, items)
{
    const $picker = $('#zentaolist [name=' + name + ']').zui('picker');
    $picker.render({items});
    $picker.$.setValue(null);
}

function checkForm(form, formData)
{
    let isValid = true;
    $(form).find('.error-tip').addClass('hidden');
    $(form).find('.form-group').removeClass('has-error');

    // 遍历 FormData 中的所有键值对
    for (let [name, value] of formData.entries()) {
        const inputElement = form.querySelector(`[name="${name}"]`);
        const formGroup    = $(inputElement).closest('.form-group');

        // 检查是否是必填项
        if (inputElement && formGroup.hasClass('required') && !value?.length) {
            isValid = false;
            formGroup.find('.error-tip').removeClass('hidden');
            formGroup.addClass('has-error');
        }
    }

    return isValid;
}

window.backToSet = function()
{
    const url = $.createLink('doc', 'zentaolist', 'type=' + getType() + '&view=setting&params=' + getParams());
    loadPage(url);
}

function formDataConvertParams(formData)
{
    let params = '';
    for (let [name, value] of formData.entries()) {
        if(params) params += ',';
        params += `${name}=${value}`;
    };
    return params;
}

function preview()
{
    const form     = $('#zentaolist form');
    const formData = new FormData(form[0]);
    if(!checkForm(form[0], formData)) return;

    const params = formDataConvertParams(formData);
    const url    = $.createLink('doc', 'zentaolist', 'type=' + getType() + '&view=setting&params=' + params);
    loadPage(url);
}

function insert()
{
    const dtable = zui.DTable.query($('#previewTable'));
    const checkedList = dtable.$.getChecks();
    const tip = $('#insert').data('tip');
    if(checkedList.length == 0)
    {
        zui.Modal.alert(tip);
        return;
    }

    const form     = $('#zentaolist form');
    const formData = new FormData(form[0]);
    const url = $.createLink('doc', 'zentaolist', 'type=' + getType() + '&view=list&params=' + getParams() + '&idList=' + checkedList.join(','));
    loadPage(url);
}

function cancel()
{
}

function changeProduct()
{
    const product = getValue('product');
    const type = getType();
    if(type === 'planStory' || type == 'productPlanContent')
    {
        const link = $.createLink('productplan', 'ajaxGetProductplans', 'product=' + product);
        $.get(link, function(resp)
        {
            resp = JSON.parse(resp);
            updatePicker('plan', resp);
        });
    }
}
