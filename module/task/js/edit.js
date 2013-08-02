$(function() 
{
    $("#story").chosen({no_results_text: noResultsMatch});
    $("#mailto").chosen({no_results_text: noResultsMatch});
    $('.iframe').colorbox({width:900, height:500, iframe:true, transition:'none', onCleanup:function(){parent.location.href=parent.location.href;}});
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
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=0&moduleID=0&storyID=' + oldStoryID);
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

/**
 * load stories of module.
 * 
 * @access public
 * @return void
 */
function loadModuleRelated()
{
    moduleID  = $('#module').val();
    projectID = $('#project').val();
    setStories(moduleID, projectID);
}

/* Get select of stories.*/
function setStories(moduleID, projectID)
{
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=0&moduleID=' + moduleID);
    $.get(link, function(stories)
    {
        if(!stories) stories = '<select id="story" name="story"></select>';
        $('#story').replaceWith(stories);
        $('#story_chzn').remove();
        $("#story").chosen({no_results_text: ''});
    });
}

/* empty function. */
function setPreview(){}
