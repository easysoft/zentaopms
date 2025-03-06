$(function()
{
    $('#subNavbar a[data-toggle=dropdown]').parent().addClass('dropdown dropdown-hover');
});

function changeProduct(event)
{
    const productID = $(event.target).val();
    if(productID == undefined) return false;

    if(typeof(changeProductConfirmed) != 'undefined' && !changeProductConfirmed && productID != bug.productID && (typeof(isShadowProduct) == 'undefined' || !isShadowProduct))
    {
        zui.Modal.confirm({message: confirmChangeProduct, onResult: function(result)
        {
            if(result)
            {
                changeProductConfirmed = true; // Only notice the user one time.
                changeProduct(event);
            }
            else
            {
                $('[name=product]').zui('picker').$.setValue(bug.product); // Revert old product id if confirm is no.
            }
        }});
    }
    else
    {
        loadProductBranches(productID)
        loadProductModules(productID);
        loadProductProjects(productID);
        loadExecutions(productID);
        loadAssignedTo(productID);
        loadProductBuilds(productID);
        loadProductPlans(productID);
        loadProductCases(productID);
        loadProductStories(productID, bug.storyID);
        loadProductBugs(productID, bug.id);
        if(methodName == 'edit' && edition == 'max') loadIdentify();
    }
}

function changeBranch(event)
{
    const productID = $('[name="product"]').val();

    loadProductModules(productID);
    loadProductProjects(productID);
    loadExecutions(productID);
    loadAssignedTo(productID);
    loadProductBuilds(productID);
    loadProductPlans(productID);
    loadProductCases(productID);
    loadProductStories(productID, bug.storyID);
}

function changeProject(event)
{
    const projectID       = $(event.target).val();
    const projectInfoLink = $.createLink('bug', 'ajaxGetProjectInfo', 'projectID=' + projectID);
    $.getJSON(projectInfoLink, function(project)
    {
        $('#executionBox').closest('tr').toggleClass('hidden', project.multiple == 0);
    });

    if(config.currentMethod == 'edit' && isShadowProduct)
    {
        const productIDLink = $.createLink('bug', 'ajaxGetProductIDByProject', 'projectID=' + projectID);
        $.get(productIDLink, function(id)
        {
            const productID      = id;
            const $productPicker = $('.detail-side [name=product]').zui('picker');
            $productPicker.$.setValue(productID);

            loadExecutionLabel(projectID);
            loadExecutions(productID, projectID);
            loadAssignedTo(productID, projectID);
            if(methodName == 'edit' && edition == 'max') loadIdentify();
        });
    }
    else
    {
        const productID = $('[name="product"]').val();

        loadExecutionLabel(projectID);
        loadExecutions(productID, projectID);
        loadAssignedTo(productID, projectID);
        if(methodName == 'edit' && edition == 'max') loadIdentify();
    }
}

function changeExecution(event)
{
    const productID   = $('[name="product"]').val();
    const projectID   = $('[name="project"]').val() == 'undefined' ? 0 : $('[name="project"]').val();
    const executionID = $(event.target).val();

    if(executionID != 0)
    {
        loadProjectByExecutionID(executionID);
        loadExecutionStories(executionID);
        loadExecutionBuilds(executionID);
        loadAssignedToByExecution(executionID);
        loadTestTasks(productID, executionID);
    }
    else
    {
        loadProductStories(productID, bug.storyID);
        loadTestTasks(productID);
        if(projectID == 0)
        {
            loadAssignedToByProduct(productID);
            loadProductBuilds(productID);
        }
        else
        {
            loadAssignedToByProject(projectID);
            loadProjectBuilds(projectID);
        }
    }

    loadExecutionTasks(executionID);
}

function changeModule(event)
{
    const moduleID  = $(event.target).val();
    const productID = $('[name="product"]').val();
    const storyID   = $('[name="story"]').val();
    let executionID = $('[name="execution"]').val();
    if(typeof(executionID) == 'undefined') executionID = 0;
    loadAssignedToByModule(moduleID, productID);
    loadProductStories(productID, storyID, moduleID, executionID);
}

function changeRegion(event)
{
    const regionID = $(event.target).val();
    const url = $.createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=bug&field=lane');
    $.get(url, function(lane)
    {
        if(!lane) lane = "<select id='lane' name='lane' class='form-control'></select>";
        $('#lane').replaceWith(lane);
    });
}

