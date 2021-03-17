$(function()
{
    $('#createBuildButton').on('click', function()
    {
        var projectID = $('#project').val();
        parent.location.href = createLink('build', 'create', 'projectID=' + projectID);
    })
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
    link = createLink('projectbuild', 'browse', 'projectID=' + projectID + '&type=product&param=' + productID);
    location.href = link;
}
