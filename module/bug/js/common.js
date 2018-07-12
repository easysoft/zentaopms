$(function()
{
    var page = window.page || '';
    var flow = window.flow;

    $('#subNavbar a[data-toggle=dropdown]').parent().addClass('dropdown dropdown-hover');
    if(page == 'create')
    {
        var productID  = $('#product').val();
        var moduleID   = $('#module').val();
        var assignedto = $('#assignedTo').val();
        changeProductConfirmed = true;
        oldStoryID             = $('#story').val() || 0;
        oldProjectID           = 0;
        oldOpenedBuild         = '';
        oldTaskID              = $('#oldTaskID').val() || 0;
        if(!assignedto) setAssignedTo(moduleID, productID);
        notice();
    }

    if(page == 'create' || page == 'edit' || page == 'assignedto' || page == 'confirmbug')
    {
        oldProductID = $('#product').val();
        $("#story, #task, #mailto").chosen();
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
    if(page == 'create') 
    {
        loadProjectTeamMembers(productID);
        setAssignedTo();
    }

    if(!changeProductConfirmed)
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
        loadProductModules(productID); 
        loadProductProjects(productID); 
        loadProductBuilds(productID);
        loadProductplans(productID);
        loadProductStories(productID);
    }
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
}

/**
  *Load all builds of one project or product.
  *
  * @access public
  * @return void
  */
function loadAllBuilds(that)
{
    if(page == 'resolve')
    {
        oldResolvedBuild = $('#resolvedBuild').val() ? $('#resolvedBuild').val() : 0;
        link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=0&index=0&type=all');
        $('#resolvedBuildBox').load(link, function(){$(this).find('select').chosen()});
    }
    else
    {
        productID = $('#product').val();
        projectID = $('#project').val();
        if(page == 'edit') buildBox = $(that).closest('.input-group').attr('id');

        if(projectID)
        {
            loadAllProjectBuilds(projectID, productID);
        }
        else
        {
            loadAllProductBuilds(productID);
        }
    }
}

/** 
  * Load all builds of the project.
  *
  * @param  int    $projectID
  * @param  int    $productID
  * @access public
  * @return void
  */
function loadAllProjectBuilds(projectID, productID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    if(page == 'create')
    {
        oldOpenedBuild = $('#openedBuild').val() ? $('#openedBuild').val() : 0;
        link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&index=0&needCreate=true&type=all');
        $('#buildBox').load(link, function(){ notice(); $('#openedBuild').chosen();});
    }
    if(page == 'edit')
    {
        if(buildBox == 'openedBuildBox')
        {
            link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&index=0&needCreate=true&type=all');
            $('#openedBuildBox').load(link, function(){$(this).find('select').chosen()});
        }
        if(buildBox == 'resolvedBuildBox')
        {
            link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=0&index=0&needCreate=true&type=all');
            $('#resolvedBuildBox').load(link, function(){$(this).find('select').chosen()});
        }
    }
}

/** 
  * Load all builds of the product.
  *
  * @param  int    $productID
  * @access public
  * @return void
  */
function loadAllProductBuilds(productID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    if(page == 'create') 
    {
        link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch + '&index=0&type=all');
        $('#buildBox').load(link, function(){ notice(); $('#openedBuild').chosen();});
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
            link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=0&index=0&type=all');
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
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true');
    $('#moduleIdBox').load(link, function()
    {
        $(this).find('select').chosen()
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
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('product', 'ajaxGetProjects', 'productID=' + productID + '&projectID=' + oldProjectID + '&branch=' + branch);
    $('#projectIdBox').load(link, function(){$(this).find('select').chosen()});
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
    link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch);

    if(page == 'create')
    {
        $('#buildBox').load(link, function(){ notice(); $('#openedBuild').chosen();});
    }
    else
    {
        $('#openedBuildBox').load(link, function(){$(this).find('select').chosen()});
        link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild + '&branch=' + branch);
        $('#resolvedBuildBox').load(link, function(){$(this).find('select').chosen()});
    }
}

/**
 * Load project related bugs and tasks.
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadProjectRelated(projectID)
{
    if(projectID)
    {
        loadProjectTasks(projectID);
        loadProjectStories(projectID);
        loadProjectBuilds(projectID);
        loadAssignedTo(projectID);
    }
    else
    {
        $('#taskIdBox').innerHTML = '<select id="task"></select>';  // Reset the task.
        loadProductStories($('#product').val());
        loadProductBuilds($('#product').val());
    }
}

/**
 * Load project tasks.
 * 
 * @param  projectID $projectID 
 * @access public
 * @return void
 */
function loadProjectTasks(projectID)
{
    link = createLink('task', 'ajaxGetProjectTasks', 'projectID=' + projectID + '&taskID=' + oldTaskID);
    $('#taskIdBox').load(link, function(){$('#task').chosen();});
}

/**
 * Load project stories.
 * 
 * @param  projectID $projectID 
 * @access public
 * @return void
 */
function loadProjectStories(projectID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=' + $('#product').val() + '&branch=' + branch + '&moduleID=0&storyID=' + oldStoryID);
    $('#storyIdBox').load(link, function(){$('#story').chosen();});
}

/**
 * Load builds of a project.
 * 
 * @param  int      $projectID 
 * @access public
 * @return void
 */
function loadProjectBuilds(projectID)
{
    branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    productID = $('#product').val();
    if(page == 'create') oldOpenedBuild = $('#openedBuild').val() ? $('#openedBuild').val() : 0;

    if(page == 'create')
    {
        link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + "&branch=" + branch + "&index=0&needCreate=true");
        $('#buildBox').load(link, function(){ notice(); $('#openedBuild').chosen();});
    }
    else
    {
        link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + '&branch=' + branch);
        $('#openedBuildBox').load(link, function(){$(this).find('select').chosen()});
    }
}