function refreshModule(event)
{
    const productID = $('[name="product"]').val();
    loadProductModules(productID);
}

window.refreshProductBuild = function(event)
{
    const productID = $('[name="product"]').val();
    loadProductBuilds(productID);
}

window.refreshExecutionBuild = function(event)
{
    const executionID = $('[name="execution"]').val();
    loadExecutionBuilds(executionID);
}

function loadProductBranches(productID)
{
    const branchStatus = methodName == 'create' ? 'active' : 'all';
    const oldBranch    = methodName == 'edit' ? bug.branch : 0;
    let   param        = "productID=" + productID + "&oldBranch=" + oldBranch + "&param=" + branchStatus;
    if(typeof(tab) != 'undefined' && (tab == 'execution' || tab == 'project')) param += "&projectID=" + bug[tab];
    $.getJSON($.createLink('branch', 'ajaxGetBranches', param), function(data)
    {
        if(data.length > 0)
        {
            if($('#branchPicker').length > 0)
            {
                const $branchPicker = $('#branchPicker').zui('picker');
                $branchPicker.render({items: data, defaultValue: data[0].value});
                $branchPicker.$.setValue('0');
            }
            else
            {
                $('[name="product"]').closest('.input-group').append($('<div id="branchPicker" class="form-group-wrapper picker-box"></div>').picker({name: 'branch', items: data, defaultValue: data[0].value, required: true}));
            }
            $('#branchPicker').css('width', methodName == 'create' ? '120px' : '70px');
        }
        else
        {
            $('#branchPicker').zui('picker').destroy();
            $('#branchPicker').remove();
        }
    });
}

function loadProductModules(productID)
{
    if(methodName == 'edit')
    {
        const moduleID = $('[name="module"]').val();
    }

    let branch = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined')   branch   = 0;
    if(typeof(moduleID) == 'undefined') moduleID = 0;

    const link = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug&branch=' + branch + '&rootModuleID=0&returnType=items&fieldID=&extra=nodeleted&currentModuleID=' + moduleID);
    $.getJSON(link, function(data)
    {
        let moduleID      = $('[name="module"]').val();
        let $modulePicker = $('[name="module"]').zui('picker');
        $modulePicker.render({items: data});
        if(moduleID != 0) $modulePicker.$.setValue('0');

        $('#manageModule').toggleClass('hidden', data.length > 1);
        if(data.length <= 1) $('#manageModule').attr('href', $.createLink('tree', 'browse', 'rootID=' + productID + '&currentModuleID=' + moduleID + '&branch=' + branch));
    });
}

function loadProductProjects(productID)
{
    let branch = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('bug', 'ajaxGetProjects', 'productID=' + productID + '&branch=' + branch + '&projectID=' + $('[name="project"]').val());
    $.getJSON(link, function(data)
    {
        let project        = $('[name="project"]').val();
        let $projectPicker = $('[name="project"]').zui('picker');
        $projectPicker.render({items: data});
        $projectPicker.$.setValue(project != '0' ? project : '');
    });
}

function loadExecutions(productID, projectID = 0)
{
    let branch = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const isMultipleProject = projectID != 0 && projectExecutionPairs[projectID] !== undefined;

    $('#executionBox').toggle(!isMultipleProject);

    if(projectID == 0) projectID = $('[name="project"]').val();
    const link = $.createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=' + projectID + '&branch=' + branch + '&pageType=&executionID=&from=&mode=stagefilter');
    $.getJSON(link, function(data)
    {
        let executionID      = isMultipleProject ? projectExecutionPairs[projectID] : $('[name=execution]').val();
        let $executionPicker = $('[name="execution"]').zui('picker');
        $executionPicker.render({items: data});
        $executionPicker.$.setValue(executionID != '0' ? executionID : '');
    });

    if($('[name="execution"]').val() == 0) projectID != 0 ? loadProjectBuilds(projectID) : loadProductBuilds(productID);
}

function loadExecutionLabel(projectID)
{
    if(methodName == 'create' && projectID > 0)
    {
        const link = $.createLink('bug', 'ajaxGetExecutionLang', 'projectID=' + projectID);
        $.post(link, function(executionLang)
        {
            $('#executionBox .form-label').html(executionLang);
        })
    }
}

