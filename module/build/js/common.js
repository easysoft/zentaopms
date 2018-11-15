/**
 * Load branches 
 * 
 * @param  int $productID 
 * @access public
 * @return void
 */
function loadBranches(productID)
{
    $('#branch').remove();
    $('#branch_chosen').remove();
    $.get(createLink('branch', 'ajaxGetBranches', 'productID=' + productID + '&oldBranch=' + productGroups[productID]['branch']), function(data)
    {
        if(data)
        {
            $('#product').closest('.input-group').append(data);
            $('#branch').chosen();
        }
    });
}
