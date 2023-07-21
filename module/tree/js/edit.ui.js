/**
 * Load branches by product.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function loadBranches(e)
{
    var productID  = $(e.target).val();
    var $branchBox = $('.branchBox');
    $branchBox.addClass('hidden');
    $.get($.createLink('branch', 'ajaxGetBranches', "productID=" + productID + "&oldBranch=0&param=withClosed"), function(data)
    {
        if(data)
        {
            $('[name=branch]').zui('picker').render({items: JSON.parse(data), defaultValue: 0, name: 'branch'});
            $branchBox.removeClass('hidden');
        }
        ajaxLoadModules(productID, 0);
    })
}

/**
 * Load modules by product and branch.
 *
 * @param  obj $branch
 * @access public
 * @return void
 */
function loadModules(e)
{
    var productID = $('[name=root]').val();
    var branchID  = $(e.target).val();

    if(typeof(branchID) == 'undefined') branchID = 0;

    console.log(productID)
    ajaxLoadModules(productID, branchID);
}

/**
 * Ajax load modules by product and branch.
 *
 * @param  int $productID
 * @param  int $branchID
 * @access public
 * @return void
 */
function ajaxLoadModules(productID, branchID)
{
    var link = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=' + type + '&branch=' + branchID + '&rootModuleID=0&returnType=html&fieldID=&needManage=false&extra=excludeModuleID=noMainBranch,nodeleted');
    $.get(link, function(data)
    {
        $('.moduleBox #parent').remove();
        $('.moduleBox').append("<div class='form-group-wrapper picker-box' id='parent'></div>");
        new zui.Picker('.moduleBox #parent', JSON.parse(data));
    });
}
