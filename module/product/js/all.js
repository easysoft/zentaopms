$("#" + browseType + "Tab").addClass('btn-active-text');
$(function()
{
    /* Init table sort. */
    var $list = $('#productTableList');
    $list.addClass('sortable').sortable(
    {
        /* Init vars. */
        reverse: orderBy === 'order_desc',
        selector: 'tr',
        dragCssClass: 'drag-row',
        trigger: $list.find('.sort-handler').length ? '.sort-handler' : null,

        /* Set movable conditions. */
        canMoveHere: function($ele, $target)
        {
            return $ele.data('parent') === $target.data('parent');
        },
        start: function(e)
        {
            e.targets.filter('[data-parent!="' + e.element.attr('data-parent') + '"]').addClass('drop-not-allowed');
        },

        /* Update order sort. */
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

    $('#productListForm').on('click', '.check-all, [id^="productIDList"]', function()
    {
        setTimeout(function()
        {
            var allCount     = $("[id^='productIDList']").length;
            var checkedCount = $("[id^='productIDList']:checked").length;

            if(allCount == checkedCount) $('.check-all').addClass('checked');
            if(allCount != checkedCount) $('.check-all').removeClass('checked');
        }, 100)
    });
});
