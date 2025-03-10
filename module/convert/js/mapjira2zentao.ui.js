$(function()
{
    $('.picker-box').last().on('inited', function(){updateItems();});
});

window.next = function()
{
    $('button[type=submit]').trigger('click');
}

window.changeField = function(e)
{
    updateItems();
}

window.updateItems = function()
{
    let chosenFields = [];
    $("[name^='zentaoField']").each(function()
    {
        const field = $(this).val();
        if(field == 'add_field') return;
        if(field && chosenFields.indexOf(field) == -1) chosenFields.push(field);
    });

    let allItems = zui.Picker.query("[name^='zentaoField']")?.options?.items;
    if(typeof allItems == 'undefined') return;

    let fieldItems = [];
    for(i = 0; i < allItems.length; i++)
    {
        allItems[i].disabled = false;
        if(chosenFields.includes(allItems[i].value)) allItems[i].disabled = true;
        fieldItems[allItems[i].value] = Object.assign({},allItems[i]);
        fieldItems[allItems[i].value].i = i;
    }

    $("[name^='zentaoField']").each(function()
    {
        const $field = $(this).zui('picker');
        const field  = $(this).val();
        let currentFieldItems = JSON.parse(JSON.stringify(allItems));
        if(field) currentFieldItems[fieldItems[field].i].disabled = false;
        $field.render({items: currentFieldItems});
    });
}
