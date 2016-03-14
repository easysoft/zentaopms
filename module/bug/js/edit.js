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
    productID = $('#product').val();
    projectID = $('#project').val();
    storyID   = $('#story').val();
    taskID    = $('#task').val();
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

/**
 * load stories of module.
 * 
 * @access public
 * @return void
 */
function loadModuleRelated()
{
    moduleID  = $('#module').val();
    productID = $('#product').val();
    setStories(moduleID, productID);
}

/**
 * Unlink related bug.
 *
 * @param  int $bugID
 * @param  int $bug2Unlink
 * @access public
 * @return void
 */
function unlinkBug(bugID, bug2Unlink)
{
    link = createLink('bug', 'unlinkBug', 'bugID=' + bugID + '&bug2Unlink=' + bug2Unlink);
    $.get(link, function(data)
    {
        if(data == 'success') $('#linkBugBox').load(createLink('bug', 'ajaxGetLinkBugs', 'bugID=' + bugID));
    });
}

/**
 * Load linkBugs.
 *
 * @param  int    $bugID
 * @access public
 * @return void
 */
function loadLinkBugs(bugID)
{
    bugLink = createLink('bug', 'ajaxGetLinkBugs', 'bugID=' + bugID);
    $('#linkBugBox').load(bugLink);
}
