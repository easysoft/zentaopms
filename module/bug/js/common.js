$(function()
{
    var page = window.page || '';
    var flow = window.flow;
    if(typeof(systemMode) == undefined) var systemMode = '';

    $('#subNavbar a[data-toggle=dropdown]').parent().addClass('dropdown dropdown-hover');

    if(page == 'create' || page == 'edit' || page == 'assignedto' || page == 'confirmbug')
    {
        oldProductID = $('#product').val();
    }

    if(window.flow != 'full')
    {
        $('.querybox-toggle').click(function()
        {
            $(this).parent().toggleClass('active');
        });
    }
});

/**
 * Load all fields.
 *
 * @param  int $productID
 * @access public
 * @return void
 */
function loadAll(productID)
{
    if(typeof(changeProductConfirmed) != 'undefined' && !changeProductConfirmed)
    {
        firstChoice = confirm(confirmChangeProduct);
        changeProductConfirmed = true;    // Only notice the user one time.

        if(!firstChoice)
        {
            $('#product').val(oldProductID);//Revert old product id if confirm is no.
            $('#product').trigger("chosen:updated");
            $('#product').chosen();
            return true;
        }

        loadAll(productID);
    }
    else
    {
        $('#taskIdBox').innerHTML = '<select id="task"></select>';  // Reset the task.
        $('#task').chosen();
        loadProductBranches(productID)
    }
}

/**
  * Load all users as assignedTo list.
  *
  * @access public
  * @return void
  */
function loadAllUsers()
{
    isClosedBug = typeof isClosedBug == 'undefined' ? false : isClosedBug;

    var params = isClosedBug ? '&params=devfirst' : '';
    var link   = createLink('bug', 'ajaxLoadAllUsers', 'selectedUser=' + $('#assignedTo').val() + params);
    $.get(link, function(data)
    {
        if(data)
        {
            if(!isClosedBug)
            {
                var moduleID  = $('#module').val();
                var productID = $('#product').val();
                setAssignedTo(moduleID, productID);
            }

            $('#assignedTo').replaceWith(data);
            $('#assignedTo_chosen').remove();
            $('#assignedTo').chosen();
        }
    });
}

/**
 * Set the assignedTo field.
 *
 * @param  int    $moduleID
 * @param  int    $productID
 * @access public
 * @return void
 */
function setAssignedTo(moduleID, productID)
{
    if(typeof(productID) == 'undefined') productID = $('#product').val();
    if(typeof(moduleID) == 'undefined')  moduleID  = $('#module').val();
    var link = createLink('bug', 'ajaxGetModuleOwner', 'moduleID=' + moduleID + '&productID=' + productID);
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
        $("#assignedTo").trigger("chosen:updated");
    });
}


/**
 * Load by branch.
 *
 * @access public
 * @return void
 */
function loadBranch()
{
    $('#taskIdBox').innerHTML = '<select id="task"></select>';  // Reset the task.
    $('#task').chosen();
    productID = $('#product').val();
    loadProductModules(productID);
    loadProductProjects(productID);
    loadProductBuilds(productID);
    loadProductplans(productID);
    loadProductStories(productID);
    loadProductMembers(productID);
}

/**
  *Load all builds of one execution or product.
  *
  * @param  object $object
  * @access public
  * @return void
  */
function loadAllBuilds(object)
{
    if(page == 'resolve')
    {
        oldResolvedBuild = $('#resolvedBuild').val() ? $('#resolvedBuild').val() : 0;
        link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=0&index=0&type=all');
        $('#resolvedBuildBox').load(link, function(){$(this).find('select').chosen()});
    }
    else
    {
        productID   = $('#product').val();
        executionID = $('#execution').val();

        var buildBox = '';
        if(page == 'edit') buildBox = $(object).closest('.input-group').attr('id');

        if(executionID)
        {
            loadAllExecutionBuilds(executionID, productID, buildBox);
        }
        else
        {
            loadAllProductBuilds(productID, buildBox);
        }
    }
}

/**
  * Load all builds of the execution.
  *
  * @param  int    $executionID
  * @param  int    $productID
  * @param  string $buildBox
  * @access public
  * @return void
  */
