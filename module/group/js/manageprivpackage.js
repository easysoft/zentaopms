$(function()
{
    $list =  $('#privPackageTableList');
    if(canSortPackage)
    {
        $list.addClass('sortable').sortable(
        {
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
                var orders   = '';
                var $item    = $(e.element);
                var type     = $item.data('type');
                var parentID = type == 'view' ? 0 : $(e.element).data('parent').id;
                $list.find('tr[data-parent="' + parentID + '"]').each(function(){
                    orders += $(this).data('id') + ',';
                });

                $.post(createLink('group', 'sortPrivPackages', 'parent=' + parentID + '&type=' + type), {'orders': orders});

                $list.children('.drop-not-allowed').removeClass('drop-not-allowed');
                $('#privPackageForm').table('initNestedList')
            }
        });
    }
})
