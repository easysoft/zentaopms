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
    selectedBuild = $('#openedBuild').val();
    if(!selectedBuild) selectedBuild = 0;
    link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + $('#product').val() + '&varName=resolvedBuild&builds=' + selectedBuild);
    $('#buildBox').load(link);
}

