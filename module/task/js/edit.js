$(function() {
    $("#story").chosen({no_results_text: noResultsMatch});
    $("#mailto").autocomplete(userList, { multiple: true, mustMatch: true});
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
    if(changeProjectConfirmed || firstChoice)
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
    $('#moduleIdBox').load(link);
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
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&moduleId=0&storyID=' + oldStoryID);
    $('#storyIdBox').load(link, function(){$('#story').chosen();});
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
    link = createLink('project', 'ajaxGetMembers', 'projectID=' + projectID);
    $('#assignedToIdBox').load(link);
}
