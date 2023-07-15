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
    loadExecutionBuilds()
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
        let $executionPicker = $('[name="execution"]').zui('picker');
        if(data)
        {
            data = JSON.parse(data);
            $executionPicker.render({items: data});
            $executionPicker.$.changeState({value: '0'});
        }
    });
}
