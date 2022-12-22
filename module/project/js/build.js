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

$(function()
{
    $('td .execution').each(function()
    {
        $deleted = $(this).find('.label-danger');
        if($deleted.length > 0)
        {
            $td = $(this).closest('td');
            if($td.width() < $(this).width())
            {
                $(this).find('.executionName').css('display', 'inline-block').css('width', $td.width() - $deleted.width()).css('overflow', 'hidden').css('float', 'left');
            }
        }
    })
})
