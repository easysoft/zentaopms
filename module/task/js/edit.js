$(function() 
{
    $("#story, #mailto").chosen(defaultChosenOptions);
    $('.iframe').modalTrigger({width:900, type:'iframe', afterHide:function(){parent.location.href=parent.location.href;}});
})

/**
 * Load module, stories and members. 
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadAll(projectID)
{
    if(!changeProjectConfirmed)
    {
         firstChoice = confirm(confirmChangeProject);
         changeProjectConfirmed = true;    // Only notice the user one time.
    }
    if(changeProjectConfirmed && firstChoice)
    {
        loadModuleMenu(projectID); 
        loadProjectStories(projectID);
        loadProjectMembers(projectID);
    }
}

/**
 * Load module of the project. 
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadModuleMenu(projectID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + projectID + '&viewtype=task');
    $('#moduleIdBox').load(link, function(){$('#module').chosen(defaultChosenOptions);});
}

/**
 * Load stories of the project. 
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadProjectStories(projectID)
{
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=0&branch=0&moduleID=0&storyID=' + oldStoryID);
    $('#storyIdBox').load(link, function(){$('#story').chosen(defaultChosenOptions);});
}

/**
 * Load team members of the project. 
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadProjectMembers(projectID)
{
    link = createLink('project', 'ajaxGetMembers', 'projectID=' + projectID + '&assignedTo=' + oldAssignedTo);
    $('#assignedToIdBox').load(link, function(){$('#assignedToIdBox').find('select').chosen(defaultChosenOptions)});
}

/* empty function. */
function setPreview(){}
