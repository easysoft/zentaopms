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
                    var resolvedBuildPicker = $('#resolvedBuild').data('zui.picker');
                    resolvedBuildPicker.setValue(oldResolvedBuild);
                }
            }
        });
    }

    $('#duplicateBug').picker(
    {
        disableEmptySearch : true,
        dropWidth : 'auto',
        maxAutoDropWidth : document.body.scrollWidth + document.getElementById('resolution').offsetWidth - document.getElementById('resolution').getBoundingClientRect().right
    });

    $('#linkBugsLink').click(function()
    {
        var bugIdList = '';
        $('#linkBugsBox input').each(function()
        {
            bugIdList += $(this).val() + ',';
        });

        var link = createLink('bug', 'linkBugs', 'bugID=' + bugID + '&browseType=&excludeBugs=' + bugIdList, '', true);

        var modalTrigger = new $.zui.ModalTrigger({type: 'iframe', width: '95%', url: link});
        modalTrigger.show();
    });

    var $pkResolvedBuild = $('#pk_resolvedBuild-search');
    $pkResolvedBuild.closest('.picker').css('width', $pkResolvedBuild.closest('td').width() - $pkResolvedBuild.closest('td').find('.input-group-btn').width());
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
