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
    if(!productID) productID = $('input[name=product]').val();
    if($('input[name=isIntegrated]:checked').val() == 'yes')
    {
        $('[name^=branch]').closest('.form-row').addClass('hidden');
        return false;
    }

    $.get($.createLink('branch', 'ajaxGetBranches', 'productID=' + productID + '&oldBranch=0&param=active&projectID=' + $('input[name=execution]').val() + '&withMainBranch=true&isSiblings=no&fieldID=0&multiple=multiple'), function(data)
    {
        if(data.length > 0)
        {
            const $branchPicker = $('[name^=branch]').zui('picker');
            $branchPicker.render({items: data});
            $branchPicker.$.setValue('');
            $('[name^=branch]').closest('.form-row').removeClass('hidden');
        }
        else
        {
            $('[name^=branch]').closest('.form-row').addClass('hidden');
        }
    }, 'json');
}

window.setSystemBox = function(e)
{
    const newSystem = $(e.target).is(':checked') ? 1 : 0;
    $('#systemBox #systemName').addClass('hidden');
    $('#systemBox .picker-box').addClass('hidden');
    if(newSystem == 1)
    {
        $('#systemBox #systemName').removeClass('hidden');
    }
    else
    {
        $('#systemBox #systemName').val('');
        $('#systemBox .picker-box').removeClass('hidden');
    }
}
