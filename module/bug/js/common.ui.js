$(function()
{
    $('#subNavbar a[data-toggle=dropdown]').parent().addClass('dropdown dropdown-hover');
});

function changeProduct(event)
{
    const productID = $(event.target).val();
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
                $('#product').val(oldProductID); // Revert old product id if confirm is no.
            }
        }});
    }
    else
    {
        loadProductBranches(productID)
        loadProductModules(productID);
        loadProductProjects(productID);
        loadExecutions(productID);
        loadAssignedTo();
        loadProductPlans(productID);
        loadProductStories(productID);
    }
}

function changeBranch(event)
{
    const productID = $('#product').val();

    loadProductModules(productID);
    loadProductProjects(productID);
    loadExecutions(productID);
    loadAssignedTo();
    loadProductBuilds(productID);
    loadProductPlans(productID);
    loadProductStories(productID);
}

function changeProject(event)
{
    const productID = $('#product').val();
    const projectID = $(event.target).val();

    loadExecutionLabel(projectID);
    loadExecutions(productID, projectID);
    loadAssignedTo();
}

function changeExecution(event)
{
    const productID   = $('#product').val();
    const projectID   = $('#project').val() == 'undefined' ? 0 : $('#project').val();
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
        loadProductStories(productID);
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
    const productID = $('#product').val();
    const storyID   = $('#story').val();
    loadAssignedToByModule(moduleID, productID);
    loadProductStories(moduleID, productID, storyID);
}

function clickRefresh(event)
{
    const productID = $('#product').val();
    loadProductModules(productID);
}

function loadProductBranches(productID)
{
    $('#branch').remove();

    const branchStatus = config.currentMethod == 'create' ? 'active' : 'all';
    const oldBranch    = config.currentMethod == 'edit' ? bugBranch : 0;
    let   param        = "productID=" + productID + "&oldBranch=" + oldBranch + "&param=" + branchStatus;
    if(typeof(tab) != 'undefined' && (tab == 'execution' || tab == 'project')) param += "&projectID=" + objectID;
    $.get($.createLink('branch', 'ajaxGetBranches', param), function(data)
    {
        if(data)
        {
            $('#product').closest('.input-group').append(data);
            $('#branch').css('width', config.currentMethod == 'create' ? '120px' : '65px');
        }
    });
}

function loadProductModules(productID)
{
    if(config.currentMethod == 'edit')
    {
        const moduleID = $('#module').val();
    }

    let branch = $('#branch').val();
    if(typeof(branch) == 'undefined')   branch   = 0;
    if(typeof(moduleID) == 'undefined') moduleID = 0;

    const link = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true&extra=nodeleted&currentModuleID=' + moduleID);
    $('#moduleBox').load(link);
}

function loadProductProjects(productID)
{
    let branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('product', 'ajaxGetProjects', 'productID=' + productID + '&branch=' + branch + '&projectID=' + oldProjectID);
    $('#projectBox').load(link);
}

function loadExecutions(productID, projectID = 0)
{
    let branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=' + projectID + '&branch=' + branch + '&number=&executionID=&from=&mode=stagefilter');
    $('#executionBox').load(link);

    projectID != 0 ? loadProjectBuilds(projectID) : loadProductBuilds(productID);
}

function loadExecutionLabel(projectID)
{
    if(config.currentMethod == 'create' && projectID)
    {
        const link = $.createLink('bug', 'ajaxGetExecutionLang', 'projectID=' + projectID);
        $.post(link, function(executionLang)
        {
            $('#executionBox').prev().html(executionLang);
        })
    }
}

function loadAssignedTo()
{
    const projectID   = $('#project').val();
    const productID   = $('#product').val();
    const executionID = $('#execution').val();

    if(projectID)
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
    let branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('bug', 'ajaxGetProductMembers', 'productID=' + productID + '&selectedUser=' + $('#assignedTo').val() + '&branchID=' + branch);
    $.get(link, function(data)
    {
        $('#assignedTo').replaceWith(data);
    });
}

function loadAssignedToByProject(projectID)
{
    const link = $.createLink('bug', 'ajaxGetProjectTeamMembers', 'projectID=' + projectID + '&selectedUser=' + $('#assignedTo').val());
    $.get(link, function(data)
    {
        $('#assignedTo').replaceWith(data);
    });
}

function loadAssignedToByExecution(executionID)
{
    const link = $.createLink('bug', 'ajaxLoadAssignedTo', 'executionID=' + executionID + '&selectedUser=' + $('#assignedTo').val());
    $.get(link, function(data)
    {
        $('#assignedTo').replaceWith(data);
    });
}

