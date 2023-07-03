$(function()
{
    loadModuleRelated();

    initPicker = function($element)
    {
        var picker = $element.data('zui.picker');
        var originOptions = picker.options;

        if(picker) picker.destroy();

        var options = $.extend({}, originOptions, {searchDelay: 1000});
        $element.picker(options);
    };

    initPicker($('#case'));
    initPicker($('#duplicateBug'));
    renderBuilds();
    renderTestTasks();
    renderStories();

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
 * Get all builds and set confirm string.
 *
 * @access public
 * @return void
 */
function renderBuilds()
{
    $.get(createLink('bug', 'ajaxGetAllBuilds', 'bugID=' + bugID), function(data)
    {
        var openedBuilds   = data.openedBuilds;
        $('#openedBuild').data('zui.picker').destroy();
        $('#openedBuild').picker({list: openedBuilds});
        $('#openedBuild').data('zui.picker').setValue('' + oldOpenedBuild);
        var resolvedBuilds = data.resolvedBuilds;
        $('#resolvedBuild').data('zui.picker').destroy();
        $('#resolvedBuild').picker({list: resolvedBuilds, allowSingleDeselect: 'true'});
        $('#resolvedBuild').data('zui.picker').setValue(oldResolvedBuild);

        resolution = $('#resolution').val();
        if(resolution == 'fixed')
        {
            $('#resolvedBuildBox').change(function()
            {
                if($('#resolvedBuild').val() != oldResolvedBuild)
                {
                    confirmUnlinkBuild = confirmUnlinkBuild.replace('%s', data.resolvedBuildName);
                    confirmResult = confirm(confirmUnlinkBuild);
                    if(!confirmResult)
                    {
                        var resolvedBuildPicker = $('#resolvedBuild').data('zui.picker');
                        resolvedBuildPicker.setValue(oldResolvedBuild);
                    }
                }
            });
        }
    }, 'json');
}

/**
 * render testTasks.
 *
 * @access public
 * @return void
 */
function renderTestTasks()
{
    $.get(createLink('bug', 'ajaxGetTestTasks', 'bugID=' + bugID), function(data)
    {
        $('#testtask').data('zui.picker').destroy();
        $('#testtask').picker({list: data, allowSingleDeselect: 'true'});
        $('#testtask').data('zui.picker').setValue(bugTestTask);
    }, 'json');
}

/**
 * render stories.
 *
 * @access public
 * @return void
 */
function renderStories()
{
    $.get(createLink('bug', 'ajaxGetStories', 'bugID=' + bugID), function(data)
    {
        $('#story').data('zui.picker').destroy();
        $('#story').picker({list: data, allowSingleDeselect: 'true'});
        $('#story').data('zui.picker').setValue(oldStoryID);
    }, 'json');
}

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
