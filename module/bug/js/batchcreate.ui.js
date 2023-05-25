function loadExecutionBuilds(event)
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const executionID = $target.val();
    const branch      = $currentRow.find('.form-batch-input[data-name="branch"]').val() || '0'; // Branch ID (from same row).
    const productID = $('[name="product"]').val();

    if(executionID != 0)
    {
        var link = $.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + "&varName=openedBuilds&build=&branch=" + branch);
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
        builds = JSON.parse(builds);

        let $row = $currentRow;
        while($row.length)
        {
            const $build = $row.find('.form-batch-input[data-name="openedBuild"]').empty();

            $.each(builds, function(value, text)
            {
                $build.append('<option value="' + value + '">' + text + '</option>');
            });

            $row = $row.next('tr');

            if(!$row.find('td[data-name="openedBuild"][data-ditto="on"]').length || !$row.find('td[data-name="branch"][data-ditto="on"]').length) break;
        }
    });
}

function setLane(event)
{
    var regionID = $(event.target).val();
    var num      = $(event.target).closest('tr').attr('data-index');

    laneLink = $.createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=bug&field=lanes&i=' + num);
    $.getJSON(laneLink, function(lanes)
    {
        if(!lanes) return;
        lanes = JSON.parse(builds);

        let $row = $currentRow;
        while($row.length)
        {
            const $lane = $row.find('.form-batch-input[data-name="laneID"]').empty();

            $.each(lanes, function(value, text)
            {
                $lane.append('<option value="' + value + '">' + text + '</option>');
            });

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
    const $target     = $(event.target);              // Project form control element.
    const $currentRow = $target.closest('tr');        // Currenr batch form row element.
    const projectID   = $target.val();                // Project ID.
    const productID   = $('[name="product"]').val();  // Product ID (from hidden form control).
    const branch      = $currentRow.find('.form-batch-input[data-name="branch"]').val() || '0'; // Branch ID (from same row).

    /* Get executions with ajax request. */
    $.getJSON($.createLink('product', 'ajaxGetExecutionsByProject', 'productID=' + productID + '&projectID=' + projectID + '&branch=' + branch), function(data)
    {
        /* Return if server not return any data. */
        if(!data || !data.executions) return;

        /* Update executions form control in current row and all follow ditto rows. */
        let $row = $currentRow;  // Start loop from current row.
        while($row.length)
        {
            /* Find execution form control and clear old options. */
            const $execution = $row.find('.form-batch-input[data-name="execution"]').empty();

            /* Set execution control disabled if there is no multiple execution ID. */
            if(data.noMultipleExecutionID)
            {
                $execution.attr('disabled', 'disabled').append('<option selected value="' + data.noMultipleExecutionID + '"></option>');
                continue;
            }

            /* Append all new executions options. */
            $.each(data.executions, function(value, text)
            {
                $execution.append('<option value="' + value + '">' + text + '</option>');
            });

            /* Set next row to current row and continue the loop. */
            $row = $row.next('tr');

            /* Break loop if next row is not ditto row. */
            if(!$row.find('td[data-name="execution"][data-ditto="on"]').length) break;
        }
    });
}
