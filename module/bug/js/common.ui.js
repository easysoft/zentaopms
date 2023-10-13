$(function()
{
    $('#subNavbar a[data-toggle=dropdown]').parent().addClass('dropdown dropdown-hover');
});

function changeProduct(event)
{
    const productID = $(event.target).val();
    if(!productID) return false;

    if(typeof(changeProductConfirmed) != 'undefined' && !changeProductConfirmed)
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
                $('#product').val(bug.product); // Revert old product id if confirm is no.
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
        loadProductStories(productID, bug.story);
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
    loadProductStories(productID, bug.story);
}

function changeProject(event)
{
    const productID = $('[name="product"]').val();
    const projectID = $(event.target).val();

    loadExecutionLabel(projectID);
    loadExecutions(productID, projectID);
    loadAssignedTo(productID, projectID);
}

function changeExecution(event)
{
    const productID   = $('[name="product"]').val();
    const projectID   = $('[name="project"]').val() == 'undefined' ? 0 : $('[name="project"]').val();
    const executionID = $(event.target).val();

    if(executionID)
    {
        loadProjectByExecutionID(executionID);
        loadExecutionTasks(executionID);
        loadExecutionStories(executionID);
        loadExecutionBuilds(executionID);
        loadAssignedToByExecution(executionID);
        loadTestTasks(productID, executionID);
    }
    else
    {
        loadProductStories(productID, bug.story);
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
}

function changeModule(event)
{
    const moduleID  = $(event.target).val();
    const productID = $('[name="product"]').val();
    const storyID   = $('#story').val();
    let executionID = $('[name="execution"]').val();
    if(typeof(executionID) == 'undefined') executionID = 0;
    loadAssignedToByModule(moduleID, productID);
    loadProductStories(productID, storyID, executionID, moduleID);
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

function changeContact(event)
{
    const contactID = $(event.target).val();
    setMailto(contactID);
}

function refreshModule(event)
{
    const productID = $('[name="product"]').val();
    loadProductModules(productID);
}

function refreshContact(event)
{
    loadContacts();
}

function refreshProductBuild(event)
{
    const productID = $('[name="product"]').val();
    loadProductBuilds(productID);
}

function refreshExecutionBuild(event)
{
    const executionID = $('[name="execution"]').val();
    loadExecutionBuilds(executionID);
}

function loadProductBranches(productID)
{
    const branchStatus = config.currentMethod == 'create' ? 'active' : 'all';
    const oldBranch    = config.currentMethod == 'edit' ? bug.branch : 0;
    let   param        = "productID=" + productID + "&oldBranch=" + oldBranch + "&param=" + branchStatus;
    if(typeof(tab) != 'undefined' && (tab == 'execution' || tab == 'project')) param += "&projectID=" + bug[tab];
    $.get($.createLink('branch', 'ajaxGetBranches', param), function(data)
    {
        let branch = $('[name="branch"]').val();
        if($('[name="branch"]').length > 0)
        {
            $('[name="branch"]').zui('picker').destroy();
            $('[name="product"]').closest('.picker-box').next('.picker-box').remove();
        }
        $('#branchPicker').remove();
        if(data)
        {
            data = JSON.parse(data);

            $('[name="product"]').closest('.input-group').append($('<div id="branchPicker" class="form-group-wrapper picker-box"></div>').picker({name: 'branch', items: data}));
            $('#branchPicker').css('width', config.currentMethod == 'create' ? '120px' : '65px');
        }
    });
}

function loadProductModules(productID)
{
    if(config.currentMethod == 'edit')
    {
        const moduleID = $('[name="module"]').val();
    }

    let branch = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined')   branch   = 0;
    if(typeof(moduleID) == 'undefined') moduleID = 0;

    const link = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug&branch=' + branch + '&rootModuleID=0&returnType=items&fieldID=&needManage=true&extra=nodeleted&currentModuleID=' + moduleID);
    $.get(link, function(data)
    {
        let moduleID      = $('[name="module"]').val();
        let $modulePicker = $('[name="module"]').zui('picker');
        if(data)
        {
            data = JSON.parse(data);
            $modulePicker.render({items: data});
            if(moduleID != 0) $modulePicker.$.setValue('0');
        }
    });
}

function loadProductProjects(productID)
{
    let branch = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('product', 'ajaxGetProjects', 'productID=' + productID + '&branch=' + branch + '&projectID=' + $('[name="project"]').val());
    $.get(link, function(data)
    {
        let project        = $('[name="project"]').val();
        let $projectPicker = $('[name="project"]').zui('picker');
        if(data)
        {
            data = JSON.parse(data);
            $projectPicker.render({items: data});
            $projectPicker.$.setValue(project != '0' ? project : '');
        }
    });
}

function loadExecutions(productID, projectID = 0)
{
    let branch = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=' + projectID + '&branch=' + branch + '&pageType=&executionID=from=&mode=stagefilter');
    $.get(link, function(data)
    {
        let executionID      = $('[name="execution"]').val();
        let $executionPicker = $('[name="execution"]').zui('picker');
        if(data)
        {
            data = JSON.parse(data);
            $executionPicker.render({items: data});
            $executionPicker.$.setValue(executionID != '0' ? executionID : '');
        }
    });

    projectID != 0 ? loadProjectBuilds(projectID) : loadProductBuilds(productID);
}

function loadExecutionLabel(projectID)
{
    if(config.currentMethod == 'create' && projectID > 0)
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
    $.get(link, function(data)
    {
        let assignedTo        = $('[name="assignedTo"]').val();
        let $assignedToPicker = $('[name="assignedTo"]').zui('picker');
        if(data)
        {
            data = JSON.parse(data);
            $assignedToPicker.render({items: data});
            $assignedToPicker.$.setValue(assignedTo);
        }
    });
}

function loadAssignedToByProject(projectID)
{
    const link = $.createLink('bug', 'ajaxGetProjectTeamMembers', 'projectID=' + projectID);
    $.get(link, function(data)
    {
        let assignedTo        = $('[name="assignedTo"]').val();
        let $assignedToPicker = $('[name="assignedTo"]').zui('picker');
        if(data)
        {
            data = JSON.parse(data);
            $assignedToPicker.render({items: data});
            $assignedToPicker.$.setValue(assignedTo);
        }
    });
}

function loadAssignedToByExecution(executionID)
{
    const link = $.createLink('bug', 'ajaxLoadAssignedTo', 'executionID=' + executionID);
    $.get(link, function(data)
    {
        let assignedTo        = $('[name="assignedTo"]').val();
        let $assignedToPicker = $('[name="assignedTo"]').zui('picker');
        if(data)
        {
            data = JSON.parse(data);
            $assignedToPicker.render({items: data});
            $assignedToPicker.$.setValue(assignedTo);
        }
    });
}

function loadAssignedToByModule(moduleID, productID)
{
    if(typeof(productID) == 'undefined') productID = $('[name="product"]').val();
    if(typeof(moduleID) == 'undefined')  moduleID  = $('[name="module"]').val();
    const link = $.createLink('bug', 'ajaxGetModuleOwner', 'moduleID=' + moduleID + '&productID=' + productID);
    $.get(link, function(owner)
    {
        owner                 = JSON.parse(owner);
        var account           = owner[0];
        var realName          = owner[1];
        var isExist           = false;
        var $assignedToPicker = $('[name="assignedTo"]').zui('picker');
        var assignedToItems   = $assignedToPicker.ref.current.state.items;
        var count             = assignedToItems.length;
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
    const oldOpenedBuild = $('[name^="openedBuild"]').val() ? $('[name^="openedBuild"]').val() : 0;

    if(config.currentMethod == 'create')
    {
        const link = $.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=&branch=' + branch);
        $.get(link, function(data)
        {
            let buildID      = $('[name^="openedBuild"]').val();
            let $buildPicker = $('[name^="openedBuild"]').zui('picker');
            if(data)
            {
                data = JSON.parse(data);
                $buildPicker.render({items: data});
                $buildPicker.$.setValue(buildID);
            }
            loadBuildActions();
        })
    }
    else
    {
        const openedLink = $.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch);
        $.get(openedLink, function(data)
        {
            let buildID      = $('[name^="openedBuild"]').val();
            let $buildPicker = $('[name^="openedBuild"]').zui('picker');
            if(data)
            {
                data = JSON.parse(data);
                $buildPicker.render({items: data});
                $buildPicker.$.setValue(buildID);
            }
            loadBuildActions();
        })

        const oldResolvedBuild = $('[name="resolvedBuild"]').val() ? $('[name="resolvedBuild"]').val() : 0;
        const resolvedLink     = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch);
        $.get(resolvedLink, function(data)
        {
            let buildID      = $('[name="resolvedBuild"]').val();
            let $buildPicker = $('[name="resolvedBuild"]').zui('picker');
            if(data)
            {
                data = JSON.parse(data);
                $buildPicker.render({items: data});
                $buildPicker.$.setValue(buildID);
            }
            $buildPicker.$.setValue(oldResolvedBuild);
        });
    }
}

function loadProductBuilds(productID, type = 'normal', buildBox = 'all')
{
    let branch = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    if(config.currentMethod == 'create')
    {
        if(buildBox == 'all' || buildBox == 'openedBuild')
        {
            const link = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=&branch=' + branch + '&type=' + type);
            $.get(link, function(data)
            {
                let buildID      = $('[name^="openedBuild"]').val();
                let $buildPicker = $('[name^="openedBuild"]').zui('picker');
                if(data)
                {
                    data = JSON.parse(data);
                    $buildPicker.render({items: data});
                    $buildPicker.$.setValue(buildID);
                }
                loadBuildActions();
            });
        }
    }
    else
    {
        if(buildBox == 'all' || buildBox == 'openedBuild')
        {
            const openedLink = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + bug.openedBuild + '&branch=' + branch + '&type=' + type);
            $.get(openedLink, function(data)
            {
                let buildID      = $('[name^="openedBuild"]').val();
                let $buildPicker = $('[name^="openedBuild"]').zui('picker');
                if(data)
                {
                    data = JSON.parse(data);
                    $buildPicker.render({items: data});
                    $buildPicker.$.setValue(buildID);
                }
            });
        }

        if(buildBox == 'all' || buildBox == 'resolvedBuild')
        {
            const oldResolvedBuild = $('[name="resolvedBuild"]').val() ? $('[name="resolvedBuild"]').val() : 0;
            const resolvedLink = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch + '&type=' + type);
            $.get(resolvedLink, function(data)
            {
                let buildID      = $('[name="resolvedBuild"]').val();
                let $buildPicker = $('[name="resolvedBuild"]').zui('picker');
                if(data)
                {
                    data = JSON.parse(data);
                    $buildPicker.render({items: data});
                    $buildPicker.$.setValue(buildID);
                }
                $buildPicker.$.setValue(oldResolvedBuild);
            });
        }
    }
}

