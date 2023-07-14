/**
 * Load execution related builds
 *
 * @access public
 * @return void
 */
function loadProductRelated()
{
    const productID = $('[name=product]').val();

    loadExecutions(productID);
    loadTestReports(productID);
    loadExecutionBuilds(productID)
}

/**
 * Load executions.
 *
 * @param  int    productID
 * @access public
 * @return void
 */
function loadExecutions(productID)
{
    $.get($.createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=' + projectID + '&branch='), function(data)
    {
        if(data)
        {
            data = JSON.parse(data);
            $('#execution').picker({items: data});
            $('#execution').picker('setValue', $('[name=execution]').val());
        }
    });
}

$(function()
{
    loadProductRelated();
});
