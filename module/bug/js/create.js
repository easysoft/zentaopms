/**
 * Load all fields. 
 * 
 * @param  int    $productID 
 * @access public
 * @return void
 */
function loadAll(productID)
{
    $('#taskIdBox').get(0).innerHTML = '<select id="task"></select>';  // Reset the task.
    loadModuleMenu(productID);
    loadProductStories(productID);
    loadProductProjects(productID);
    loadProductBuilds(productID); 
    setAssignedTo(); 
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
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID);
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
    link = createLink('product', 'ajaxGetProjects', 'productID=' + productID);
    $('#projectIdBox').load(link);
}

/**
 * Load product builds.
 * 
 * @param  int    $productID 
 * @access public
 * @return void
 */
function loadProductBuilds(productID)
{
    link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild');
    $('#buildBox').load(link);
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
    }
    else
    {
        $('#taskIdBox').get(0).innerHTML = '';
        loadProductStories($('#product').get(0).value);
        loadProductBuilds($('#product').get(0).value);
    }
}

/**
 * Load project tasks.
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadProjectTasks(projectID)
{
    link = createLink('task', 'ajaxGetProjectTasks', 'projectID=' + projectID);
    $('#taskIdBox').load(link);
}

/**
 * Load project stories.
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadProjectStories(projectID)
{
    productID = $('#product').get(0).value; 
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=' + productID);
    $('#storyIdBox').load(link);
}

/**
 * Set the assignedTo field.
 * 
 * @access public
 * @return void
 */
function setAssignedTo()
{
    link = createLink('bug', 'ajaxGetModuleOwner', 'moduleID=' + $('#module').val() + '&productID=' + $('#product').val());
    $.get(link, function(owner)
    {
        $('#assignedTo').val(owner);
    });
}

/**
 * Load project builds.
 * 
 * @param  int $projectID 
 * @access public
 * @return void
 */
function loadProjectBuilds(projectID)
{
    link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + $('#product').val() + '&varName=openedBuild');
    $('#buildBox').load(link);
}

$(function() {
    $("#mailto").autocomplete(userList, { multiple: true, mustMatch: true});
    setAssignedTo();
})
