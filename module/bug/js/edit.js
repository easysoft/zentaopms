/**
 * Load all fields.
 * 
 * @param  int $productID 
 * @access public
 * @return void
 */
function loadAll(productID)
{
    if(!changeProductConfirmed)
    {
         firstChoice = confirm(confirmChangeProduct);
         changeProductConfirmed = true;    // Only notice the user one time.
    }
    if(changeProductConfirmed || firstChoice)
    {
        $('#taskIdBox').get(0).innerHTML = emptySelect;
        loadModuleMenu(productID); 
        loadProductStories(productID);
        loadProductProjects(productID); 
        loadProductBuilds(productID);
    }
}

/**
 * Load module menu.
 * 
 * @param  int    $productID 
 * @access public
 * @return void
 */
function loadModuleMenu(productID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug');
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
    $('#storyIdBox').load(link);
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
    $('#openedBuildBox').load(link);
    link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild);
    $('#resolvedBuildBox').load(link);
}

/**
 * loadProjectRelated 
 * 
 * @param  projectID $projectID 
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
    }
    else
    {
        $('#taskIdBox').get(0).innerHTML = emptySelect;
        loadProductStories($('#product').get(0).value);
    }
}

/**
 * loadProjectTasks 
 * 
 * @param  projectID $projectID 
 * @access public
 * @return void
 */
function loadProjectTasks(projectID)
{
    link = createLink('task', 'ajaxGetProjectTasks', 'projectID=' + projectID + '&taskID=' + oldTaskID);
    $('#taskIdBox').load(link);
}

/**
 * loadProjectStories 
 * 
 * @param  projectID $projectID 
 * @access public
 * @return void
 */
function loadProjectStories(projectID)
{
    productID = $('#product').get(0).value; 
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=' + productID + '&storyID=' + oldStoryID);
    $('#storyIdBox').load(link);
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
    link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild);
    $('#openedBuildBox').load(link);
    link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild);
    $('#resolvedBuildBox').load(link);
}

/**
 * Set duplicate field.
 * 
 * @param  string $resolution 
 * @access public
 * @return void
 */
function setDuplicate(resolution)
{
    if(resolution == 'duplicate')
    {
        $('#duplicateBugBox').show();
    }
    else
    {
        $('#duplicateBugBox').hide();
    }
}

/**
 * Get story or task list.
 * 
 * @param  string $module 
 * @access public
 * @return void
 */
function getList(module)
{
    productID = $('#product').get(0).value;
    projectID = $('#project').get(0).value;
    storyID   = $('#story').get(0).value;
    taskID    = $('#task').get(0).value;
    if(module == 'story')
    {
        link = createLink('search', 'select', 'productID=' + productID + '&projectID=' + projectID + '&module=story&moduleID=' + storyID);
        $('#storyListIdBox a').attr("href", link);
    }
    else
    {
        link = createLink('search', 'select', 'productID=' + productID + '&projectID=' + projectID + '&module=task&moduleID=' + taskID);
        $('#taskListIdBox a').attr("href", link);
    }
}

$(function() {
    $("#mailto").autocomplete(userList, { multiple: true, mustMatch: true});
    $("#searchStories").colorbox({width:680, height:400, iframe:true, transition:'none'});
    $("#searchTasks").colorbox({width:680, height:400, iframe:true, transition:'none'});
});