function loadAllExecutionBuilds(executionID, productID, buildBox)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    if(page == 'create')
    {
        oldOpenedBuild = $('#openedBuild').val() ? $('#openedBuild').val() : 0;
        link = createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&index=0&needCreate=true&type=all');
        $.get(link, function(data)
        {
            if(!data) data = '<select id="openedBuild" name="openedBuild" class="form-control" multiple=multiple></select>';
            $('#openedBuild').replaceWith(data);
            $('#openedBuild_chosen').remove();
            $('#openedBuild').next('.picker').remove();
            $("#openedBuild").chosen();
            notice();
        })
    }
    if(page == 'edit')
    {
        if(buildBox == 'openedBuildBox')
        {
            link = createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&index=0&needCreate=true&type=all');
            $('#openedBuildBox').load(link, function(){$(this).find('select').chosen()});
        }
        if(buildBox == 'resolvedBuildBox')
        {
            link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch + '&index=0&type=all');
            $('#resolvedBuildBox').load(link, function(){$(this).find('select').chosen()});
        }
    }
}

/**
  * Load all builds of the product.
  *
  * @param  int    $productID
  * @param  string $buildBox
  * @access public
  * @return void
  */
function loadAllProductBuilds(productID, buildBox)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    if(page == 'create')
    {
        link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&index=0&type=all');
        $.get(link, function(data)
        {
            if(!data) data = '<select id="openedBuild" name="openedBuild" class="form-control" multiple=multiple></select>';
            $('#openedBuild').replaceWith(data);
            $('#openedBuild_chosen').remove();
            $('#openedBuild').next('.picker').remove();
            $("#openedBuild").chosen();
            notice();
        })
    }
    if(page == 'edit')
    {
        if(buildBox == 'openedBuildBox')
        {
            link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&index=0&type=all');
            $('#openedBuildBox').load(link, function(){$(this).find('select').chosen()});
        }
        if(buildBox == 'resolvedBuildBox')
        {
            link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch + '&index=0&type=all');
            $('#resolvedBuildBox').load(link, function(){$(this).find('select').chosen()});
        }
    }
}

/**
 * Load product's modules.
 *
 * @param  int    $productID
 * @access public
 * @return void
 */
function loadProductModules(productID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    if(typeof(moduleID) == 'undefined') moduleID = 0;
    if(config.currentMethod == 'edit') moduleID = $('#module').val();
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true&extra=&currentModuleID=' + moduleID);
    $('#moduleIdBox').load(link, function()
    {
        $(this).find('select').chosen()
        if(typeof(bugModule) == 'string') $('#moduleIdBox').prepend("<span class='input-group-addon' style='border-left-width: 1px;'>" + bugModule + "</span>");
    });
}

/**
 * Load product stories
 *
 * @param  int    $productID
 * @access public
 * @return void
 */
function loadProductStories(productID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    if(typeof(oldStoryID) == 'undefined') oldStoryID = 0;
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleId=0&storyID=' + oldStoryID);
    $('#storyIdBox').load(link, function(){$('#story').chosen();});
}

/**
 * Load projects of product.
 *
 * @param  int    $productID
 * @access public
 * @return void
 */
function loadProductProjects(productID)
{
    if(systemMode == 'classic')
    {
        var projectID = $('#execution').find("option:selected").val();
        loadProductExecutions(productID, projectID);
        return true;
    }

    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;

    link = createLink('product', 'ajaxGetProjects', 'productID=' + productID + '&branch=' + branch + '&projectID=' + oldProjectID);
    $('#projectBox').load(link, function()
    {
        $(this).find('select').chosen();
        var projectID = $('#project').find("option:selected").val();
        loadProductExecutions(productID, projectID);
    });
}

/**
 * Load executions of product.
 *
 * @param  int    $productID
 * @param  int    $projectID
 * @access public
 * @return void
 */
function loadProductExecutions(productID, projectID = 0)
{
    required = $('#execution_chosen').hasClass('required');
    branch   = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;

    var execution = $('#execution').val();
    link = createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=' + projectID + '&branch=' + branch + '&number=&executionID=' + execution);
    $('#executionIdBox').load(link, function()
    {
        $(this).find('select').chosen();
        if(typeof(bugExecution) == 'string' && systemMode != 'classic') $('#executionIdBox').prepend("<span class='input-group-addon' id='executionBox' style='border-left-width: 0px;'>" + bugExecution + "</span>");
        if(required) $(this).find('#execution_chosen').addClass('required');
        changeAssignedTo(projectID);
    });

    projectID != 0 ? loadProjectBuilds(projectID) : loadProductBuilds(productID);
}

