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
    $row.find('.form-batch-control[data-name="module"] .picker-box').on('inited', function(e, info)
    {
        let $module = info[0];
        $module.render({items: modulePairs[row.id]});
        $module.$.setValue(row.module);
    });

    /* Set the scenes for the row. */
    $row.find('.form-batch-control[data-name="scene"] .picker-box').on('inited', function(e, info)
    {
        let $scene = info[0];
        $scene.render({items: scenePairs[row.id]});
        $scene.$.setValue(row.scene);
    });
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
