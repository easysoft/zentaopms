$("#" + browseType + "Tab").addClass('btn-active-text');
$(function()
{
    /* Init table sort. */
    $('#productTableList').addClass('sortable').sortable(
    {
        /* Init vars. */
        reverse: orderBy === 'order_desc',
        selector: 'tr',
        dragCssClass: 'drag-row',
        trigger: '.sort-handler',

        /* Set movable conditions. */
        canMoveHere: function($ele, $target)
        {
            var canMove = true;
            if($ele.hasClass('no-nest')) canMove = $target.hasClass('no-nest') ? true : false;
            return $ele.data('parent') === $target.data('parent') && canMove;
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

    /* Update parent checkbox */
    function updatePrarentCheckbox($parent)
    {
        var $row          = $parent.closest('tr');
        var $checkbox     = $row.find('.program-checkbox');
        var rowID         = $row.data('id');
        var $subRows      = $('#productTableList').children('.row-product[data-nest-path^="' + rowID + ',"],.row-product[data-nest-path*=",' + rowID + ',"]');
        var allCount      = $subRows.length;
        var selectedCount = $subRows.find('input:checkbox:checked').length;
        var isAllChecked  = allCount > 0 && allCount === selectedCount;
        $checkbox.toggleClass('checked', isAllChecked)
            .toggleClass('indeterminate', selectedCount > 0 && selectedCount < allCount);
        $row.toggleClass('checked', isAllChecked);
    }

    /* Update checkboxes */
    function updateCheckboxes()
    {
        $('#productTableList').children('.row-program,.row-line').each(function()
        {
            updatePrarentCheckbox($(this))
        });
    }

    /* Add a statistics prompt statement after the Edit button */
    function addStatistic()
    {
        var checkedLength = $(":checkbox[name^='productIDList']:checked").length;
        var summary       = checkedProducts.replace('%s', checkedLength);
        var statistic     = "<div id='productsSummary' class='statistic'>" + summary + "</div>";
        if(checkedLength > 0)
        {
            $('#productsSummary').remove();
            $('#editBtn').after(statistic);
        }
        else
        {
            $('#productsSummary').addClass('hidden');
        }
    }

    function debounce(fn,delay)
    {
        var timer = null;
        return function()
        {
            if(timer) clearTimeout(timer);
            timer = setTimeout(fn,delay)
        }
    }

    function updateStatistic()
    {
        debounce(addStatistic(), 200)
    }

    $('#productTableList').on('click', '.row-program,.row-line', function(e)
    {
        if($(e.target).closest('.table-nest-toggle,a').length) return;

        var $row      = $(this);
        var $checkbox = $row.find('.program-checkbox').toggleClass('checked').removeClass('indeterminate');
        var isChecked = $checkbox.hasClass('checked');
        var rowID     = $row.data('id');
        var $subRows  = $('#productTableList').children('tr[data-nest-path^="' + rowID + ',"],tr[data-nest-path*=",' + rowID + ',"]');
        $row.toggleClass('checked', isChecked);
        $subRows.toggleClass('checked', isChecked);
        $subRows.find('input:checkbox').prop('checked', isChecked);
        $subRows.find('.program-checkbox').toggleClass('checked', isChecked).removeClass('indeterminate');

        var parentID = $row.attr('data-parent');
        if(parentID && parentID !== '0')
        {
            updatePrarentCheckbox($('#productTableList>tr[data-id="' + parentID + '"]'));
        }
        updateStatistic()
    });

    $('#productListForm').on('checkChange', updateCheckboxes);
    updateCheckboxes();

    $(":checkbox[name^='productIDList']").on('click', function()
    {
        updateStatistic()
    });

    $(".check-all").on('click', function()
    {
        if($(":checkbox[name^='productIDList']:not(:checked)").length == 0)
        {
            $(":checkbox[name^='productIDList']").prop('checked', false);
        }
        else
        {
            $(":checkbox[name^='productIDList']").prop('checked', true);
        }
        updateStatistic()
    });
});
