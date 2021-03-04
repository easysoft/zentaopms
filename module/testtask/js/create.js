/**
 * Load execution related builds
 *
 * @access public
 * @return void
 */
function loadProductRelated()
{
    loadExecutionBuilds(parseInt($('#execution').val()));
}

/* If the mouse hover over the manage contacts button, give tip. */
$(function()
{
    adjustPriBoxWidth();
    if($('#execution').val()) loadExecutionBuilds($('#execution').val());
});
