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

function loadProductExecutionsByProject(event)
{
    var projectID = $(event.target).val();
    var num       = $(event.target).closest('tr').attr('data-index');

    const productID = $('[name="product"]').val();

    var branch = $('#branches_' + num).val();
    if(typeof(branch) == 'undefined') branch = 0;

    var link = $.createLink('product', 'ajaxGetExecutionsByProject', 'productID=' + productID + '&projectID=' + projectID + '&branch=' + branch + '&number=' + num);
    $.get(link, function(executions)
    {
        if(!executions) executions = '<select id="executions_' + num + '" name="executions[' + num + ']" class="form-control"></select>';
        $('#executions' + num).replaceWith(executions);
    });
}