function loadAssignedTo(productID, projectID = 0, executionID = 0)
{
    if(projectID != 0)
    {
        loadAssignedToByProject(projectID);
    }
    else if(executionID != 0)
    {
        loadAssignedToByExecution(executionID);
    }
    else
    {
        loadAssignedToByProduct(productID);
    }
}

function loadAssignedToByProduct(productID)
{
    let branch = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('bug', 'ajaxGetProductMembers', 'productID=' + productID + '&selectedUser=' + $('[name="assignedTo"]').val() + '&branchID=' + branch);
    $.getJSON(link, function(data)
    {
        let assignedTo        = $('[name="assignedTo"]').val();
        let $assignedToPicker = $('[name="assignedTo"]').zui('picker');
        $assignedToPicker.render({items: data});
        $assignedToPicker.$.setValue(assignedTo);
    });
}

function loadAssignedToByProject(projectID)
{
    const link = $.createLink('bug', 'ajaxGetProjectTeamMembers', 'projectID=' + projectID);
    $.getJSON(link, function(data)
    {
        let assignedTo        = $('[name="assignedTo"]').val();
        let $assignedToPicker = $('[name="assignedTo"]').zui('picker');
        $assignedToPicker.render({items: data});
        $assignedToPicker.$.setValue(assignedTo);
    });
}

function loadAssignedToByExecution(executionID)
{
    const link = $.createLink('bug', 'ajaxLoadAssignedTo', 'executionID=' + executionID);
    $.getJSON(link, function(data)
    {
        let assignedTo        = $('[name="assignedTo"]').val();
        let $assignedToPicker = $('[name="assignedTo"]').zui('picker');
        $assignedToPicker.render({items: data});
        $assignedToPicker.$.setValue(assignedTo);
    });
}

function loadAssignedToByModule(moduleID, productID)
{
    if(typeof(productID) == 'undefined') productID = $('[name="product"]').val();
    if(typeof(moduleID) == 'undefined')  moduleID  = $('[name="module"]').val();
    const link = $.createLink('bug', 'ajaxGetModuleOwner', 'moduleID=' + moduleID + '&productID=' + productID);
    $.getJSON(link, function(owner)
    {
        let account           = owner.account;
        let realName          = owner.realname;
        let isExist           = false;
        let $assignedToPicker = $('[name="assignedTo"]').zui('picker');
        let assignedToItems   = $assignedToPicker.options.items;
        let count             = assignedToItems.length;
        for(var i = 0; i < count; i++)
        {
            if(assignedToItems[i].value == account)
            {
                isExist = true;
                break;
            }
        }
        if(!isExist && account)
        {
            assignedToItems = {text: realName, value: account, keys: realName};
            $assignedToPicker.render({items: data, value: account});
        }
        $assignedToPicker.$.setValue(account);
    });
}

function loadProjectBuilds(projectID)
{
    let branch = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const productID      = $('[name="product"]').val();
    const oldOpenedBuild = $('[name^="openedBuild"]').val() ? $('[name^="openedBuild"]').val().toString() : 0;

    if(methodName == 'create')
    {
        const link = $.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=&branch=' + branch);
        $.getJSON(link, function(data)
        {
            let buildID      = $('[name^="openedBuild"]').val();
            let $buildPicker = $('[name^="openedBuild"]').zui('picker');
            $buildPicker.render({items: data});
            $buildPicker.$.setValue(buildID);
            loadBuildActions();
        })
    }
    else
    {
        const openedLink = $.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch);
        $.getJSON(openedLink, function(data)
        {
            let $buildPicker = $('[name^="openedBuild"]').zui('picker');
            $buildPicker.render({items: data});
            $buildPicker.$.setValue(oldOpenedBuild);
            loadBuildActions();
        })

        const oldResolvedBuild = $('[name="resolvedBuild"]').val() ? $('[name="resolvedBuild"]').val().toString() : 0;
        const resolvedLink     = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch);
        $.getJSON(resolvedLink, function(data)
        {
            let $buildPicker = $('[name="resolvedBuild"]').zui('picker');
            $buildPicker.render({items: data});
            $buildPicker.$.setValue(oldResolvedBuild);
        });
    }
}

