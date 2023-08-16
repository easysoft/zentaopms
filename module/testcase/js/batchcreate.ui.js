function onModuleChanged(event)
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const moduleID    = $target.val();

    loadStories(productID, moduleID, 0, $currentRow);
    loadScenes(productID, moduleID, $currentRow);
}

function loadScenes(productID, moduleID, $currentRow)
{
    let branchID = $currentRow.find('.form-batch-input[data-name="branch"]').val();
    let sceneLink = $.createLink('testcase', 'ajaxGetScenesForBC', 'productID=' + productID + '&branch=' + branchID + '&moduleID=' + moduleID + '&stype=2&storyID=0&onlyOption=false&status=noclosed&limit=50&type=full&hasParent=1');
    $.getJSON(sceneLink, function(scenes)
    {
        if(!scenes) return;

        let $row = $currentRow;
        while($row.length)
        {
            const $scene = $row.find('[data-name="scene"] .picker').zui('picker');
            $scene.render({items: stories});
            $scene.$.setValue($scene.$.value);

            $row = $row.next('tr');

            if(!$row.find('td[data-name="scene"][data-ditto="on"]').length || !$row.find('td[data-name="module"][data-ditto="on"]').length) break;
        }
    });
}
