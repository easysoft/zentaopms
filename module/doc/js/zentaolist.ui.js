function getType()
{
    return $('#zentaolist').data('type');
}

function getValue(name)
{
    return $('#zentaolist [name=' + name + ']').val();
}

function updatePicker(name, items)
{
    const $picker = $('#zentaolist [name=' + name + ']').zui('picker');
    $picker.render({items});
    $picker.$.setValue(items[0]?.value)
}

function preview()
{
    const form = $('#zentaolist form');
    const formData = new FormData(form[0]);
    const url = $.createLink('doc', 'zentaolist', 'type=' + getType());
    postAndLoadPage(url, formData, '#previewForm,pageJS/.zin-page-js,#configJS');
}

function insert()
{
    const dtable = zui.DTable.query($('#previewTable'));
    const checkedList = dtable.$.getChecks();

    const url = $.createLink('doc', 'zentaolist', 'type=' + getType() + '&view=list&idList=' + checkedList.join(','));
    loadPage(url);
}

function cancel()
{
}

function changeProduct()
{
    const product = getValue('product');
    const type = getType();
    if(type === 'planStory')
    {
        const link = $.createLink('productplan', 'ajaxGetProductplans', 'product=' + product);
        $.get(link, function(resp)
        {
            resp = JSON.parse(resp);
            updatePicker('plan', resp);
        });
    }
}
