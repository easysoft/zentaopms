/**
 * Change product.
 *
 * @param  int    $productID
 * @access public
 * @return void
 */
function changeProduct(productID)
{
    link = createLink('execution', 'build', 'executionID=' + executionID + '&type=product&param=' + productID);
    location.href = link;
}
