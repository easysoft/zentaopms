function loadExecutionBuilds(event)
{
    const $target = $(event.target);
    if($target.closest('.form-batch-ditto').data('ditto') == 'on') return false;
    const $currentRow = $target.closest('tr');
    const executionID = $target.val();
    const projectID   = $currentRow.find('.form-batch-control[data-name="project"] .picker').zui('picker').$.value || '0';
    const branch      = $currentRow.find('.form-batch-input[data-name="branch"]').val() || '0'; // Branch ID (from same row).
    const productID = $('[name="product"]').val();

    if(executionID != 0)
    {
        var link = $.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + "&varName=openedBuilds&build=&branch=" + branch);
    }
    else if(projectID != 0)
    {
        var link = $.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + "&varName=openedBuilds&build=&branch=" + branch);
    }
    else
    {
        var link = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + "&varName=openedBuilds&build=&branch=" + branch);
    }

    setOpenedBuilds(link, $currentRow);
}

function setOpenedBuilds(link, $currentRow)
{
    $.getJSON(link, function(builds)
    {
        if(!builds) return;

        let $row = $currentRow;
        while($row.length)
        {
            const $build = $row.find('[data-name="openedBuild"] .picker').zui('picker');
            $build.render({items: builds});
            $build.$.setValue($build.$.value.split(','));

            $row = $row.next('tr');

            if(!$row.find('td[data-name="openedBuild"][data-ditto="on"]').length || !$row.find('td[data-name="branch"][data-ditto="on"]').length) break;
        }
    });
}

function setLane(event)
{
    const $target = $(event.target);
    if($target.closest('.form-batch-ditto').data('ditto') == 'on') return false;
    const $currentRow = $target.closest('tr');
    const regionID    = $target.val();

    laneLink = $.createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=bug&field&pageType=batch');
    $.getJSON(laneLink, function(lanes)
    {
        if(!lanes) return;

        let $row = $currentRow;
        while($row.length)
        {
            const $lane = $row.find('[data-name="laneID"] .picker').zui('picker');
            $lane.render({items: lanes});
            $lane.$.setValue($lane.options.defaultValue);

            $row = $row.next('tr');

            if(!$row.find('td[data-name="laneID"][data-ditto="on"]').length || !$row.find('td[data-name="branch"][data-ditto="on"]').length) break;
        }
    });
}

/**
 * Load product executions on project change.
 *
 * @param {Event} event Project form control change event.
 */
function loadProductExecutionsByProject(event)
{
    const $target = $(event.target); // Project form control element.
    if($target.closest('.form-batch-ditto').data('ditto') == 'on') return false;
    const $currentRow = $target.closest('tr');        // Currenr batch form row element.
    const projectID   = $target.val();                // Project ID.
    const productID   = $('[name="product"]').val();  // Product ID (from hidden form control).
    const branch      = $currentRow.find('.form-batch-control[data-name="branch"] input').val() || '0'; // Branch ID (from same row).

    /* Get executions with ajax request. */
    $.getJSON($.createLink('product', 'ajaxGetExecutionsByProject', 'productID=' + productID + '&projectID=' + projectID + '&branch=' + branch), function(data)
    {
        /* Return if server do not return any data. */
        if(!data || !data.executions) return;

        /* Update executions form control in current row and all follow ditto rows. */
        let $row = $currentRow;  // Start loop from current row.
        while($row.length)
        {
            /* Find execution form control and clear old options. */
            const $execution = $row.find('[data-name="execution"] .picker').zui('picker');
            $execution.render({items: data.executions});
            $execution.$.setValue($execution.$.value);

            /* Set next row to current row and continue the loop. */
            $row = $row.next('tr');

            /* Break loop if next row is not ditto row. */
            if(!$row.find('td[data-name="execution"][data-ditto="on"]').length) break;
        }
    });

    const executionID = $currentRow.find('.form-batch-control[data-name="execution"] .picker').zui('picker').$.value || '0';
    if(executionID != 0)
    {
        var link = $.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + "&varName=openedBuilds&build=&branch=" + branch);
    }
    else if(projectID != 0)
    {
        var link = $.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + "&varName=openedBuilds&build=&branch=" + branch);
    }
    else
    {
        var link = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + "&varName=openedBuilds&build=&branch=" + branch);
    }

    setOpenedBuilds(link, $currentRow);
}
