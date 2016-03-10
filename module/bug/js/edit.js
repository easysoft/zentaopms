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
 * Delete a linked bug.
 *
 * @param  int $bugID
 * @param  int $deleteBug
 * @access public
 * @return void
 */
function deleteLinkedBug(bugID, deleteBug)
{
    deleteLink = createLink('bug', 'ajaxDeleteLinkedBug', 'bugID=' + bugID + '&deleteBug=' + deleteBug);
    $('#linkBugBox').load(deleteLink);
}

/**
 * Load linked bugs.
 *
 * @param  int    $bugID
 * @param  string $linkedBugs
 * @access public
 * @return void
 */
function loadLinkedBugs(bugID, linkedBugs)
{
    bugLink = createLink('bug', 'ajaxGetLinkedBugs', 'bugID=' + bugID + '&linkedBugs=' + linkedBugs);
    $('#linkBugBox').load(bugLink);
}
