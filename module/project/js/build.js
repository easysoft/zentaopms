$(function()
{
    $('#createBuild').click(function()
    {
        if(executions.length == 0)
        {
            alert(createExecution);
            location.href = createLink('execution', 'create');
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
    link = createLink('project', 'build', 'type=product&param=' + productID);
    location.href = link;
}