/**
 * Ajax change execution name.
 *
 * @param  int $projectID
 * @access public
 * @return void
 */
function changeAssignedTo(projectID)
{
    if(parseInt(projectID))
    {
        loadProjectTeamMembers(projectID);
        if(page == 'create')
        {
            var link = createLink('bug', 'ajaxGetExecutionLang', 'projectID=' + projectID);
            $.post(link, function(executionLang)
            {
                $('#executionBox').html(executionLang);
            })
        }
    }
    else if($('#execution').val() != 0)
    {
        loadAssignedTo($('#execution').val());
    }
    else
    {
        var productID = $('#product').val();
        loadProductMembers(productID);
    }
}

/**
 * Load executions of product and project.
 *
 * @param  int    $productID
 * @param  int    $projectID
 * @param  int    $num
 * @access public
 * @return void
 */
function loadProductExecutionsByProject(productID, projectID = 0, num = 0)
{
    var branch = $('#branches' + num).val();
    if(typeof(branch) == 'undefined') branch = 0;

    if(projectID == 'ditto')
    {
        for(var i = num - 1; i > 0, projectID == 'ditto'; i--)
        {
            projectID = $('#projects' + i).val();
        }
    }

    var link = createLink('product', 'ajaxGetExecutionsByProject', 'productID=' + productID + '&projectID=' + projectID + '&branch=' + branch + '&number=' + num);
    $.get(link, function(executions)
    {
        if(!executions) executions = '<select id="executions' + num + '" name="executions[' + num + ']" class="form-control"></select>';
        $('#executions' + num).replaceWith(executions);
        $("#executions" + num + "_chosen").remove();
        $("#executions" + num).next('.picker').remove();
        $("#executions" + num).chosen();
    });
}

/**
 * Load product plans.
 *
 * @param  productID $productID
 * @access public
 * @return void
 */
function loadProductplans(productID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('productplan', 'ajaxGetProductplans', 'productID=' + productID + '&branch=' + branch);
    $('#planIdBox').load(link, function(){$(this).find('select').chosen()});
}

/**
 * Load product builds.
 *
 * @param  productID $productID
 * @access public
 * @return void
 */
function loadProductBuilds(productID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    if(typeof(oldOpenedBuild) == 'undefined') oldOpenedBuild = 0;
    link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch);

    if(page == 'create')
    {
        $.get(link, function(data)
        {
            if(!data) data = '<select id="openedBuild" name="openedBuild" class="form-control" multiple=multiple></select>';
            $('#openedBuild').replaceWith(data);
            $('#openedBuild_chosen').remove();
            $('#openedBuild').next('.picker').remove();
            $("#openedBuild").chosen();
            notice();
        })
    }
    else
    {
        $('#openedBuildBox').load(link, function(){$(this).find('select').chosen()});
        link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch);
        $('#resolvedBuildBox').load(link, function(){$(this).find('select').chosen()});
    }
}

/**
 * Load execution related bugs and tasks.
 *
 * @param  int    $executionID
 * @access public
 * @return void
 */
function loadExecutionRelated(executionID)
{
    executionID = parseInt(executionID);
    if(executionID)
    {
        loadExecutionTasks(executionID);
        loadExecutionStories(executionID);
        loadExecutionBuilds(executionID);
        loadAssignedTo(executionID, $('#assignedTo').val());
        loadTestTasks($('#product').val(), executionID);
    }
    else
    {
        var currentProjectID = $('#project').val() == 'undefined' ? 0 : $('#project').val();
        var currentProductID = $('#product').val();

        $('#taskIdBox').innerHTML = '<select id="task"></select>';  // Reset the task.
        loadProductStories(currentProductID);
        loadTestTasks(currentProductID);
        if(currentProjectID == 0)
        {
            loadProductMembers(currentProductID);
        }
        else
        {
            loadProjectTeamMembers(currentProjectID);
        }

        currentProjectID != 0 ? loadProjectBuilds(currentProjectID) : loadProductBuilds(currentProductID);
    }
}

/**
 * Load execution tasks.
 *
 * @param  executionID $executionID
 * @access public
 * @return void
 */
function loadExecutionTasks(executionID)
{
    link = createLink('task', 'ajaxGetExecutionTasks', 'executionID=' + executionID + '&taskID=' + oldTaskID);
    $.post(link, function(data)
    {
        if(!data) data = '<select id="task" name="task" class="form-control"></select>';
        $('#task').replaceWith(data);
        $('#task_chosen').remove();
        $('#task').next('.picker').remove();
        $("#task").chosen();
    })
}

