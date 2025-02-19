window.renderRowData = function($row, index, row)
{
    /* If there are multiple branch products, show the branches. */
    if(isBranchProduct == 1)
    {
        $row.find('[data-name="branch"]').find('.picker-box').on('inited', function(e, info)
        {
            let $branch = info[0];

            /* The product of current bug is multiple branch product, show branches in the select box. */
            if(products[row.product] != undefined && products[row.product].type != 'normal' && branchTagOption[row.product] != undefined)
            {
                let branches = branchTagOption[row.product];
                $branch.render({items: branches, disabled: false});
            }
            /* The product of current bug isn't multiple branch product, disable the input box. */
            else
            {
                $branch.render({disabled: true});
            }

            $branch.$.changeState({value: ''});
        });
    }

    /* Show the modules of current bug's product. */
    if(modules[row.product] != undefined && modules[row.product][row.branch] != undefined)
    {
        $row.find('[data-name="module"]').find('.picker-box').on('inited', function(e, info)
        {
            let bugModules = modules[row.product][row.branch];
            let $module    = info[0];
            $module.render({items: bugModules});
        });
    }

    /* Show the plans of current bug's product. */
    if(row.plans != undefined)
    {
        $row.find('[data-name="plan"]').find('.picker-box').on('inited', function(e, info)
        {
            let $plan    = info[0];
            $plan.render({items: row.plans});
        });
    }

    /* Show the bugs of current bug's product. */
    if(productBugOptions[row.product] != undefined && productBugOptions[row.product][row.branch] != undefined)
    {
        let duplicateBugs = JSON.parse(JSON.stringify(productBugOptions[row.product][row.branch]));
        duplicateBugs.forEach((duplicateBug, index) =>
        {
            if(duplicateBug.value == row.id)
            {
                duplicateBugs.splice(index, 1);
                return false;
            }
        })

        $row.find('[data-name="resolutionBox"]').find('.input-group').find('.duplicate-select').on('inited', function(e, info)
        {
            let $duplicateBug = info[0];
            $duplicateBug.render({items: duplicateBugs});
        });

        $row.find('[data-name="resolutionBox"]').find('.input-group').find('.duplicate-select').toggleClass('hidden', row.resolution != 'duplicate');
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

    $row.find('[data-name="product"]').val(row.product); // Set product value.

    /* Set project items. */
    $row.find('[data-name="project"]').find('.picker-box').on('inited', function(e, info)
    {
        let projectItems   = JSON.parse(JSON.stringify(productProjects[row.product]));
        const $project     = info[0];
        const isRequired   = typeof(noProductProjects[row.project]) != 'undefined';
        if(typeof(deletedProjects[row.project]) != 'undefined') projectItems.push(deletedProjects[row.project]);
        $project.render({items: projectItems, required: isRequired});
    });

    /* Set execution items. */
    $row.find('[data-name="execution"]').find('.picker-box').on('inited', function(e, info)
    {
        const isDisabled     = typeof(noSprintProjects[row.project]) != 'undefined';
        let executionItems   = isDisabled || typeof(productExecutions[row.product]) == 'undefined' || typeof(productExecutions[row.product][row.project]) == 'undefined' ? [] : JSON.parse(JSON.stringify(productExecutions[row.product][row.project]));
        const $execution     = info[0];
        if(typeof(deletedExecutions[row.execution]) != 'undefined') executionItems.push(deletedExecutions[row.execution]);
        $execution.render({items: executionItems, disabled: isDisabled});
    });

    /* Set openedBuild items. */
    $row.find('[data-name="openedBuild"]').find('.picker-box').on('inited', function(e, info)
    {
        let openedBuildItems = [];
        if(row.execution > 0)
        {
            openedBuildItems = executionOpenedBuilds[row.execution];
        }
        else if(row.project > 0)
        {
            openedBuildItems = projectOpenedBuilds[row.project];
        }
        else
        {
            openedBuildItems = productOpenedBuilds[row.product];
        }

        const $openedBuild = info[0];
        $openedBuild.render({items: openedBuildItems, menu: {checkbox: true}});
    });
}

function setDuplicate(event)
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const resolution  = $target.val();

    $currentRow.find('[data-name="duplicateBug"]').toggleClass('hidden', resolution != 'duplicate');
}

function projectChange(event)
{
    const $row      = $(event.target).closest('tr');
    const projectID = $(event.target).val();

    $row.find('input[name^="execution"]').zui('picker').render({disabled: typeof(noSprintProjects[projectID]) != 'undefined'});
    if(typeof(noProductProjects[projectID]) != 'undefined')
    {
        const productIDLink = $.createLink('bug', 'ajaxGetProductIDByProject', 'projectID=' + projectID);
        $.get(productIDLink, function(id)
        {
            $row.find('[name^=product]').val(id);
            loadExecutions($row, id, projectID);
            loadProductModules($row, id)
            loadProductPlans($row, id);
            loadProductBugs($row, id);
        });
    }
    else
    {
        const productID = $row.find('[name^=product]').val();
        loadExecutions($row, productID, projectID);
    }
}

