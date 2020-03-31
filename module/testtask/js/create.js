/**
 * Load project related builds
 * 
 * @access public
 * @return void
 */
function loadProductRelated()
{
    loadProjectBuilds(parseInt($('#project').val()));
}

/* If the mouse hover over the manage contacts button, give tip. */
$(function()
{
    adjustPriBoxWidth();
    if($('#project').val()) loadProjectBuilds($('#project').val());
});
