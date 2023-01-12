/**
 * Update prarent checkbox.
 *
 * @param  object $parent
 * @access public
 * @return void
 */
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

/**
 * Update all checkbox.
 *
 * @access public
 * @return void
 */
function updateCheckboxes()
{
    $('#productTableList').children('.row-program,.row-line').each(function()
    {
        updatePrarentCheckbox($(this))
    });
}

/**
 * Add a statistics prompt statement after the Edit button.
 *
 * @access public
 * @return void
 */
function addStatistic()
{
    var checkedLength = $(":checkbox[name^='productIDList']:checked").length;
    if(checkedLength > 0)
    {
        var summary = checkedProducts.replace('%s', checkedLength);
        if(cilentLang == "en" && checkedLength < 2) summary = summary.replace('products', 'product');

        var statistic = "<div id='productsSummary' class='statistic'>" + summary + "</div>";
        $('#productsCount').hide();
        $('#productsSummary').remove();
        $('#editBtn').after(statistic);
        $('.table-actions').show();
    }
    else
    {
        $('.table-actions').hide();
        $('#productsCount').show();
        $('#productsSummary').addClass('hidden');
    }
}

$(function()
{
    $('input[name^="showEdit"]').click(function()
    {
        var editProduct = $(this).is(':checked') ? 1 : 0;
        $.cookie('showProductBatchEdit', editProduct, {expires: config.cookieLife, path: config.webRoot});
        dtableWithZentao.render({checkable: editProduct,
          footer() {
              const statistic = () => {
                  const checkedCount = this.getChecks().length;
                  const text = editProduct && checkedCount ? checkedProjects.replace('%s', checkedCount) : productSummary;

                  return [{children: text, className: 'text-dark'}];
              };
              if(editProduct) return ['checkbox', 'toolbar', statistic, 'flex', 'pager'];
              return [statistic, 'flex', 'pager'];
          },
        });
    });

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

    $('.main-table').on('click', 'tr', function(e)
    {
        if($.cookie('showProductBatchEdit') == 1) updateStatistic();
    });

    $('#productTableList').on('click', 'tr', function(e)
    {
        if($.cookie('showProductBatchEdit') != 1) e.stopPropagation();
    });

    $('#productTableList').on('click', '.row-program,.row-line', function(e)
    {
        if($.cookie('showProductBatchEdit') != 1) return;
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

    var isEditMode = $('input#showEdit1').is(':checked');
    dtableWithZentao.render({
        checkable: isEditMode,
        footer() {
            const statistic = () => {
                const checkedCount = this.getChecks().length;
                const text = isEditMode && checkedCount ? checkedProjects.replace('%s', checkedCount) : productSummary;

                return [{children: text, className: 'text-dark'}];
            };
            if(isEditMode) return ['checkbox', 'toolbar', statistic, 'flex', 'pager'];
            return [statistic, 'flex', 'pager'];
        },
    });
});
