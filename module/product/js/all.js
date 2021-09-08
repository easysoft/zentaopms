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

    /* Update program checkboxes */
    function updateCheckboxes()
    {
        var $tbody = $('#productTableList');
        $tbody.find('.program-checkbox').each(function()
        {
            var $checkbox       = $(this);
            var $tr             = $checkbox.closest('tr');
            var rowID           = $tr.data('id');
            var isAllRowChecked = !$tbody.find('tr[data-parent="' + rowID + '"] input:checkbox:not(:checked)').length;
            $checkbox.toggleClass('checked', isAllRowChecked);
        });
    }

    $('#productTableList').on('click', '.program-checkbox', function()
    {
        var $checkbox = $(this).toggleClass('checked');
        var $tr = $checkbox.closest('tr');
        var rowID = $tr.data('id');
        var checked = $checkbox.hasClass('checked');
        $('#productTableList').children('tr').each(function()
        {
            var $tr = $(this);
            var nestPath = $tr.attr('data-nest-path');
            if(!nestPath) return;
            if(!nestPath.split(',').includes(rowID)) return;
            var $checkbox = $tr.find('input:checkbox');
            if($checkbox.length) $checkbox.prop('checked', checked);
            else $tr.find('.program-checkbox').toggleClass('checked', checked);
        });
    });
    $('#productListForm').on('checkChange', updateCheckboxes);
    updateCheckboxes();
});