/**
 * Load execution stories.
 *
 * @param  int  executionID
 * @param  int  num
 * @access public
 * @return void
 */
function loadExecutionStories(executionID, num)
{
    if(typeof(num) == 'undefined') num = '';

    var productID = $('#product' + num).val();
    var branch    = $('#branch' + num).val();

    if(typeof(branch) == 'undefined') branch = 0;
    if(typeof(oldStoryID) == 'undefined') oldStoryID = 0;

    var link = createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=' + productID + '&branch=' + branch + '&moduleID=0&storyID=' + oldStoryID + '&number=' + num + '&type=full&status=all&from=bug');
    $('#storyIdBox' + num).load(link, function(){$('#story' + num).chosen();});
}

/**
 * Load builds of a project.
 *
 * @param  int      projectID
 * @access public
 * @return void
 */
function loadProjectBuilds(projectID)
{
    var branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    var productID      = $('#product').val();
    var oldOpenedBuild = $('#openedBuild').val() ? $('#openedBuild').val() : 0;

    if(page == 'create')
    {
        var link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=&branch=' + branch);
        $.get(link, function(data)
        {
            if(!data) data = '<select id="openedBuild" name="openedBuild" class="form-control" multiple=multiple></select>';
            $('#openedBuild').replaceWith(data);
            $('#openedBuild').val(oldOpenedBuild);
            $('#openedBuild_chosen').remove();
            $('#openedBuild').next('.picker').remove();
            $("#openedBuild").chosen();
            notice();
        })
    }
    else
    {
        var link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch);
        $('#openedBuildBox').load(link, function(){$(this).find('select').val(oldOpenedBuild).chosen()});

        var oldResolvedBuild = $('#resolvedBuild').val() ? $('#resolvedBuild').val() : 0;
        var link             = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch);
        $('#resolvedBuildBox').load(link, function(){$(this).find('select').val(oldResolvedBuild).chosen()});
    }
}

/**
 * Load builds of a execution.
 *
 * @param  int      executionID
 * @param  int      num
 * @access public
 * @return void
 */
function loadExecutionBuilds(executionID, num)
{
    if(typeof(num) == 'undefined') num = '';
    var branch = $('#branch' + num).val();

    if(typeof(branch) == 'undefined') var branch = 0;

    var oldOpenedBuild = $('#openedBuild' + num).val() ? $('#openedBuild' + num).val() : 0;
    var productID      = $('#product' + num).val();

    if(page == 'create')
    {
        link = createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + "&branch=" + branch + "&index=0&needCreate=true");
        $.get(link, function(data)
        {
            if(!data) data = '<select id="openedBuild" name="openedBuild" class="form-control" multiple=multiple></select>';
            $('#openedBuild').replaceWith(data);
            $('#openedBuild').val(oldOpenedBuild);
            $('#openedBuild_chosen').remove();
            $('#openedBuild').next('.picker').remove();
            $("#openedBuild").chosen();
            notice();
        })
    }
    else
    {
        link = createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&index=0&needCreate=false&type=normal&number=' + num);
        $('#openedBuildBox' + num).load(link, function(){$(this).find('select').val(oldOpenedBuild).chosen()});

        oldResolvedBuild = $('#resolvedBuild').val() ? $('#resolvedBuild').val() : 0;
        link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch);
        $('#resolvedBuildBox').load(link, function(){$(this).find('select').val(oldResolvedBuild).chosen()});
    }
}

/**
 * Load product members.
 *
 * @param  productID
 * @access public
 * @return void
 */
function loadProductMembers(productID)
{
    var branchID = $('#branch').val() == undefined ? '' : $('#branch').val();
    var link     = createLink('bug', 'ajaxGetProductMembers', 'productID=' + productID + '&selectedUser=' + $('#assignedTo').val() + '&branchID=' + branchID);
    $.get(link, function(data)
    {
        if(!data) data = '<select id="assignedTo" name="assignedTo" class="form-control"></select>';
        $('#assignedTo').replaceWith(data);
        $('#assignedTo_chosen').remove();
        $("#assignedTo").chosen();
    });
}

/**
 * Load project members.
 *
 * @param  projectID $projectID
 * @access public
 * @return void
 */
