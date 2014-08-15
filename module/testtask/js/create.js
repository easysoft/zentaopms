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
    link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + $('#product').val() + '&varName=testTaskBuild&builds=' + selectedBuild);
    $('#buildBox').load(link);
}

/**
 * Convert a date string like 2011-11-11 to date object in js.
 * 
 * @param  string $date 
 * @access public
 * @return date
 */
function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    dateString = dateString[1] + '/' + dateString[2] + '/' + dateString[0];
    
    return Date.parse(dateString);
}

/**
 * when begin date input change and end date input is null
 * change end date input to begin's after day
 * 
 * @access public
 * @return void
 */
function suitEndDate()
{
    beginDate = $('#begin').val();
    if(!beginDate) return;
    endDate = $('#end').val();
    if(endDate) return;
    
    endDate = convertStringToDate(beginDate).addDays(1);
    endDate = endDate.toString('yyyy-M-dd');
    $('#end').val(endDate);
}