function loadProductBuilds(productID, type = 'normal', buildBox = 'all')
{
    let branch = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    if(methodName == 'create')
    {
        if(buildBox == 'all' || buildBox == 'openedBuild')
        {
            const link = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=&branch=' + (branch == 0 ? 'all' : branch) + '&type=' + type);
            $.getJSON(link, function(data)
            {
                let buildID      = $('[name^="openedBuild"]').val();
                let $buildPicker = $('[name^="openedBuild"]').zui('picker');
                $buildPicker.render({items: data});
                $buildPicker.$.setValue(buildID);
                loadBuildActions();
            });
        }
    }
    else
    {
        if(buildBox == 'all' || buildBox == 'openedBuild')
        {
            const openedLink = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + bug.openedBuild + '&branch=' + (branch == 0 ? 'all' : branch) + '&type=' + type);
            $.getJSON(openedLink, function(data)
            {
                let buildID      = $('[name^="openedBuild"]').val().toString();
                let $buildPicker = $('[name^="openedBuild"]').zui('picker');
                $buildPicker.render({items: data});
                $buildPicker.$.setValue(buildID);
            });
        }

        if(buildBox == 'all' || buildBox == 'resolvedBuild')
        {
            const oldResolvedBuild = $('[name="resolvedBuild"]').val() ? $('[name="resolvedBuild"]').val().toString() : 0;
            const resolvedLink = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch + '&type=' + type);
            $.getJSON(resolvedLink, function(data)
            {
                let $buildPicker = $('[name="resolvedBuild"]').zui('picker');
                $buildPicker.render({items: data});
                $buildPicker.$.setValue(oldResolvedBuild);
            });
        }
    }
}

function loadExecutionBuilds(executionID, num)
{
    if(typeof(num) == 'undefined') num = '';

    let branch           = num != '' ? $('#branch' + num).val() : $('[name="branch"]').val();
    let productID        = num != '' ? $('#product' + num).val() : $('[name="product"]').val();
    const oldOpenedBuild = $('[name^="openedBuild"]' + num).val() ? $('[name^="openedBuild"]' + num).val().toString() : 0;

    if(typeof(branch) == 'undefined')    branch    = 'all';
    if(typeof(productID) == 'undefined') productID = 0;

    if(methodName == 'create')
    {
        const link = $.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + "&branch=" + branch + "&needCreate=true");
        $.getJSON(link, function(data)
        {
            let $buildPicker = $('[name^="openedBuild"]').zui('picker');
            $buildPicker.render({items: data});
            $buildPicker.$.setValue(oldOpenedBuild);
            loadBuildActions();
        });
    }
    else
    {
        const openedLink = $.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&needCreate=false&type=normal&number=' + num);
        $.getJSON(openedLink, function(data)
        {
            let $buildPicker = $('[name^="openedBuild"]').zui('picker');
            $buildPicker.render({items: data});
            $buildPicker.$.setValue(oldOpenedBuild);
        });

        const oldResolvedBuild = $('[name="resolvedBuild"]').val() ? $('[name="resolvedBuild"]').val().toString() : 0;
        const resolvedLink     = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch);
        $.getJSON(resolvedLink, function(data)
        {
            let $buildPicker = $('[name^="resolvedBuild"]').zui('picker');
            $buildPicker.render({items: data});
            $buildPicker.$.setValue(oldResolvedBuild);
        });
    }
}

function loadProductPlans(productID)
{
    if($('[name="plan"]').length == 0) return;
    let branch = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('productplan', 'ajaxGetProductplans', 'productID=' + productID + '&branch=' + branch);
    $.getJSON(link, function(data)
    {
        if(data)
        {
            let planID      = $('[name="plan"]').val();
            let $planPicker = $('[name="plan"]').zui('picker');
            $planPicker.render({items: data});
            $planPicker.$.setValue(planID);
        }
    });
}

function loadProductStories(productID, storyID, moduleID = 0, executionID = 0)
{
    let branch = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&storyID=' + storyID + '&onlyOption=false&status=active&limit=0&type=full&hasParent=0&executionID=' + executionID);
    $.getJSON(link, function(data)
    {
        let $storyPicker = $('[name="story"]').zui('picker');
        $storyPicker.render({items: data});
        $storyPicker.$.setValue(storyID);
    });
}

