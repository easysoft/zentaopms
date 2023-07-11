/**
 * Load branches
 *
 * @param  int $productID
 * @access public
 * @return void
 */
window.loadBranches = function(productID)
{
    productID = parseInt(productID);
    if(!productID) productID = $(this).val();
    if($('input[name=isIntegrated]:checked').val() == 'yes')
    {
        $('#branch').closest('.form-row').addClass('hidden');
        return false;
    }

    let oldBranch = 0;
    if(typeof(productGroups[productID]) != "undefined")
    {
        oldBranch = productGroups[productID]['branches'];
    }

    $.get($.createLink('branch', 'ajaxGetBranches', 'productID=' + productID + '&oldBranch=0&param=active&projectID=' + $('#execution').val() + '&withMainBranch=true&isSiblings=no&fieldID=0&multiple=multiple'), function(data)
    {
        if(data)
        {
            $('#branch').replaceWith(data);
            $('#branch').closest('.form-row').removeClass('hidden');
        }
        else
        {
            $('#branch').closest('.form-row').addClass('hidden');
        }
    });
}
