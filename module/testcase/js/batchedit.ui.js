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
    });
}