function loadProjectTeamMembers(projectID)
{
    link = createLink('bug', 'ajaxGetProjectTeamMembers', 'projectID=' + projectID + '&selectedUser=' + $('#assignedTo').val());
    $.get(link, function(data)
    {
        if(!data) data = '<select id="assignedTo" name="assignedTo" class="form-control"></select>';
        $('#assignedTo').replaceWith(data);
        $('#assignedTo_chosen').remove();
        $("#assignedTo").chosen();
    });
}

/**
 * Set story field.
 *
 * @param  moduleID $moduleID
 * @param  productID $productID
 * @param  storyID $storyID
 * @access public
 * @return void
 */
function setStories(moduleID, productID, storyID)
{
    var branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    var executionID = $('#execution').val();
    if(typeof(executionID) == 'undefined') executionID = 0;
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&storyID=' + storyID + '&onlyOption=false&status=&limit=0&type=full&hasParent=0&executionID=' + executionID);
    $.get(link, function(stories)
    {
        if(!stories) stories = '<select id="story" name="story" class="form-control"></select>';
        $('#story').replaceWith(stories);
        $('#story_chosen').remove();
        $('#story').next('.picker').remove();
        $("#story").chosen();
    });
}

/**
 * Load product branches.
 *
 * @param  int $productID
 * @access public
 * @return void
 */
function loadProductBranches(productID, param)
{
    $('#branch').remove();
    $('#branch_chosen').remove();
    $('#branch').next('.picker').remove();

    var branchStatus = page == 'create' ? 'active' : 'all';
    var oldBranch    = page == 'edit' ? bugBranch : 0;
    var param        = "productID=" + productID + "&oldBranch=" + oldBranch + "&param=" + branchStatus;
    if(typeof(tab) != 'undefined' && (tab == 'execution' || tab == 'project')) param += "&projectID=" + objectID;
    $.get(createLink('branch', 'ajaxGetBranches', param), function(data)
    {
        if(data)
        {
            $('#product').closest('.input-group').append(data);
            $('#branch').css('width', page == 'create' ? '120px' : '65px');
            $('#branch').chosen();
        }

        loadProductModules(productID);
        loadProductProjects(productID);
        loadProductplans(productID);
        loadProductStories(productID);
        loadProductMembers(productID);
    })
}

/**
 * Load team members of the execution as assignedTo list.
 *
 * @param  int     $executionID
 * @access public
 * @return void
 */
function loadAssignedTo(executionID, selectedUser)
{
    selectedUser = (typeof(selectedUser) == 'undefined') ? '' : $('#assignedTo').val();
    link = createLink('bug', 'ajaxLoadAssignedTo', 'executionID=' + executionID + '&selectedUser=' + selectedUser);
    $.get(link, function(data)
    {
        $('#assignedTo_chosen').remove();
        $('#assignedTo').next('.picker').remove();
        $('#assignedTo').replaceWith(data);
        $('#assignedTo').chosen();
    });
}

var oldTestTaskTitle = $("#testtask").find("option:selected").text();
var oldTestTask      = $("#testtask").find("option:selected").val();

/**
 * Load test tasks.
 *
 * @param  int $productID
 * @param  int $executionID
 * @access public
 * @return void
 */
function loadTestTasks(productID, executionID)
{
    if(!$('#testtaskBox').length) return;

    if(typeof(executionID) == 'undefined') executionID = 0;
    link = createLink('testtask', 'ajaxGetTestTasks', 'productID=' + productID + '&executionID=' + executionID);
    $.get(link, function(data)
    {
        var defaultOption = '<option title="' + oldTestTaskTitle + '" value="' + oldTestTask + '" selected="selected">' + oldTestTaskTitle + '</option>';
        $('#testtaskBox').html(data);
        $('#testtask').append(defaultOption);
        $('#testtask').chosen();
    });
}

/**
 * notice for create build.
 *
 * @access public
 * @return void
 */
