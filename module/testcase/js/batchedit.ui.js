window.handleRenderRow = function($row, index, row)
{
    /* Set the branches for the row. */
    if(branchProduct)
    {
        $row.find('.form-batch-control[data-name="branch"] .picker-box').on('inited', function(e, info)
        {
            const branchLink = $.createLink('branch', 'ajaxGetBranches', 'productID=' + row.product);
            $.getJSON(branchLink, function(branches)
            {
                if(!branches.length)
                {
                    info[0].render({disabled: 'disabled'});
                }
                else
                {
                    let $branch = info[0];
                    $branch.render({items: branches});
                    $branch.$.setValue(row.branch);
                }
            });
        });
    }
    /* Set the modules for the row. */
    $row.find('.form-batch-control[data-name="module"] .picker-box').on('inited', function(e, info)
    {
        let $module = info[0];
        $module.render({items: modulePairs[row.id]});
        $module.$.setValue(row.module);
    });

    $row.find('.form-batch-control[data-name="story"] .picker-box').on('inited', function(e, info)
    {
        const storyLink = $.createLink('story', 'ajaxGetProductStories', 'productID=' + row.product + '&branch=' + row.branch + '&moduleID=' + row.module + '&storyID=' + row.story + '&onlyOption=false&status=active&limit=0&type=&hasParent=0');
        $.getJSON(storyLink, function(stories)
        {
            let $story = info[0];
            $story.render({items: stories});
            $story.$.setValue(row.story);
        });
    });

    /* Set the scenes for the row. */
    $row.find('.form-batch-control[data-name="scene"] .picker-box').on('inited', function(e, info)
    {
        let $scene = info[0];
        $scene.render({items: scenePairs[row.id]});
        $scene.$.setValue(row.scene);
    });
}
