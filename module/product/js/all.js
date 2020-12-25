$("#" + browseType + "Tab").addClass('btn-active-text');
$(function()
{
    var $list = $('#productTableList');
    $list.addClass('sortable').sortable(
    {
        reverse: orderBy === 'order_desc',
        selector: 'tr',
        dragCssClass: 'drag-row',
        trigger: $list.find('.sort-handler').length ? '.sort-handler' : null,
        canMoveHere: function($ele, $target)
        {
            return $ele.data('parent') === $target.data('parent');
        },
        start: function(e)
        {
            e.targets.filter('[data-parent!="' + e.element.attr('data-parent') + '"]').addClass('drop-not-allowed');
        },
        finish: function(e)
        {
            var products = '';
            e.list.each(function()
            {
                products += $(this.item).data('id') + ',' ;
            });
            $.post(createLink('product', 'updateOrder'), {'products' : products, 'orderBy' : orderBy});

            $('#productListForm').table('initNestedList');
        }
    });
});