function notice()
{
    if(page == 'edit') return;

    $('#buildBoxActions').empty().hide();
    if($('#openedBuild').find('option').length <= 1)
    {
        var html = '';
        if($('#execution').length == 0 || $('#execution').val() == 0)
        {
            var branch = $('#branch').val();
            if(typeof(branch) == 'undefined') branch = 0;
            var link = createLink('release', 'create', 'productID=' + $('#product').val() + '&branch=' + branch);
            if(config.onlybody != 'yes') link += link.indexOf('?') >= 0 ? '&onlybody=yes' : '?onlybody=yes';
            html += '<a href="' + link + '" data-toggle="modal" data-type="iframe" style="padding-right:5px">' + createRelease + '</a> ';
            html += '<a href="javascript:loadProductBuilds(' + $('#product').val() + ')">' + refresh + '</a>';
        }
        else
        {
            executionID = $('#execution').val();
            productID   = $('#product').val();
            projectID   = $('#project').val();
            link = createLink('build', 'create','executionID=' + executionID + '&productID=' + productID + '&projectID=' + projectID);
            link += link.indexOf('?') >= 0 ? '&onlybody=yes' : '?onlybody=yes';
            html += '<a href="' + link + '" data-toggle="modal" data-type="iframe" style="padding-right:5px">' + createBuild + '</a> ';
            html += '<a href="javascript:loadExecutionBuilds(' + executionID + ')">' + refresh + '</a>';
        }
        var $bba = $('#buildBoxActions');
        if($bba.length)
        {
            $bba.html(html);
            $bba.show();
        }
        else
        {
            if($('#buildBox').closest('tr').find('td').size() > 1)
            {
                $('#buildBox').closest('td').next().attr('id', 'buildBoxActions');
                $('#buildBox').closest('td').next().html(html);
            }
            else
            {
                html = "<td id='buildBoxActions'>" + html + '</td>';
                $('#buildBox').closest('td').after(html);
            }
        }
    }
}

/**
 * Set branch related.
 *
 * @param  int     $branchID
 * @param  int     $productID
 * @param  int     $num
 * @access public
 * @return void
 */
function setBranchRelated(branchID, productID, num)
{
    var currentModuleID = config.currentMethod == 'batchedit' ? $('#modules' + num).val() : 0;
    var moduleLink      = createLink('tree', 'ajaxGetModules', 'productID=' + productID + '&viewType=bug&branch=' + branchID + '&num=' + num + '&currentModuleID=' + currentModuleID);
    $.get(moduleLink, function(modules)
    {
        if(!modules) modules = '<select id="modules' + num + '" name="modules[' + num + ']" class="form-control"></select>';
        $('#modules' + num).replaceWith(modules);
        $("#modules" + num + "_chosen").remove();
        $("#modules" + num).next('.picker').remove();
        $("#modules" + num).chosen();
    });

    var projectLink = createLink('product', 'ajaxGetProjectsByBranch', 'productID=' + productID + '&branch=' + branchID + '&num=' + num);
    $.get(projectLink, function(projects)
    {
        if(!projects) projects = '<select id="projects' + num + '" name="projects[' + num + ']" class="form-control"></select>';
        $('#projects' + num).replaceWith(projects);
        $("#projects" + num + "_chosen").remove();
        $("#projects" + num).next('.picker').remove();
        $("#projects" + num).chosen();
    });

    var executionLink = createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=0&branch=' + branchID + '&num=' + num);
    $.get(executionLink, function(executions)
    {
        if(!executions) executions = '<select id="executions' + num + '" name="executions[' + num + ']" class="form-control"></select>';
        $('#executions' + num).replaceWith(executions);
        $("#executions" + num + "_chosen").remove();
        $("#executions" + num).next('.picker').remove();
        $("#executions" + num).chosen();
    });

    var buildLink = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + "&varName=openedBuilds&build=&branch=" + branchID + "&index=" + num);

    /* If the branch of the current row is inconsistent with the one below, clear the module and execution of the nex row. */
    if(config.currentMethod == 'batchcreate')
    {
        var nextBranchID = $('#branch' + (num + 1)).val();
        if(nextBranchID != branchID)
        {
            $('#modules' + (num + 1)).find("option[value='ditto']").remove();
            $('#modules' + (num + 1)).trigger("chosen:updated");

            $('#executions' + (num + 1)).find("option[value='ditto']").remove();
            $('#executions' + (num + 1)).trigger("chosen:updated");
        }
        setOpenedBuilds(buildLink, num);
    }

    if(config.currentMethod == 'batchedit')
    {
        planID   = $('#plans' + num).val();
        planLink = createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&branch=' + branchID + '&planID=' + planID + '&fieldID=' + num + '&needCreate=false&expired=&param=skipParent');
        $('#plans' + num).parent('td').load(planLink, function()
        {
            $('#plans' + num).chosen();
        });
    }
}