function loadExecutions($row, productID, projectID)
{
    let branch = $row.find('[name^="branch"]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link              = $.createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=' + projectID + '&branch=' + branch + '&pageType=&executionID=&from=&mode=stagefilter');
    const isMultipleProject = typeof(projectExecutions[projectID]) != 'undefined';
    const executionID       = isMultipleProject ? projectExecutions[projectID] : $row.find('[name^=execution]').val();
    $.getJSON(link, function(data)
    {
        let $executionPicker    = $row.find('[name^=execution]').zui('picker');
        $executionPicker.render({items: data});
        $executionPicker.$.setValue(isMultipleProject ? '' : executionID);
    });

    const isLoadBuild = (!isMultipleProject && !executionID) || (isMultipleProject);
    if(isLoadBuild) projectID != 0 ? loadProjectBuilds($row, projectID) : loadProductBuilds($row, productID);
}

function loadProjectBuilds($row, projectID)
{
    let branch = $row.find('[name^=branch]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const productID      = $row.find('[name^=product]').val();
    const oldOpenedBuild = $row.find('[name^=openedBuild]').val() ? $row.find('[name^=openedBuild]').val().toString() : 0;
    const openedLink     = $.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch);
    $.getJSON(openedLink, function(data)
    {
        let $buildPicker = $row.find('[name^=openedBuild]').zui('picker');
        $buildPicker.render({items: data});
        $buildPicker.$.setValue(oldOpenedBuild);
    })
}

function loadProductBuilds($row, productID)
{
    let branch = $row.find('[name^=branch]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const oldOpenedBuild = $row.find('[name^=openedBuild]').val() ? $row.find('[name^=openedBuild]').val().toString() : 0;
    const openedLink     = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch);
    $.getJSON(openedLink, function(data)
    {
        let $buildPicker = $row.find('[name^=openedBuild]').zui('picker');
        $buildPicker.render({items: data});
        $buildPicker.$.setValue(oldOpenedBuild);
    });
}

function loadProductModules($row, productID)
{
    let branch = $('[name^=branch]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const moduleID = $row.find('[name^=module]').val();
    const link     = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug&branch=' + branch + '&rootModuleID=0&returnType=items&fieldID=&extra=nodeleted&currentModuleID=' + moduleID);
    $.getJSON(link, function(data)
    {
        const $modulePicker = $row.find('[name^=module]').zui('picker');
        $modulePicker.render({items: data});
        $modulePicker.$.setValue(moduleID);
    });
}

function loadProductPlans($row, productID)
{
    let branch = $row.find('[name^=branch]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('productplan', 'ajaxGetProductplans', 'productID=' + productID + '&branch=' + branch);
    $.getJSON(link, function(data)
    {
        const planID      = $row.find('[name^=plan]').val();
        const $planPicker = $row.find('[name^=plan]').zui('picker');
        $planPicker.render({items: data});
        $planPicker.$.setValue(planID);
    });
}

function loadProductBugs($row, productID)
{
    const bugID = $row.find('[name^=id]').val();
    const link  = $.createLink('bug', 'ajaxGetProductBugs', 'productID=' + productID + '&bugID=' + bugID);
    $.getJSON(link, function(data)
    {
        const duplicateBugID = $row.find('[name^=duplicateBug]').val();
        const $bugPicker     = $row.find('[name^=duplicateBug]').zui('picker');
        $bugPicker.render({items: data});
        $bugPicker.$.setValue(duplicateBugID);
    });
}

function branchChange(event)
{
    const $row      = $(event.target).closest('tr');
    const productID = $row.find('[name^=product]').val();
    const projectID = $row.find('[name^=project]').val();
    loadProductModules($row, productID);
    loadProductBuilds($row, productID);
    loadProductPlans($row, productID);

    projectID != 0 ? loadProjectBuilds($row, projectID) : loadProductBuilds($row, productID);
}

function executionChange(event)
{
    const $row        = $(event.target).closest('tr');
    const projectID   = $row.find('[name^=project]').val();
    const executionID = $row.find('[name^=execution]').val();
    if(executionID != 0)
    {
        loadProjectByExecutionID($row, executionID);
        loadExecutionBuilds($row, executionID);
    }
    else if(projectID != 0)
    {
        loadProjectBuilds($row, projectID);
    }
    else
    {
        const productID = $row.find('[name^=product]').val();
        loadProductBuilds($row, productID);
    }
}

function loadProjectByExecutionID($row, executionID)
{
    const link = $.createLink('project', 'ajaxGetPairsByExecution', 'executionID=' + executionID, 'json');
    $.post(link, function(data)
    {
        $projectPicker = $row.find('[name^=project]').zui('picker');
        $projectPicker.$.setValue(data.id.toString());
    }, 'json')
}

function loadExecutionBuilds($row, executionID)
{
    const branch         = $row.find('[name^=branch]').val();
    const productID      = $row.find('[name^=product]').val();
    const oldOpenedBuild = $row.find('[name^=openedBuild]').val() ? $row.find('[name^=openedBuild]').val().toString() : 0;

    if(typeof(branch) == 'undefined') branch = 'all';

    const openedLink = $.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&needCreate=false&type=normal');
    $.getJSON(openedLink, function(data)
    {
        let $buildPicker = $row.find('[name^=openedBuild]').zui('picker');
        $buildPicker.render({items: data});
        $buildPicker.$.setValue(oldOpenedBuild);
    });
}
