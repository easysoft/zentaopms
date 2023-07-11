window.handleRenderRow = function($row, index, row)
{
    /* Set the branches for the row. */
    let $branch        = $row.find('.form-batch-input[data-name=branch]').empty();
    let currentProduct = productID ? productID : row.product;

    if(products[currentProduct] != undefined && products[currentProduct].type != 'normal' && branchPairs[currentProduct] != undefined)
    {
        $branch.removeAttr('disabled');

        let branches = branchPairs[currentProduct];
        $.each(branches, function(branchID, branchName){$branch.append('<option value="' + branchID + '">' + branchName + '</option>');});
    }
    else
    {
        $branch.attr('disabled', 'disabled');
    }

    /* Set the modules for the row. */
    let $module = $row.find('.form-batch-input[data-name=module]').empty();
    let modules = modulePairs[row.id];
    $.each(modules, function(moduleID, moduleName){ $module.append('<option value="' + moduleID + '">' + moduleName + '</option>'); });

    /* Set the scenes for the row. */
    let $scene = $row.find('.form-batch-input[data-name=scene]').empty();
    let scenes = scenePairs[row.id];
    $.each(scenes, function(sceneID, sceneName){ $scene.append('<option value="' + sceneID + '">' + sceneName + '</option>'); });
}

function loadBranches()
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const branch      = $target.val();
    const caseID      = $currentRow.find('.form-batch-input[data-name=caseIdList]').val();
    const product     = productID ? productID : cases[caseID].product;
    console.log(cases);
    const oldBranch   = cases[caseID].branch;

    if(typeof(branch) == 'undefined') branch = 0;
    if(!branch) branch = 0;

    var result = true;
    if(branch)
    {
        var endLoop = false;
        for(index in testtasks)
        {
            if(endLoop) break;
            if(index == caseID)
            {
                endLoop = true;
                for(taskID in testtasks[index])
                {
                    if(branch != oldBranch && testtasks[index][taskID]['branch'] != branch)
                    {
                        var tip = confirmUnlinkTesttask.replace("%s", caseID);
                        result  = confirm(tip);
                        if(!result) $('#branch' + caseID).val(oldBranch);
                        break;
                    }
                }
            }
        }
    }

    if(result)
    {
        var currentModuleID = $('#module' + caseID).val();
        moduleLink          = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + product + '&viewtype=case&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=' + caseID + '&needManage=false&extra=nodeleted&currentModuleID=' + currentModuleID);
        $('#module' + caseID).parent('td').load(moduleLink);

        loadStories(product, 0, caseID);
    }
}
