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
    let sceneLink = $.createLink('testcase', 'ajaxGetScenes', 'productID=' + productID + '&branch=' + branchID + '&moduleID=' + moduleID);
    $.getJSON(sceneLink, function(scenes)
    {
        if(!scenes) return;

        const items = JSON.parse(scenes);

        let $row = $currentRow;
        while($row.length)
        {
            const $picker = $row.find('[data-name="scene"] .picker').zui('picker');
            $picker.render({items});
            $picker.$.setValue($scene.$.value);

            $row = $row.next('tr');
            if(!$row.find('td[data-name="scene"][data-ditto="on"]').length || !$row.find('td[data-name="module"][data-ditto="on"]').length) break;
        }
    });
}