/**
 * Set story field.
 * 
 * @param  moduleID $moduleID 
 * @param  productID $productID 
 * @access public
 * @return void
 */
function setStories(moduleID, productID)
{
    var branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID);
    $.get(link, function(stories)
    {
        if(!stories) stories = '<select id="story" name="story" class="form-control"></select>';
        $('#story').replaceWith(stories);
        $('#story_chosen').remove();
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
function loadProductBranches(productID)
{
    $('#branch').remove();
    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + productID), function(data)
    {
        if(data)
        {
            $('#product').closest('.input-group').append(data);
            $('#branch').css('width', page == 'create' ? '120px' : '65px');
        }
    })
}

/**
 * Load team members of the project as assignedTo list.
 * 
 * @param  int     $projectID 
 * @access public
 * @return void
 */
function loadAssignedTo(projectID)
{
    link = createLink('bug', 'ajaxLoadAssignedTo', 'projectID=' + projectID + '&selectedUser=' + $('#assignedTo').val());
    $('#assignedToBox').load(link, function(){$('#assignedTo').chosen();});
}

/**
 * notice for create build.
 * 
 * @access public
 * @return void
 */
function notice()
{
    $('#buildBoxActions').empty().hide();
    if($('#openedBuild').find('option').length <= 1) 
    {
        var html = '';
        if($('#project').val() == '')
        {
            branch = $('#branch').val();
            if(typeof(branch) == 'undefined') branch = 0;
            html += '<a href="' + createLink('release', 'create', 'productID=' + $('#product').val() + '&branch=' + branch) + '" target="_blank" style="padding-right:5px">' + createRelease + '</a> ';
            html += '<a href="javascript:loadProductBuilds(' + $('#product').val() + ')">' + refresh + '</a>';
        }
        else
        {
            html += '<a href="' + createLink('build', 'create','projectID=' + $('#project').val()) + '" target="_blank" style="padding-right:5px">' + createBuild + '</a> ';
            html += '<a href="javascript:loadProjectBuilds(' + $('#project').val() + ')">' + refresh + '</a>';
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
