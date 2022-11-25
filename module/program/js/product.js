$(function()
{
    $('input[name^="showEdit"]').click(function()
    {
        $.cookie('showProductBatchEdit', $(this).is(':checked') ? 1 : 0, {expires: config.cookieLife, path: config.webRoot});
        setCheckbox();
    });
    setCheckbox();

    $(":checkbox[name^='productIDList']").on('click', function()
    {
        updateStatistic();
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
        updateStatistic();
    });

    $('.main-table').on('click', 'tr', function(e)
    {
        if($.cookie('showProductBatchEdit') == 1) updateStatistic();
    });
});


/**
 * Set batch edit checkbox.
 *
 * @access public
 * @return void
 */
function setCheckbox()
{
    $('#productListForm .checkbox-primary, #productListForm .check-all').hide();
    $('#productListForm .product-id').addClass('hidden');
    $(":checkbox[name^='productIDList']").prop('checked', false);
    $('.check-all, .sortable tr').removeClass('checked');
    if($.cookie('showProductBatchEdit') == 1)
    {
        $('#productListForm .checkbox-primary, #productListForm .check-all').show();
    }
    else
    {
        $('.table-actions').hide();
        $('#productListForm .product-id').removeClass('hidden');
    }
    updateStatistic();
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
