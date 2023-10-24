/**
 * Change product.
 *
 * @param  int    $productID
 * @access public
 * @return void
 */
function changeProduct(productID)
{
    link = createLink(fromModule, fromMethod, 'projectID=' + projectID + '&type=product&param=' + productID);
    location.href = link;
}

$(function()
{
    $('td .execution .label-danger').each(function()
    {
        $execution = $(this).closest('.execution');
        $td        = $(this).closest('td');
        if($td.width() < $execution.width())
        {
            $execution.find('.executionName').css('display', 'inline-block').css('width', $td.width() - $(this).width()).css('overflow', 'hidden').css('float', 'left');
        }
    })

    $('td.c-name .build .icon-code-fork').each(function()
    {
        $build = $(this).closest('.build');
        $td    = $(this).closest('td');
        if($td.width() < $build.width())
        {
            $build.find('.buildName').css('display', 'inline-block').css('width', $td.width() - $(this).width()).css('overflow', 'hidden').css('float', 'left');
        }
    })
})
