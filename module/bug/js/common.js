$(function() 
{
    setModal4List('iframe', 'bugList');

    if(typeof page == 'undefined') page = '';
    if(page == 'create')
    {
        productID  = $('#product').val();
        moduleID   = $('#module').val();
        assignedto = $('#assignedTo').val();
        changeProductConfirmed = true;
        oldStoryID             = 0;
        oldProjectID           = 0;
        oldOpenedBuild         = '';
        oldTaskID              = 0;
        if(!assignedto) setAssignedTo(moduleID, productID);
        notice();
    }

    if(page == 'create' || page == 'edit' || page == 'assignedto' || page == 'confirmbug')
    {
        $("#story, #task, #mailto").chosen(defaultChosenOptions);
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
        $('#task').chosen(defaultChosenOptions);
        loadProductModules(productID); 
        loadProductProjects(productID); 
        loadProductBuilds(productID);
        loadProductplans(productID);
        loadProductStories(productID);
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
    $('#moduleIdBox').load(link, function(){$(this).find('select').chosen(defaultChosenOptions)});
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
    $('#storyIdBox').load(link, function(){$('#story').chosen(defaultChosenOptions);});
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
    $('#projectIdBox').load(link, function(){$(this).find('select').chosen(defaultChosenOptions)});
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
    link = createLink('productplan', 'ajaxGetProductplans', 'productID=' + productID);
    $('#planIdBox').load(link, function(){$(this).find('select').chosen(defaultChosenOptions)});
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
    link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild);

    if(page == 'create')
    {
        $('#buildBox').load(link, function(){ notice(); $('#openedBuild').chosen(defaultChosenOptions);});
    }
    else
    {
        $('#openedBuildBox').load(link, function(){$(this).find('select').chosen(defaultChosenOptions)});
        link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild);
        $('#resolvedBuildBox').load(link, function(){$(this).find('select').chosen(defaultChosenOptions)});
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
    $('#taskIdBox').load(link, function(){$('#task').chosen(defaultChosenOptions);});
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
    $('#storyIdBox').load(link, function(){$('#story').chosen(defaultChosenOptions);});
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
        $('#buildBox').load(link, function(){$('#openedBuild').chosen(defaultChosenOptions);});
    }
    else
    {
        link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild);
        $('#openedBuildBox').load(link, function(){$(this).find('select').chosen(defaultChosenOptions)});

        link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild);
        $('#resolvedBuildBox').load(link, function(){$(this).find('select').chosen(defaultChosenOptions)});
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
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&moduleID=' + moduleID);
    $.get(link, function(stories)
    {
        if(!stories) stories = '<select id="story" name="story" class="form-control"></select>';
        $('#story').replaceWith(stories);
        $('#story_chosen').remove();
        $("#story").chosen(defaultChosenOptions);
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
    $('#buildBoxActions').empty();
    if($('#openedBuild').find('option').length <= 1) 
    {
        var html = '';
        if($('#project').val() == '')
        {
            html += '<a href="' + createLink('release', 'create', 'productID=' + $('#product').val()) + '" target="_blank">' + createRelease + ' </a>';
            html += '<a href="javascript:loadProductBuilds(' + $('#product').val() + ')">' + refresh + '</a>';
        }
        else
        {
            html += '<a href="' + createLink('build', 'create','projectID=' + $('#project').val()) + '" target="_blank">' + createBuild + '</a>';
            html += '<a href="javascript:loadProjectBuilds(' + $('project').val() + ')">' + refresh + '</a>';
        }
        var $bba = $('#buildBoxActions');
        if($bba.length) $bba.html(html); else $('#buildBox').append(html);
    }
}
