/**
 * Load execution related builds
 *
 * @access public
 * @return void
 */
function loadProductRelated()
{
    loadExecutions($('#product').val());
    loadTestReports($('#product').val());
    loadExecutionBuilds($('#execution').val())
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
    var executionID = $('#execution').val();
    link = createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=' + projectID + '&branch=');
    $.get(link, function(data)
    {
        if(!data) data = '<select id="execution" name="execution" class="form-control"></select>';
        $('#execution').replaceWith(data);
        $('#execution_chosen').remove();
        $("#execution").val(executionID);
        $("#execution").chosen();
    });
}

/* If the mouse hover over the manage contacts button, give tip. */
$(function()
{
    adjustPriBoxWidth();
    if($('#execution').val() != 0 && multiple) loadExecutionBuilds($('#execution').val());
});