function loadAssignedToByModule(moduleID, productID)
{
    if(typeof(productID) == 'undefined') productID = $('#product').val();
    if(typeof(moduleID) == 'undefined')  moduleID  = $('#module').val();
    const link = $.createLink('bug', 'ajaxGetModuleOwner', 'moduleID=' + moduleID + '&productID=' + productID);
    $.get(link, function(owner)
    {
        owner        = JSON.parse(owner);
        var account  = owner[0];
        var realName = owner[1];
        var isExist  = false;
        var count    = $('#assignedTo').find('option').length;
        for(var i=0; i < count; i++)
        {
            if($('#assignedTo').get(0).options[i].value == account)
            {
                isExist = true;
                break;
            }
        }
        if(!isExist && account)
        {
            option = "<option title='" + realName + "' value='" + account + "'>" + realName + "</option>";
            $("#assignedTo").append(option);
        }
        $('#assignedTo').val(account);
    });
}

function loadProjectBuilds(projectID)
{
    let branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const productID      = $('#product').val();
    const oldOpenedBuild = $('#openedBuild').val() ? $('#openedBuild').val() : 0;

    if(config.currentMethod == 'create')
    {
        const link = $.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=&branch=' + branch);
        $.get(link, function(data)
        {
            $('#openedBuild').replaceWith(data);
        })
    }
    else
    {
        const openedLink = $.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch);
        $('#openedBuildBox').load(openedLink, function()
        {
            $(this).find('select').val(oldOpenedBuild);
        });

        const oldResolvedBuild = $('#resolvedBuild').val() ? $('#resolvedBuild').val() : 0;
        const resolvedLink     = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch);
        $('#resolvedBuildBox').load(resolvedLink, function()
        {
            $(this).find('select').val(oldResolvedBuild);
        });
    }
}

function loadProductBuilds(productID, type = 'normal', buildBox = 'all')
{
    let branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    if(typeof(oldOpenedBuild) == 'undefined') oldOpenedBuild = 0;

    if(config.currentMethod == 'create')
    {
        if(buildBox == 'all' || buildBox == 'openedBuildBox')
        {
            const link = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=&branch=' + branch + '&index=0&type=' + type);
            $.get(link, function(data)
            {
                $('#openedBuild').replaceWith(data);
            })
        }
    }
    else
    {
        if(buildBox == 'all' || buildBox == 'openedBuildBox')
        {
            const openedLink = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&index=0&type=' + type);
            $('#openedBuildBox').load(openedLink, function()
            {
                $(this).find('select').val(oldOpenedBuild);
            });
        }

        if(buildBox == 'all' || buildBox == 'resolvedBuildBox')
        {
            const oldResolvedBuild = $('#resolvedBuild').val() ? $('#resolvedBuild').val() : 0;
            const resolvedLink = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch + '&index=0&type=' + type);
            $('#resolvedBuildBox').load(resolvedLink, function()
            {
                $(this).find('select').val(oldResolvedBuild);
            });
        }
    }
}

function loadExecutionBuilds(executionID, num)
{
    if(typeof(num) == 'undefined') num = '';

    let branch           = $('#branch' + num).val();
    let productID        = $('#product' + num).val();
    const oldOpenedBuild = $('#openedBuild' + num).val() ? $('#openedBuild' + num).val() : 0;
    if(typeof(branch) == 'undefined')    branch    = 0;
    if(typeof(productID) == 'undefined') productID = 0;

    if(config.currentMethod == 'create')
    {
        const link = $.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + "&branch=" + branch + "&index=0&needCreate=true");
        $.get(link, function(data)
        {
            $('#openedBuild').replaceWith(data);
            $('#openedBuild').val(oldOpenedBuild);
        })
    }
    else
    {
        const openedLink = $.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&index=0&needCreate=false&type=normal&number=' + num);
        $('#openedBuildBox' + num).load(openedLink, function()
        {
            $(this).find('select').val(oldOpenedBuild);
        });

        const oldResolvedBuild = $('#resolvedBuild').val() ? $('#resolvedBuild').val() : 0;
        const resolvedLink     = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch);
        $('#resolvedBuildBox').load(resolvedLink, function()
        {
            $(this).find('select').val(oldResolvedBuild);
        });
    }
}

function loadProductPlans(productID)
{
    let branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('productplan', 'ajaxGetProductplans', 'productID=' + productID + '&branch=' + branch);
    $('#planBox').load(link);
}

function loadProductStories(productID, moduleID = 0, oldStoryID = 0)
{
    let branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    if(typeof(oldStoryID) == 'undefined') oldStoryID = 0;

    let executionID = $('#execution').val();
    if(typeof(executionID) == 'undefined') executionID = 0;

    const link = $.createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=0&storyID=' + oldStoryID + '&onlyOption=false&status=&limit=0&type=full&hasParent=0&executionID=' + executionID);
    $('#storyBox').load(link);
}

function loadExecutionStories(executionID, num)
{
    if(typeof(num) == 'undefined') num = '';

    const productID = $('#product' + num).val();
    let   branch    = $('#branch' + num).val();
    if(typeof(branch) == 'undefined')     branch     = 0;
    if(typeof(oldStoryID) == 'undefined') oldStoryID = 0;

    const link = $.createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=' + productID + '&branch=' + branch + '&moduleID=0&storyID=' + oldStoryID + '&number=' + num + '&type=full&status=all&from=bug');
    $('#storyBox' + num).load(link);
}