function loadExecutionStories(executionID)
{
    const productID = $('[name="product"]').val();
    let   branch    = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=' + productID + '&branch=' + branch + '&moduleID=0&storyID=' + bug.storyID + '&number=&type=full&status=all&from=bug');
    $.getJSON(link, function(data)
    {
        let story        = $('[name="story"]').val();
        let $storyPicker = $('[name="story"]').zui('picker');
        $storyPicker.render({items: data});
        $storyPicker.$.setValue(story);
    });
}

function loadExecutionTasks(executionID)
{
    const link = $.createLink('task', 'ajaxGetExecutionTasks', 'executionID=' + executionID);
    $.post(link, function(data)
    {
        let $taskPicker = $('[name="task"]').zui('picker');
        if(data)
        {
            data = JSON.parse(data);
            $taskPicker.render({items: data});
        }
    })
}

function loadProjectByExecutionID(executionID)
{
    const link = $.createLink('project', 'ajaxGetPairsByExecution', 'executionID=' + executionID, 'json');
    $.post(link, function(data)
    {
        if($('[name="project"]').val() == data.id.toString()) return;
        $projectPicker = $('[name="project"]').zui('picker');
        $projectPicker.$.setValue(data.id.toString());
    }, 'json')
}

function loadTestTasks(productID, executionID)
{
    if(!$('#testtaskBox').length) return;
    if(typeof(executionID) == 'undefined') executionID = 0;

    const link = $.createLink('testtask', 'ajaxGetTestTasks', 'productID=' + productID + '&executionID=' + executionID + '&testtaskID=' + bug.testtask);
    $.getJSON(link, function(result)
    {
        let testtask        = $('[name="testtask"]').val();
        let $testtaskPicker = $('[name="testtask"]').zui('picker');
        if(result.tasks)
        {
            $testtaskPicker.render({items: result.tasks});
            $testtaskPicker.$.setValue(testtask);
        }
    });
}

function loadAllBuilds(event)
{
    const productID     = $('[name="product"]').val();
    const $buildElement = $(event.target).closest('.input-group').find('select').length > 0 ? $(event.target).closest('.input-group').find('select') : $(event.target).closest('.input-group').find('input');
    const buildBox      = $buildElement.attr('name').replace('[]', '');
    loadProductBuilds(productID, 'all', buildBox);
}

function loadAllUsers(event)
{
    const isClosedBug = bug.status == 'closed';
    const params      = isClosedBug ? 'params=devfirst' : 'params=devfirst,noclosed';
    const link        = $.createLink('bug', 'ajaxLoadAllUsers', params);
    $.getJSON(link, function(data)
    {
        $('[name="assignedTo"]').zui('picker').render({items: data});
        if(!isClosedBug)
        {
            const moduleID  = $('[name="module"]').val();
            const productID = $('[name="product"]').val();
            loadAssignedToByModule(moduleID, productID);
        }
    });
}

