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
        data = JSON.parse(data);
        if(data.length > 0)
        {
            $branch = $('[name=branch]').zui('picker');
            $branch.render({items: data});
            $branch.$.setValue('');
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

    ajaxLoadModules(productID, branchID);
}

function changeRoot()
{
    var root = $('[name=root]').val();
    var confirmRoot = type == 'doc' ? confirmRoot4Doc : confirmRoot;
    if(moduleRoot != root)
    {
        if(type == 'docTemplate')
        {
            ajaxLoadModules(root, 0, 'docTemplate', moduleID);
        }
        else
        {
            zui.Modal.confirm(confirmRoot).then(result =>
            {
                if(result)
                {
                    ajaxLoadModules(root, 0, type != 'doc' ? 'story' : type, moduleID);
                }
                else
                {
                    $('[name=root]').zui('picker').$.setValue(moduleRoot);
                }
            });
        }
    }
    else
    {
        ajaxLoadModules(root, 0, type != 'doc' ? 'story' : type, moduleID);
    }
}

/**
 * Ajax load modules by product and branch.
 *
 * @param  int    $productID
 * @param  int    $branchID
 * @param  string $viewType
 * @param  int    $currentModuleID
 * @access public
 * @return void
 */
function ajaxLoadModules(productID, branchID, viewType = '', currentModuleID = 0)
{
    if(!viewType) viewType = type;

    var link = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=' + viewType + '&branch=' + branchID + '&rootModuleID=0&returnType=html&fieldID=&extra=excludeModuleID=' + currentModuleID + ',noMainBranch,nodeleted,excludeRelated');
    $.getJSON(link, function(data)
    {
        $parent = $('[name=parent]').zui('picker');
        $parent.render({items: data.items});
        $parent.$.setValue('');
    });
}
