/**
 * Load project related builds
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadProjectRelated(projectID)
{
    loadProjectBuilds(projectID);
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
    selectedBuild = $('#build').val();
    if(!selectedBuild) selectedBuild = 0;
    link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + $('#product').val() + '&varName=testTaskBuild&build=' + selectedBuild);
    $('#buildBox').load(link, function(){$('#build').chosen();});
}

$(function()
{
    adjustPriBoxWidth();
})
