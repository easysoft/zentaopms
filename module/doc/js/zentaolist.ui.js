function getType()
{
    return $('#zentaolist').data('type');
}

function getSettings(useFormData = false)
{
    const settings = $('#zentaolist').data('settings');
    const idList   = $('#zentaolist').data('idlist');
    settings.idList = idList;
    if(!useFormData) return settings;

    const formData = new FormData();
    for(const key in settings)
    {
        const value = settings[key];
        if(Array.isArray(value))
        {
            value.forEach((item, index) => (formData.append(`${key}[]`, item)));
        }
        else
        {
            formData.append(key, value);
        }
    }
    return formData;
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
    const settings = $('#previewForm').data('settings');
    const blockID  = $('#previewForm').data('blockid');
    parent.zui.Modal.open({
        size: 'lg',
        url: settings.replace('{blockID}', blockID)
    });
}

window.toggleCheckRows = function()
{
    const idList = $('#zentaolist').data('idlist');
    if(!idList?.length) return;
    const dtable = zui.DTable.query($('#previewTable'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}

function loadWithForm(formData, view = 'setting', action = 'load')
{
    const sessionUrl = $.createLink('doc', 'buildZentaoList', 'type=' + getType());
    const loadUrl    = $.createLink('doc', 'zentaolist', 'type=' + getType() + '&view=' + view);

    $.post(sessionUrl, formData, function(data)
    {
        data = JSON.parse(data);
        if(data.result == 'success') action === 'load' ? loadPage(loadUrl) : loadCurrentPage('#customSearchContent');
    });
}

function preview()
{
    const form     = $('#zentaolist form');
    const formData = new FormData(form[0]);
    if(!checkForm(form[0], formData)) return;

    formData.append('action', 'preview');
    loadWithForm(formData);
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
    formData.append('action', 'insert');
    formData.append('idList', checkedList.join(','));
    loadWithForm(formData, 'list');
}

window.cancel = function()
{
    zui.Editor.iframe.delete();
}

function changeCondition()
{
    const condition = getValue('condition');
    if(condition == 'customSearch')
    {
        $('#customSearchContent').removeClass('hidden');
    }
    else
    {
        $('#customSearchContent').addClass('hidden');
    }
}

window.updateCustomSearchItem = function($this, action)
{
    const index    = $this.data('index');
    const form     = $('#zentaolist form');
    const formData = new FormData(form[0]);
    formData.append('conditionAction', action);
    formData.append('conditionIndex',  index);
    loadWithForm(formData, 'setting', 'post');
}

window.updateCustomSearch = function()
{
    const form = $('#zentaolist form');
    const formData = new FormData(form[0]);
    loadWithForm(formData, 'setting', 'post');
}

function changeProduct()
{
    const product = getValue('product');
    const type = getType();
    if(type === 'planStory' || type == 'planBug')
    {
        const link = $.createLink('productplan', 'ajaxGetProductplans', 'product=' + product);
        $.get(link, function(resp)
        {
            resp = JSON.parse(resp);
            updatePicker('plan', resp);
        });
    }

    if(type === 'productCase')
    {
        const condition = getValue('condition');
        if(condition == 'customSearch') updateCustomSearch();
    }
}
