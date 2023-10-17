$("#" + browseType + "Tab").addClass('btn-active-text');

/**
 * Set batch edit checkbox.
 *
 * @access public
 * @return void
 */
function setCheckbox()
{
    $('#productListForm .c-checkbox, #productListForm .check-all').hide();
    $('.c-name').css('border-left', 'none');
    $(":checkbox[name^='productIDList']").prop('checked', false);
    $('.check-all, .program-checkbox, .row-product').removeClass('checked');
    if($.cookie('showProductBatchEdit') == 1)
    {
        $('#productListForm .c-checkbox, #productListForm .check-all').show();
        $('.c-name').css('border-left', '1px solid #ddd');
    }
    else
    {
        $('.table-actions').hide();
        $('#productsCount').show();
    }
}

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

/**
 * Anti shake operation for jquery.
 *
 * @param  fn $fn
 * @param  delay $delay
 * @access public
 * @return void
 */
function debounce(fn, delay)
{
    var timer = null;
    return function()
    {
        if(timer) clearTimeout(timer);
        timer = setTimeout(fn, delay)
    }
}

/**
 * Update statistics.
 *
 * @access public
 * @return void
 */
function updateStatistic()
{
    debounce(addStatistic(), 200)
}

$(function()
{
    $('input[name^="showEdit"]').click(function()
    {
        $.cookie('showProductBatchEdit', $(this).is(':checked') ? 1 : 0, {expires: config.cookieLife, path: config.webRoot});
        setCheckbox();
    });
    setCheckbox();

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
});

/**
 * Get checked items.
 *
 * @access public
 * @return array
 */
function getCheckedItems()
{
    var checkedItems = [];
    $('#productListForm [name^=productIDList]:checked').each(function(index, ele)
    {
        checkedItems.push($(ele).val());
    });
    return checkedItems;
};
