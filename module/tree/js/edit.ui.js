/**
 * Load branches by product.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function loadBranches()
{
    const productID  = $(['name=root']).val();
    const $branchBox = $('.branchBox');
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
    const productID = $('[name=root]').val();
    const branchID  = $('[name=branch]').val();
    if(typeof(branchID) == 'undefined') branchID = 0;

    ajaxLoadModules(productID, branchID);
}

function changeRoot()
{
    const productID      = $('[name=root]').val();
    const confirmMessage = type == 'doc' ? confirmRoot4Doc : confirmRoot;
    if(moduleRoot != productID)
    {
        if(type == 'docTemplate')
        {
            ajaxLoadModules(productID, 0, 'docTemplate', moduleID, '1');
        }
        else
        {
            zui.Modal.confirm(confirmMessage).then(result =>
            {
                if(result)
                {
                    ajaxLoadModules(productID, 0, type, moduleID);
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
        ajaxLoadModules(productID, 0, type, moduleID);
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
function ajaxLoadModules(productID, branchID, viewType = '', currentModuleID = 0, grade = 'all')
{
    if(!viewType) viewType = type;

    var link = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=' + viewType + '&branch=' + branchID + '&rootModuleID=0&returnType=html&fieldID=&extra=excludeModuleID=' + currentModuleID + ',noMainBranch,nodeleted,excludeRelated&currentModuleID=0&grade=' + grade);
    $.getJSON(link, function(data)
    {
        $parent = $('[name=parent]').zui('picker');
        $parent.render({items: data.items});
        $parent.$.setValue('');
    });
}
