window.handleRenderRow = function($row, index, row)
{
    /* Set the branches for the row. */
    if(branchProduct)
    {
        $row.find('.form-batch-control[data-name="branch"] .picker-box').on('inited', function(e, info)
        {
            let $branch = info[0];
            $branch.$.setValue(row.branch);
        });
    }
    /* Set the modules for the row. */
    $row.find('.form-batch-control[data-name="module"] .picker-box').on('inited', function(e, info)
    {
        let $module = info[0];
        $module.render({items: modulePairs[row.id]});
        $module.$.setValue(row.module);
    });

    /* Set the scenes for the row. */
    $row.find('.form-batch-control[data-name="scene"] .picker-box').on('inited', function(e, info)
    {
        let $scene = info[0];
        $scene.render({items: scenePairs[row.id]});
        $scene.$.setValue(row.scene);
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
    let branchID = $currentRow.find('.form-batch-control[data-name="branch"]').length ? $currentRow.find('.form-batch-control[data-name="branch"] .pick-value').val() : 0;
    if(!branchID) branchID = 0;

    const storyLink  = $.createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branchID + '&moduleID=' + moduleID + '&storyID=0&onlyOption=false&status=noclosed&limit=0&type=full&hasParent=1&objectID=0&number=' + num);
    $.getJSON(storyLink, function(stories)
    {
        if(!stories) return;

        /* Append case's stories. */
        var storyIdList = stories.map(function(story) { return story.value; });
        let mergeStories = caseStories.filter(function(story){return storyIdList.some(function(id){return id != story.value;});});

        stories.append(mergeStories);

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
