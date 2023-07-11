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
        loadAssignedTo();
        loadProductPlans(productID);
        loadProductStories(productID, bug.story);
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
    loadProductStories(productID, bug.story);
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
    const productID = $('#product').val();
    const storyID   = $('#story').val();
    let executionID = $('#execution').val();
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
    const productID = $('#product').val();
    loadProductModules(productID);
}

function refreshContact(event)
{
    loadContacts();
}

function refreshProductBuild(event)
{
    const productID = $('#product').val();
    loadProductBuilds(productID);
}

function refreshExecutionBuild(event)
{
    const executionID = $('#execution').val();
    loadExecutionBuilds(executionID);
}

function loadProductBranches(productID)
{
    $('#branch').remove();

    const branchStatus = config.currentMethod == 'create' ? 'active' : 'all';
    const oldBranch    = config.currentMethod == 'edit' ? bug.branch : 0;
    let   param        = "productID=" + productID + "&oldBranch=" + oldBranch + "&param=" + branchStatus;
    if(typeof(tab) != 'undefined' && (tab == 'execution' || tab == 'project')) param += "&projectID=" + bug[tab];
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

    const link = $.createLink('product', 'ajaxGetProjects', 'productID=' + productID + '&branch=' + branch + '&projectID=' + bug.project);
    $('#projectBox').load(link);
}

function loadExecutions(productID, projectID = 0)
{
    let branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=' + projectID + '&branch=' + branch + '&pageType=&executionID=from=&mode=stagefilter');
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

    if(config.currentMethod == 'create')
    {
        if(buildBox == 'all' || buildBox == 'openedBuild')
        {
            const link = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=&branch=' + branch + '&index=0&type=' + type);
            $.get(link, function(data)
            {
                $('#openedBuild').replaceWith(data);
                loadBuildActions();
            })
        }
    }
    else
    {
        if(buildBox == 'all' || buildBox == 'openedBuild')
        {
            const openedLink = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + bug.openedBuild + '&branch=' + branch + '&index=0&type=' + type);
            $('#openedBuildBox').load(openedLink, function()
            {
                $(this).find('select').val(bug.openedBuild);
            });
        }

        if(buildBox == 'all' || buildBox == 'resolvedBuild')
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
            loadBuildActions();
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

function loadProductStories(productID, storyID, moduleID = 0, executionID = 0)
{
    let branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&storyID=' + storyID + '&onlyOption=false&status=&limit=0&type=full&hasParent=0&executionID=' + executionID);
    $('#storyBox').load(link);
}

function loadExecutionStories(executionID, num)
{
    if(typeof(num) == 'undefined') num = '';

    const productID = $('#product' + num).val();
    let   branch    = $('#branch' + num).val();
    if(typeof(branch) == 'undefined') branch = 0;

    const link = $.createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=' + productID + '&branch=' + branch + '&moduleID=0&storyID=' + bug.story + '&number=' + num + '&type=full&status=all&from=bug');
    $('#storyBox' + num).load(link);
}

function loadExecutionTasks(executionID)
{
    const link = $.createLink('task', 'ajaxGetExecutionTasks', 'executionID=' + executionID + '&taskID=' + bug.task);
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
    const buildBox  = $(event.target).closest('.input-group').find('select').attr('id');
    loadProductBuilds(productID, 'all', buildBox);
}

function loadAllUsers(event)
{
    const isClosedBug = bug.status = 'closed';
    const params      = isClosedBug ? '&params=devfirst' : '';
    const link        = $.createLink('bug', 'ajaxLoadAllUsers', 'selectedUser=' + $('#assignedTo').val() + params);
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

function loadBuildActions()
{
    if(config.currentMethod == 'edit') return;
    $('#buildBoxActions').empty().hide();
    let itemCount = $('#openedBuild').find('option').length;
    if($('#openedBuild').attr('data-items') != undefined) itemCount = $('#openedBuild').attr('data-items');
    if(itemCount <= 1)
    {
        let html = '';
        if($('#execution').length == 0 || $('#execution').val() == 0)
        {
            let branch    = $('#branch').val();
            let projectID = $('#project').val();

            if(typeof(branch)    == 'undefined') branch    = 0;
            if(typeof(projectID) == 'undefined') projectID = 0;

            let link = $.createLink('release', 'create', 'productID=' + $('#product').val() + '&branch=' + branch);
            if(projectID > 0) link = $.createLink('projectrelease', 'create', 'projectID=' + projectID);

            html += '<a href="' + link + '" data-toggle="modal" style="padding-right:5px">' + createRelease + '</a> ';
            html += '<a href="javascript:;" id="refreshProductBuild">' + refresh + '</a>';
        }
        else
        {
            const executionID = $('#execution').val();
            const productID   = $('#product').val();
            const projectID   = $('#project').val();
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