function loadExecutionTasks(executionID)
{
    const link = $.createLink('task', 'ajaxGetExecutionTasks', 'executionID=' + executionID + '&taskID=' + oldTaskID);
    $.post(link, function(data)
    {
        $('#task').replaceWith(data);
    })
}

function loadProjectByExecutionID(executionID)
{
    const link = $.createLink('project', 'ajaxGetPairsByExecution', 'executionID=' + executionID, 'json');
    $.post(link, function(data)
    {
        if($('#project').find('option[value="' + data.id + '"]').length > 0)
        {
            $('#project').val(data.id);
        }
        else
        {
            $('#project').append('<option value="' + data.id + '" data-keys="' + data.namePinyin + '" selected="selected">' + data.name + '</option>');
        }
    }, 'json')
}

function loadTestTasks(productID, executionID)
{
    if(!$('#testtaskBox').length) return;
    if(typeof(executionID) == 'undefined') executionID = 0;

    const link = $.createLink('testtask', 'ajaxGetTestTasks', 'productID=' + productID + '&executionID=' + executionID);
    $.get(link, function(data)
    {
        var defaultOption = '<option title="' + oldTestTaskTitle + '" value="' + oldTestTask + '" selected="selected">' + oldTestTaskTitle + '</option>';
        $('#testtaskBox').html(data);
        $('#testtask').append(defaultOption);
    });
}

function loadAllBuilds(event)
{
    const productID = $('#product').val();
    const buildBox  = $(event.target).prev().attr('id');
    loadProductBuilds(productID, 'all', buildBox);
}

function loadAllUsers(event)
{
    isClosedBug = typeof isClosedBug == 'undefined' ? false : isClosedBug;

    const params = isClosedBug ? '&params=devfirst' : '';
    const link   = $.createLink('bug', 'ajaxLoadAllUsers', 'selectedUser=' + $('#assignedTo').val() + params);
    $.get(link, function(data)
    {
        if(data)
        {
            $('#assignedTo').replaceWith(data);
            if(!isClosedBug)
            {
                const moduleID  = $('#module').val();
                const productID = $('#product').val();
                loadAssignedToByModule(moduleID, productID);
            }
        }
    });
}

function setBranchRelated(event)
{
    var branchID = $(event.target).val();
    var num      = $(event.target).closest('tr').attr('data-index');

    const productID = $('[name="product"]').val();

    var currentModuleID = config.currentMethod == 'batchedit' ? $('#modules_' + num).val() : 0;
    var moduleLink      = $.createLink('tree', 'ajaxGetModules', 'productID=' + productID + '&viewType=bug&branch=' + branchID + '&num=' + num + '&currentModuleID=' + currentModuleID);
    $.get(moduleLink, function(modules)
    {
        if(!modules) modules = '<select id="modules_' + num + '" name="modules[' + num + ']" class="form-control"></select>';
        $('#modules_' + num).replaceWith(modules);
    });

    var projectLink = $.createLink('product', 'ajaxGetProjectsByBranch', 'productID=' + productID + '&branch=' + branchID + '&num=' + num);
    $.get(projectLink, function(projects)
    {
        if(!projects) projects = '<select id="projects' + num + '" name="projects[' + num + ']" class="form-control"></select>';
        $('#projects_' + num).replaceWith(projects);
    });

    var executionLink = $.createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=0&branch=' + branchID + '&num=' + num);
    $.get(executionLink, function(executions)
    {
        if(!executions) executions = '<select id="executions' + num + '" name="executions[' + num + ']" class="form-control"></select>';
        $('#executions_' + num).replaceWith(executions);
    });

    /* If the branch of the current row is inconsistent with the one below, clear the module and execution of the nex row. */
    if(config.currentMethod == 'batchcreate')
    {
        var nextBranchID = $('#branches_' + (num + 1)).val();
        if(nextBranchID != branchID)
        {
            $('#modules_' + (num + 1)).closest('.form-batch-control').attr('data-ditto', 'off');
            $('#modules_' + (num + 1)).trigger("chosen:updated");

            $('#executions_' + (num + 1)).closest('.form-batch-control').attr('data-ditto', 'off');
            $('#executions_' + (num + 1)).trigger("chosen:updated");
        }

      var buildLink = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + "&varName=openedBuilds&build=&branch=" + branchID + "&index=" + num);
      setOpenedBuilds(buildLink, num);
    }

    if(config.currentMethod == 'batchedit')
    {
        var planID   = $('#plans_' + num).val();
        var planLink = $.createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&branch=' + branchID + '&planID=' + planID + '&fieldID=' + num + '&needCreate=false&expired=&param=skipParent');
        $.ajaxSettings.async = false;
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
        $.ajaxSettings.async = true;
    }
}
