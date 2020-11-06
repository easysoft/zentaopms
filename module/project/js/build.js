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