function loadExecutionBuilds(executionID, num)
{
    if(typeof(num) == 'undefined') num = '';

    let branch           = $('#branch' + num).val();
    let productID        = $('#product' + num).val();
    const oldOpenedBuild = $('[name^="openedBuild"]' + num).val() ? $('[name^="openedBuild"]' + num).val() : 0;

    if(typeof(branch) == 'undefined')    branch    = 0;
    if(typeof(productID) == 'undefined') productID = 0;

    if(config.currentMethod == 'create')
    {
        const link = $.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + "&branch=" + branch + "&needCreate=true");
        $.get(link, function(data)
        {
            let $buildPicker = $('[name^="openedBuild"]').zui('picker');
            if(data)
            {
                data = JSON.parse(data);
                $buildPicker.render({items: data});
                $buildPicker.$.setValue(oldOpenedBuild);
            }
            loadBuildActions();
        });
    }
    else
    {
        const openedLink = $.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&needCreate=false&type=normal&number=' + num);
        $.get(openedLink, function(data)
        {
            let $buildPicker = $('[name^="openedBuild"]').zui('picker');
            if(data)
            {
                data = JSON.parse(data);
                $buildPicker.render({items: data});
                $buildPicker.$.setValue(oldOpenedBuild);
            }
        });

        const oldResolvedBuild = $('[name="resolvedBuild"]').val() ? $('[name="resolvedBuild"]').val() : 0;
        const resolvedLink     = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch);
        $.get(resolvedLink, function(data)
        {
            let $buildPicker = $('[name^="openedBuild"]').zui('picker');
            if(data)
            {
                data = JSON.parse(data);
                $buildPicker.render({items: data});
                $buildPicker.$.setValue(oldResolvedBuild);
            }
        });
    }
}

