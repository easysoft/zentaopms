$(function() 
{
    setModal4List('iframe', 'bugList');

    if(typeof page == 'undefined') page = '';
    if(page == 'create')
    {
        changeProductConfirmed = true;
        oldStoryID             = 0;
        oldProjectID           = 0;
        oldOpenedBuild         = '';
        oldTaskID              = 0;
        setAssignedTo();
    }

    if(page == 'create' || page == 'edit' || page == 'assignedto' || page == 'confirmbug')
    {
        $("#story").chosen({no_results_text:noResultsMatch});
        $("#task").chosen({no_results_text:noResultsMatch});
        $("#mailto").chosen({no_results_text:noResultsMatch});
        $(".chzn-container-multi .chzn-choices li.search-field input").attr('value', mailto);
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
    if(page == 'create') setAssignedTo();

    if(!changeProductConfirmed)
    {
      firstChoice = confirm(confirmChangeProduct);
      changeProductConfirmed = true;    // Only notice the user one time.
    }
    if(changeProductConfirmed || firstChoice)
    {
        $('#taskIdBox').innerHTML = '<select id="task"></select>';  // Reset the task.
        $('#task').chosen({no_results_text: noResultsMatch});
        loadProductModules(productID); 
        loadProductStories(productID);
        loadProductProjects(productID); 
        loadProductBuilds(productID);
        loadProductPlans(productID);
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
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug&rootModuleID=0&returnType=html&needManage=true');
    $('#moduleIdBox').load(link);
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
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&moduleId=0&storyID=' + oldStoryID);
    $('#storyIdBox').load(link, function(){$('#story').chosen({no_results_text:noResultsMatch});});
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
    link = createLink('product', 'ajaxGetProjects', 'productID=' + productID + '&projectID=' + oldProjectID);
    $('#projectIdBox').load(link);
}

/**
 * loadProductBuilds 
 * 
 * @param  productID $productID 
 * @access public
 * @return void
 */
function loadProductBuilds(productID)
{
    link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild);

    if(page == 'create')
    {
        $('#buildBox').load(link);
    }
    else
    {
        $('#openedBuildBox').load(link);
        link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild);
        $('#resolvedBuildBox').load(link);
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
    $('#taskIdBox').load(link, function(){$('#task').chosen({no_results_text:noResultsMatch});});
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
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=' + $('#product').val() + '&moduleID=0&storyID=' + oldStoryID);
    $('#storyIdBox').load(link, function(){$('#story').chosen({no_results_text:noResultsMatch});});
}

/**
 * Load product plans. 
 * 
 * @param  int    $productID 
 * @access public
 * @return void
 */
function loadProductPlans(productID)
{
    if(typeof(planID) == 'undefined') planID = 0;
    link = createLink('product', 'ajaxGetPlans', 'productID=' + $('#product').val() + '&planID=' + planID);
    $('#planIdBox').load(link, function(){$('#story').chosen({no_results_text:noResultsMatch});});
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
    productID = $('#product').val();
    if(page == 'create') oldOpenedBuild = $('#openedBuild').val() ? $('#openedBuild').val() : 0;

    if(page == 'create')
    {
        link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild + "&index=0&needCreate=true");
        $('#buildBox').load(link);
    }
    else
    {
        link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild);
        $('#openedBuildBox').load(link);

        link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild);
        $('#resolvedBuildBox').load(link);
    }
}
