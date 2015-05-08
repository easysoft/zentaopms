$(function()
{
    $('#productTableList').on('sort.sortable', function(e, data)
    {
        // TODO: save order to server.
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i]).attr('data-id') + ',';
        $.post(createLink('product', 'ajaxOrder'), {'products' : list, 'orderBy' : orderBy});
    });
});
