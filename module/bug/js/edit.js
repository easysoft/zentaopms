$(function()
{
    loadModuleRelated();

    resolution = $('#resolution').val();
    if(resolution == 'fixed')
    {
        $('#resolvedBuildBox').change(function()
        {
            if($('#resolvedBuild').val() != oldResolvedBuild)
            {
                confirmResult = confirm(confirmUnlinkBuild);
                if(!confirmResult)
                {
                    $('#resolvedBuild').val(oldResolvedBuild);
                    $('#resolvedBuild').trigger("chosen:updated");
                    $('#resolvedBuild').chosen();
                }
            }
        });
    }
});

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
    executionID = $('#execution').val();
    storyID   = $('#story').val();
    taskID    = $('#task').val();
    if(module == 'story')
    {
        link = createLink('search', 'select', 'productID=' + productID + '&executionID=' + executionID + '&module=story&moduleID=' + storyID);
        $('#storyListIdBox a').attr("href", link);
    }
    else
    {
        link = createLink('search', 'select', 'productID=' + productID + '&executionID=' + executionID + '&module=task&moduleID=' + taskID);
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
    storyID   = $('#story').val();
    setStories(moduleID, productID, storyID);
}
