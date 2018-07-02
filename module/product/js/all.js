$(function()
{
    $('#productTableList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i].item).attr('data-id') + ',';
        $.post(createLink('product', 'updateOrder'), {'products' : list, 'orderBy' : orderBy});
    });
});
