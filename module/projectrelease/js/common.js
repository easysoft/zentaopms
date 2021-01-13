$(document).ready(function()
{
    $("a.preview").modalTrigger({width:1000, type:'iframe'});
})

/**
 * Determined whether to show the productis.
 *
 * @param  int    $build
 * @access public
 * @return void
 */
function showProducts(build)
{
    if(build)  $('#productBox').hide();
    if(!build) $('#productBox').show();
}

/**
 * Flush the branch when switching products.
 *
 * @param  int    $productID
 * @access public
 * @return void
 */
function loadBranches(productID)
{
    $('#branch').remove();
    $('#branch_chosen').remove();
    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + productID), function(data)
    {
        var $product = $('#product');
        var $inputGroup = $product.closest('.input-group');
        $inputGroup.find('.input-group-addon').toggleClass('hidden', !data);
        if(data)
        {
            $inputGroup.append(data);
            $('#branch').css('width', '120px').chosen();
        }
        $inputGroup.fixInputGroup();
    })
}
