/**
 * Load execution related builds
 *
 * @access public
 * @return void
 */
function loadProductRelated()
{
    loadExecutions($('#product').val());
    buildData = '<select id="build" name="build" class="form-control" onchange="loadTestReports(this.value)"></select>';
    $('#build').replaceWith(buildData);
    $('#build_chosen').remove();
    $("#build").chosen();
    $('#build').trigger("chosen:updated");
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
    link = createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=' + projectID);
    $.get(link, function(data)
    {
        if(!data) data = '<select id="execution" name="execution" class="form-control"></select>';
        $('#execution').replaceWith(data);
        $('#execution_chosen').remove();
        $("#execution").chosen();
    });
}

/* If the mouse hover over the manage contacts button, give tip. */
$(function()
{
    adjustPriBoxWidth();
    if($('#execution').val()) loadExecutionBuilds($('#execution').val());
});
