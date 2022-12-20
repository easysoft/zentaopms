/**
 * Load branches
 *
 * @param  int $productID
 * @access public
 * @return void
 */
function loadBranches(productID)
{
    if($('input[name=isIntegrated]:checked').val() == 'yes')
    {
        $('#branchBox').closest('tr').addClass('hidden');
        return false;
    }
    $('#branch').remove();
    $('#branch_chosen').remove();
    var oldBranch = 0;
    if(typeof(productGroups[productID]) != "undefined")
    {
        oldBranch = productGroups[productID]['branches'];
    }

    $.get(createLink('branch', 'ajaxGetBranches', 'productID=' + productID + '&oldBranch=0&param=active&projectID=' + $('#execution').val() + '&withMainBranch=true&isSiblings=no&fieldID=0&multiple=multiple'), function(data)
    {
        if(data)
        {
            $('#branchBox').append(data);
            $('#branch').chosen();
            $('#branchBox').closest('tr').removeClass('hidden');
        }
        else
        {
            $('#branchBox').closest('tr').addClass('hidden');
        }
    });
}

function loadBranch() {return false;}
