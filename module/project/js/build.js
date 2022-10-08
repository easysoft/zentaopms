$(function()
{
    $('#createBuild').click(function()
    {
        if(executions.length == 0)
        {
            var message = noDevStage;
            if(allExecutions.length == 0) message = createExecution;
            alert(message);

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
