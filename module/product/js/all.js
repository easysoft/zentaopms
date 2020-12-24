$("#" + browseType + "Tab").addClass('btn-active-text');
$(function()
{
    var $list = $('#programTableList');
    $('#productTableList').addClass('sortable').sortable(
    {
        selector: 'tr',
        dragCssClass: 'drag-row',
        trigger: $list.find('.sort-handler').length ? '.sort-handler' : null,
        canMoveHere: function($ele, $target)
        {
            return $ele.data('parent') === $target.data('parent');
        },
        finish: function(e)
        {
            $('#productListForm').table('initNestedList')
        }
    });

    $('#productTableList').on('sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i].item).attr('data-id') + ',';
        $.post(createLink('product', 'updateOrder'), {'products' : list, 'orderBy' : orderBy});
    });
});
