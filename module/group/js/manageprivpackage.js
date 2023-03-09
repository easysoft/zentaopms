$(function()
{
    $list =  $('#privPackageTableList');
    $list.addClass('sortable').sortable(
    {
        selector: 'tr',
        dragCssClass: 'drag-row',
        trigger: $list.find('.sort-handler').length ? '.sort-handler' : null,
        before: function(e)
        {
            // if($(e.event.target).closest('a,.btn').length) return false;
        },
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
            var orders = '';
            var $parent = $(e.element).data('parent');
            var parentID = typeof $parent == 'object' ? $parent.id : 0;
            var grade    = typeof $parent == 'object' ? parseInt($parent.level + 1) : 1;
            $list.find('tr[data-parent="' + parentID + '"]').each(function(){
                orders += $(this).data('id') + ',';
            });

            $.post(createLink('group', 'sortPrivPackages', 'parent=' + parentID + '&grade=' + grade), {'orders': orders});

            e.element.addClass('drop-success');
            setTimeout(function(){e.element.removeClass('drop-success');}, 800);
            $list.children('.drop-not-allowed').removeClass('drop-not-allowed');
            $('#privPackageForm').table('initNestedList')
        }

    });
})
