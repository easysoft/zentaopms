window.loadProductStories = function(productID)
{
    let branch   = $('[name=branch]').val();
    let moduleID = $('[name=module]').val();
    let storyID  = $('[name=story]').val();

    if(typeof(branch)   == 'undefined') branch   = 0;
    if(typeof(moduleID) == 'undefined') moduleID = 0;
    if(typeof(storyID)  == 'undefined') storyID  = 0;

    const link = $.createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&storyID=' + storyID + '&onlyOption=false&status=noclosed&limit=0&type=full&hasParent=1&objectID=' + objectID);
    $.getJSON(link, function(data)
    {
        let $storyPicker = $('[name=story]').zui('picker');
        $storyPicker.render({items: data});
        $storyPicker.$.setValue(storyID);
    })
}

window.loadProductBranches = function(productID)
{
    var param     = config.currentMethod == 'create' ? 'active' : 'all';
    var oldBranch = config.currentMethod == 'edit' ? caseBranch : 0;
    var param     = 'productID=' + productID + '&oldBranch=' + oldBranch + '&param=' + param;
    if(typeof(tab) != 'undefined' && (tab == 'execution' || tab == 'project')) param += '&projectID=' + objectID;

    $.get($.createLink('branch', 'ajaxGetBranches', param), function(data)
    {
        $('#branch').toggleClass('hidden', !data);
        data = JSON.parse(data);

        let branch        = $('[name=branch]').val();
        let $branchPicker = $('[name=branch]').zui('picker');
        $branchPicker.render({items: data});
        $branchPicker.$.setValue(branch);
        $('#branch').removeClass('hidden');
    })
}

window.loadProductModules = function(productID)
{
    let branch = $('[name=branch]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const getModuleLink = $.createLink('testcase', 'ajaxGetOptionMenu', 'rootID=' + productID + '&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=');
    $.getJSON(getModuleLink, function(data)
    {
        let oldModule     = $('[name=module]').val();
        let $modulePicker = $('[name=module]').zui('picker');
        $modulePicker.render({items: data});
        $modulePicker.$.setValue(oldModule);

        $('#module').next('.input-group-addon').toggleClass('hidden', data.length > 1);
    })
}

window.loadScenes = function(productID, sceneName = 'scene')
{
    let branchID = $('[name=branch]').val();
    let moduleID = $('[name=module]').val();
    if(typeof(branchID) == 'undefined') branchID = 0;
    if(typeof(moduleID) == 'undefined') moduleID = 0;
    if(typeof(sceneID)  == 'undefined') sceneID  = 0;

    const link = $.createLink('testcase', 'ajaxGetScenes', 'productID=' + productID + '&branch=' + branchID + '&moduleID=' + moduleID + '&sceneID=' + sceneID);
    $.getJSON(link, function(scenes)
    {
        const $picker = $('[name=' + sceneName + ']').zui('picker');
        $picker.render({items: scenes});
        $picker.$.setValue(sceneID);
    });
}

/**
 * Set modules.
 *
 * @param  int     $branchID
 * @param  int     $productID
 * @param  int     $num
 * @access public
 * @return void
 */
window.onBranchChangedForBatch = function(event)
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const branchID    = $target.val();
    const moduleID    = $currentRow.find('.form-batch-control[data-name="module"] .pick-value').val();

    $.getJSON($.createLink('tree', 'ajaxGetModules', 'productID=' + productID + '&viewType=case&branch=' + branchID + '&number=0&currentModuleID=' + moduleID), function(data)
    {
        if(!data || !data.modules) return;

        let $row = $currentRow;
        while($row.length)
        {
            const $module = $row.find('.form-batch-control[data-name="module"] .picker').zui('picker');
            $module.render({items: data.modules});
            $module.$.setValue(data.currentModuleID);

            $row = $row.next('tr');
            if(!$row.find('td[data-name="module"][data-ditto="on"]').length || !$row.find('td[data-name="branch"][data-ditto="on"]').length) break;
        }
    });

    loadScenesForBatch(productID, moduleID, $currentRow);
    loadStoriesForBatch(productID, moduleID, 0, $currentRow);
}

window.onModuleChangedForBatch = function(event)
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const moduleID    = $target.val();

    loadScenesForBatch(productID, moduleID, $currentRow);
    loadStoriesForBatch(productID, moduleID, 0, $currentRow);
}

window.loadScenesForBatch = function(productID, moduleID, $currentRow)
{
    let branchID = $currentRow.find('.form-batch-control[data-name="branch"] .pick-value').val();
    if(!branchID) branchID = 0;

    let sceneLink = $.createLink('testcase', 'ajaxGetScenes', 'productID=' + productID + '&branch=' + branchID + '&moduleID=' + moduleID);
    $.getJSON(sceneLink, function(scenes)
    {
        let $row = $currentRow;
        while($row.length)
        {
            const $scene = $row.find('.form-batch-control[data-name="scene"] .picker').zui('picker');
            $scene.render({items: scenes});
            $scene.$.setValue($scene.$.value);

            $row = $row.next('tr');
            if(!$row.find('td[data-name="module"][data-ditto="on"]').length) break;
        }
    });
}

/**
 * Set stories.
 *
 * @param  int     productID
 * @param  int     moduleID
 * @param  int     num
 * @access public
 * @return void
 */
window.loadStoriesForBatch = function(productID, moduleID, num, $currentRow = null)
{
    const branchID = $currentRow.find('.form-batch-control[data-name="branch"]').length ? $currentRow.find('.form-batch-control[data-name="branch"] .pick-value').val() : 0;
    if(!branchID) branchID = 0;

    var storyLink  = $.createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branchID + '&moduleID=' + moduleID + '&storyID=0&onlyOption=false&status=noclosed&limit=0&type=full&hasParent=1&objectID=0&number=' + num);
    $.getJSON(storyLink, function(stories)
    {
        if(!stories) return;

        let $row = $currentRow;
        while($row.length)
        {
            const $story = $row.find('.form-batch-control[data-name="story"] .picker').zui('picker');
            $story.render({items: stories});
            $story.$.setValue($story.$.value);

            $row = $row.next('tr');

            if(($row.find('td[data-name="branch"]').length && !$row.find('td[data-name="branch"][data-ditto="on"]').length) || !$row.find('td[data-name="module"][data-ditto="on"]').length) break;
        }
    });
}