function setBranchRelated(event)
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const branchID    = $target.val();
    const productID   = $('[name= "product"]').val();
    const moduleID    = $currentRow.find('.form-batch-input[data-name="module"]').val() || '0';
    const moduleLink  = $.createLink('tree', 'ajaxGetModules', 'productID=' + productID + '&viewType=bug&branch=' + branchID + '&num=0&currentModuleID=' + moduleID);
    $.getJSON(moduleLink, function(data)
    {
        if(!data || !data.modules) return;

        let $row = $currentRow;
        while($row.length)
        {
            const currentModuleID = $row.find('.form-batch-control[data-name="module"] input').val();
            const $modulePicker   = $row.find('.form-batch-control[data-name="module"] input').zui('picker');
            $modulePicker.render({items: data.modules});
            $modulePicker.$.setValue(currentModuleID);

            $row = $row.next('tr');
            if(!$row.find('td[data-name="module"][data-ditto="on"]').length) break;
        }
    });

    var projectLink = $.createLink('product', 'ajaxGetProjectsByBranch', 'productID=' + productID + '&branch=' + branchID);
    $.getJSON(projectLink, function(projects)
    {
        if(!projects) return;

        let $row = $currentRow;
        while($row.length)
        {
            const currentProjectID = $row.find('.form-batch-control[data-name="project"] input').val();
            const $projectPikcer   = $row.find('.form-batch-control[data-name="project"] input').zui('picker');
            $projectPikcer.render({items: projects});
            $projectPikcer.$.setValue(currentProjectID);

            $row = $row.next('tr');
            if(!$row.find('td[data-name="project"][data-ditto="on"]').length) break;
        }
    });

    const currentProjectID = $currentRow.find('.form-batch-input[data-name="project"]').val() || '0';
    var   executionLink    = $.createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=' + currentProjectID + '&branch=' + branchID + '&pageType=batch');
    $.getJSON(executionLink, function(executions)
    {
        if(!executions) return;

        let $row = $currentRow;
        while($row.length)
        {
            const currentExecutionID = $row.find('.form-batch-control[data-name="execution"] input').val();
            const $executionPicker   = $row.find('.form-batch-control[data-name="execution"] input').zui('picker');
            $executionPicker.render({items: executions});
            $executionPicker.$.setValue(currentExecutionID);

            $row = $row.next('tr');
            if(!$row.find('td[data-name="execution"][data-ditto="on"]').length) break;
        }
    });

    /* If the branch of the current row is inconsistent with the one below, clear the module and execution of the nex row. */
    if(config.currentMethod == 'batchcreate')
    {
        let $nextRow     = $currentRow.next('tr');
        let nextBranchID = $nextRow.find('td[data-name="branch"]').val();
        if(nextBranchID != branchID)
        {
            $nextRow.find('.form-batch-input[data-name="branch"]').attr('data-ditto', 'off');
            $nextRow.find('.form-batch-input[data-name="execution"]').attr('data-ditto', 'off');
        }

      var buildLink = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + "&varName=openedBuilds&build=&branch=" + branchID);
      setOpenedBuilds(buildLink, $currentRow);
    }

    if(config.currentMethod == 'batchedit')
    {
        var planID   = $('#plans_' + num).val();
        var planLink = $.createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&branch=' + branchID + '&planID=' + planID + '&fieldID=' + num + '&needCreate=false&expired=&param=skipParent');
        $('#plans_' + num).parent('td').load(planLink, function()
        {
            var firstBugID = $('.table-form tbody').first('tr').find('input[id^=bugIDList]').val();
            if(num == firstBugID)
            {
                $('#plans_' + firstBugID).find('option').each(function()
                {
                    if($(this).val() == 'ditto') $(this).remove();
                    $('#plans_' + firstBugID).trigger('chosen:updated');
                });
            }
        });
    }
}

function loadBuildActions()
{
    if(methodName == 'edit') return;
    $('#createRelease, #createBuild').hide();
    let itemCount = $('[name^=openedBuild]').zui('picker').options.items.length;
    if(itemCount <= 1)
    {
        let html = '';
        if($('#execution').length == 0 || $('[name="execution"]').val() == 0)
        {
            $('#createRelease').show();
        }
        else
        {
            $('#createBuild').show();
        }
    }
}

let checkHasCheckedData = function(item, checkedValue)
{
    return item.value == checkedValue;
};

function loadBuilds()
{
    const productID   = $('[name="product"]').val();
    const projectID   = $('[name="project"]').val() == 'undefined' ? 0 : $('[name="project"]').val();
    const executionID = $('[name="execution"]').val() == 'undefined' ? 0 : $('[name="execution"]').val();
    if(executionID)
    {
        loadExecutionBuilds(executionID);
    }
    else if(projectID)
    {
        loadProjectBuilds(projectID);
    }
    else
    {
        loadProductBuilds(productID);
    }
}

function loadProductCases(productID)
{
    const branchID  = $('[name=branch]').length > 0 ? $('[name=branch]').val() : 0;
    const caseID    = $('[name=case]').val();
    $.getJSON($.createLink('bug', 'ajaxGetProductCases', `productID=${productID}&branch=${branchID}`),function(cases)
    {
        const $casePicker = $('[name="case"]').zui('picker');
        $casePicker.render({items: cases});
        $casePicker.$.setValue(caseID);
    });
}

function loadProductBugs(productID, bugID)
{
    if($('[name="duplicateBug"]').length == 0) return;

    const link = $.createLink('bug', 'ajaxGetProductBugs', 'productID=' + productID + '&bugID=' + bugID);
    $.getJSON(link, function(data)
    {
        const duplicateBugID      = $('[name="duplicateBug"]').val();
        const $duplicateBugPicker = $('[name="duplicateBug"]').zui('picker');
        $duplicateBugPicker.render({items: data});
        $duplicateBugPicker.$.setValue(duplicateBugID);
    });
}
