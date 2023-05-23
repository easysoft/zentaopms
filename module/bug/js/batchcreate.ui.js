function loadExecutionBuilds(event)
{
    var executionID = $(event.target).val();
    var index       = $(event.target).closest('tr').attr('data-index');
    var branch      = $('#branches_' + index).val() ? $('#branches_' + index).val() : 0;

    const productID = $('[name="product"]').val();

    if(executionID != 0)
    {
        var link = $.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + "&varName=openedBuilds&build=&branch=" + branch + "&index=" + index);
    }
    else
    {
        var link = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + "&varName=openedBuilds&build=&branch=" + branch + "&index=" + index);
    }

    setOpenedBuilds(link, index);
}

function setOpenedBuilds(link, index)
{
    $.get(link, function(builds)
    {
        var row = $('#openedBuilds_' + index).closest('tbody').find('tr').length;
        do
        {
            var selected = $('#openedBuilds_' + index).val();
            $buildBox = $('#openedBuilds_' + index).parent();
            $buildBox.html(builds);
            $buildBox.find('select').val(selected);
            $buildBox.find('select').removeClass('select-3');
            $buildBox.find('select').addClass('select-1');
            $buildBox.find('select').attr('name','openedBuilds[' + index + '][]');
            $buildBox.find('select').attr('id','openedBuilds_' + index);

            index++;
            if($('#branches_' + index).closest('.form-batch-control').attr('data-ditto') != 'on') break;
        }while(index < row)
    });
}

function setLane(event)
{
    var regionID = $(event.target).val();
    var num      = $(event.target).closest('tr').attr('data-index');

    laneLink = $.createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=bug&field=lanes&i=' + num);
    $.get(laneLink, function(lanes)
    {
        if(!lanes) lanes = '<select id="lanes_' + num + '" name="lanes[' + num + ']" class="form-control"></select>';
        $('#lanes_' + num).replaceWith(lanes);
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
