$(document).ready(function()
{
    $("a.preview").modalTrigger({width:1000, type:'iframe'});
})

function loadBranches(productID)
{
    $('#branch').remove();
    $.get(createLink('branch', 'ajaxGetBranches', 'productID=' + productID), function(data)
    {
        if(data)
        {
            $('#product').closest('.input-group').append(data);
            $('#branch').css('width', '100px');
            $('#branch').val(productGroups[productID]['branch']);
        }
    });
}
