window.renderRowData = function($row, index, row)
{
    /* If there are multiple branch products, show the branches. */
    if(isBranchProduct == 1)
    {
        let $branch = $row.find('.form-batch-input[data-name="branch"]').empty();

        /* The product of current bug is multiple branch product, show branches in the select box. */
        if(products[row.product] != undefined && products[row.product].type != 'normal' && branchTagOption[row.product] != undefined)
        {
            $branch.removeAttr('disabled');
            let bugBranches = branchTagOption[row.product];
            $.each(bugBranches, function(branchID, branchName)
            {
                $branch.append('<option value="' + branchID + '">' + branchName + '</option>');
            });
        }
        /* The product of current bug isn't multiple branch product, disable the input box. */
        else
        {
            $branch.attr('disabled', 'disabled');
        }
    }

    /* Show the modules of current bug's product. */
    if(modules[row.product] != undefined && modules[row.product][row.branch] != undefined)
    {
        let bugModules = modules[row.product][row.branch];
        let $module    = $row.find('.form-batch-input[data-name="module"]').empty();

        $.each(bugModules, function(moduleID, moduleName)
        {
            $module.append('<option value="' + moduleID + '">' + moduleName + '</option>');
        });
    }

    /* Show the bugs of current bug's product. */
    if(productBugList[row.product] != undefined && productBugList[row.product][row.branch] != undefined)
    {
        let duplicateBugs = productBugList[row.product][row.branch];
        let $duplicateBug = $row.find('.form-batch-input[data-name="duplicateBug"]').empty();

        $.each(duplicateBugs, function(duplicateBugID, duplicateBugName)
        {
            if(duplicateBugID != row.id) $duplicateBug.append('<option value="' + duplicateBugID + '">' + duplicateBugName + '</option>');
        });
    }

    /* Change assigner. */
    let $assignedTo = $row.find('.form-batch-input[data-name="assignedTo"]');
    if(row.status == 'closed')
    {
        /* If the status of the bug is closed, assigner of the bug is closed. */
        $assignedTo.replaceWith('<input class="form-control form-batch-input" name="assignedTo[' + row.id + ']" id="assignedTo_0" data-name="assignedTo" disabled="disabled" value="' + row.assignedTo.slice(0, 1).toUpperCase() + row.assignedTo.slice(1) + '">');
    }
    else if(tab == 'project' || tab == 'execution')
    {
        let assignedToList;
        if(row.execution != 0)
        {
            /* If bug has execution, assigner of the bug is all members of the execution. */
            assignedToList = executionMembers[row.execution];
        }
        else if(row.project != 0)
        {
            /* If bug has project, assigner of the bug is all members of the project. */
            assignedToList = projectMembers[row.project];
        }
        else if(productMembers.length > 0)
        {
            /* If bug has product and the product has members, assigner of the bug is all members of the product. */
            assignedToList = productMembers[row.product];
        }

        /* If the assignedToList is defined, replace assigner with assignedToList. */
        if(assignedToList != undefined)
        {
            $assignedTo.empty();
            $assignedTo.append('<option value=""></option>');
            $.each(assignedToList, function(assignedToID, assignedToName)
            {
                if(assignedToID != row.id) $assignedTo.append('<option value="' + assignedToID + '">' + assignedToName + '</option>');
            });
        }
    }
    $assignedTo.find('option[value="closed"]').remove();
}

function setDuplicate(event)
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const resolution  = $target.val();
    if(resolution == 'duplicate')
    {
        $currentRow.find('.form-batch-input[data-name="duplicateBug"]').removeClass('hidden');
    }
    else
    {
        $currentRow.find('.form-batch-input[data-name="duplicateBug"]').addClass('hidden');
    }
}
