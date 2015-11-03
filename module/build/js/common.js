$(document).ready(function()
{
    $("a.preview").modalTrigger({width:1000, type:'iframe'});
})

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
    $.get(createLink('branch', 'ajaxGetBranches', 'productID=' + productID + '&oldBranch=' + productGroups[productID]['branch']), function(data)
    {
        if(data)
        {
            $('#product').closest('.input-group').append(data);
            $('#branch').css('width', '100px');
        }
    });
}
