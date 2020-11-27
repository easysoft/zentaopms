$(document).ready(function()
{
    $("a.preview").modalTrigger({width:1000, type:'iframe'});
})

function loadProduct(productID)
{
    if(typeof parentStory != 'undefined' && parentStory)
    {
        confirmLoadProduct = confirm(moveChildrenTips);
        if(!confirmLoadProduct)
        {
            $('#product').val(oldProductID);
            $('#product').trigger("chosen:updated");
            return false;
        }
    }

    if(typeof hasSR != 'undefined' && hasSR)
    {
        confirmLoadProduct = confirm(moveSRTips);//Set hasSR variable in pro and biz.
        if(!confirmLoadProduct)
        {
            $('#product').val(oldProductID);
            $('#product').trigger("chosen:updated");
            return false;
        }
    }

    oldProductID = $('#product').val();
    loadProductBranches(productID)
}

function loadBranch()
{
    var branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
}

function loadProductBranches(productID)
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
            $('#branch').css('width', config.currentMethod == 'create' ? '120px' : '65px').chosen();
        }
        $inputGroup.fixInputGroup();
    })
}
