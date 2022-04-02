$(function()
{
    $('#createBuild').click(function()
    {
        if(executions.length == 0)
        {
            alert(createExecution);
            var link = createLink('execution', 'create', 'projectID=' + projectID);

            window.parent.$.apps.open(link, 'execution');
            return false;
        }
    });
});

/**
 * Change product.
 *
 * @param  int    $productID
 * @access public
 * @return void
 */
function changeProduct(productID)
{
    link = createLink('project', 'build', 'projectID=' + projectID + '&type=product&param=' + productID);
    location.href = link;
}