function loadProductPlans(productID)
{
    if($('[name="plan"]').length == 0) return;
    let branch = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('productplan', 'ajaxGetProductplans', 'productID=' + productID + '&branch=' + branch);
    $.get(link, function(data)
    {
        let planID      = $('[name="plan"]').val();
        let $planPicker = $('[name="plan"]').zui('picker');
        if(data)
        {
            data = JSON.parse(data);
            $planPicker.render({items: data});
            $planPicker.$.setValue(planID);
        }
    });
}

function loadProductStories(productID, storyID, moduleID = 0, executionID = 0)
{
    let branch = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&storyID=' + storyID + '&onlyOption=false&status=&limit=0&type=full&hasParent=0&executionID=' + executionID);
    $.get(link, function(data)
    {
        let $storyPicker = $('[name="story"]').zui('picker');
        if(data)
        {
            data = JSON.parse(data);
            $storyPicker.render({items: data});
            $storyPicker.$.setValue('');
        }
    });
}

function loadExecutionStories(executionID)
{
    const productID = $('[name="product"]').val();
    let   branch    = $('[name="branch"]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=' + productID + '&branch=' + branch + '&moduleID=0&storyID=' + bug.story + '&number=&type=full&status=all&from=bug');
    $.get(link, function(data)
    {
        let story        = $('[name="story"]').val();
        let $storyPicker = $('[name="story"]').zui('picker');
        if(data)
        {
            data = JSON.parse(data);
            $storyPicker.render({items: data});
            $storyPicker.$.setValue(story);
        }
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
    const productID = $('[name="product"]').val();
    const buildBox  = $(event.target).closest('.input-group').find('select').attr('name').replace('[]', '');
    loadProductBuilds(productID, 'all', buildBox);
}

function loadAllUsers(event)
{
    const isClosedBug = bug.status = 'closed';
    const params      = isClosedBug ? '&params=devfirst' : '';
    const link        = $.createLink('bug', 'ajaxLoadAllUsers', params);
    $.get(link, function(data)
    {
        if(data)
        {
            data = JSON.parse(data);
            $('[name="assignedTo"]').zui('picker').render({items: data});
            if(!isClosedBug)
            {
                const moduleID  = $('[name="module"]').val();
                const productID = $('[name="product"]').val();
                loadAssignedToByModule(moduleID, productID);
            }
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
            const $module = $row.find('.form-batch-input[data-name="module"]').empty();
            $.each(data.modules, function(index, module)
            {
                $module.append('<option value="' + module.value + '"' + (module.value == data.currentModuleID ? 'selected' : '')  + '>' + module.text + '</option>');
            });

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
            const currentProjectID = $row.find('.form-batch-input[data-name="project"]').val();
            const $project         = $row.find('.form-batch-input[data-name="project"]').empty();
            $.each(projects, function(index, project)
            {
                $project.append('<option value="' + project.value + '"' + (project.value == currentProjectID ? 'selected' : '')  + '>' + project.text + '</option>');
            });

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
            const currentExecutionID = $row.find('.form-batch-input[data-name="execution"]').val();
            const $execution         = $row.find('.form-batch-input[data-name="execution"]').empty();
            $.each(executions, function(index, execution)
            {
                $execution.append('<option value="' + execution.value + '"' + (execution.value == currentExecutionID ? 'selected' : '')  + '>' + execution.text + '</option>');
            });

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
    if(config.currentMethod == 'edit') return;
    $('#buildBoxActions').empty().hide();
    let itemCount = $('#openedBuild').find('option').length;
    if($('#openedBuild').attr('data-items') != undefined) itemCount = $('#openedBuild').attr('data-items');
    if(itemCount <= 1)
    {
        let html = '';
        if($('#execution').length == 0 || $('[name="execution"]').val() == 0)
        {
            let branch    = $('[name="branch"]').val();
            let projectID = $('[name="project"]').val();

            if(typeof(branch)    == 'undefined') branch    = 0;
            if(typeof(projectID) == 'undefined') projectID = 0;

            let productID = $('[name="product"]').length > 0 ? $('[name="product"]').val() : bug.productID;
            let link = $.createLink('release', 'create', 'productID=' + productID + '&branch=' + branch);
            if(projectID > 0) link = $.createLink('projectrelease', 'create', 'projectID=' + projectID);

            html += '<a href="' + link + '" data-toggle="modal" style="padding-right:5px">' + createRelease + '</a> ';
            html += '<a href="javascript:;" id="refreshProductBuild">' + refresh + '</a>';
        }
        else
        {
            const executionID = $('[name="execution"]').val();
            const productID   = $('[name="product"]').val();
            const projectID   = $('[name="project"]').val();
            let link = $.createLink('build', 'create','executionID=' + executionID + '&productID=' + productID + '&projectID=' + projectID);
            link += link.indexOf('?') >= 0 ? '&onlybody=yes' : '?onlybody=yes';
            html += '<a href="' + link + '" data-toggle="modal" style="padding-right:5px">' + createBuild + '</a> ';
            html += '<a href="javascript:;" id="refreshExecutionBuild">' + refresh + '</a>';
        }
        $('#buildBoxActions').html(html).show();
    }
}

function loadContacts()
{
    const link = $.createLink('user', 'ajaxGetContactList', 'dropdownName=mailto');
    $.get(link, function(contacts)
    {
        if(!contacts) return false;
        $('#contactBox').html(contacts)
    });
}

function setMailto(contactID)
{
    const oldUsers = $('#mailto').val();
    const link     = $.createLink('user', 'ajaxGetContactUsers', 'listID=' + contactID + '&dropdownName=mailto&oldUsers=' + oldUsers);
    $.get(link, function(users)
    {
        $('#mailto').replaceWith(users);
    });
}

let checkHasCheckedData = function(item, checkedValue)
{
    return item.value == checkedValue;
};
