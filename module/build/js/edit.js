$().ready(function()
{
    var oldExecutionID = $('#execution').val();
    $(document).on('change', '#product, #branch', function()
    {
        var productID = $('#product').val();

        if(executionID)
        {
            loadExecutions(oldExecutionID);
        }
        else
        {
            $.get(createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=builds&build=&branch=all&index=&needCreate=&type=noempty,notrunk,separate,singled&extra=multiple'), function(data)
            {
                if(data) $('#buildBox').html(data);
                $('#builds').attr('data-placeholder', multipleSelect).chosen();
            });
        }

        var newBranch = $('#branch').val() ? $('#branch').val().toString() : '';
        $.get(createLink('build', 'ajaxGetBranch', 'buildID=' + buildID + '&newBranch=' + newBranch), function(unlinkBranch)
        {
            if(unlinkBranch != '')
            {
                var result = confirm(unlinkBranch) ? true : false;
                if(!result)
                {
                    $('#branch').val(oldBranch[buildID].split(','));
                    $('#branch').trigger("chosen:updated");
                }
            }
        });

        $.get(createLink('product', 'ajaxGetProductById', 'produtID=' + productID), function(data)
        {
            $('#branchBox').closest('tr').find('th').text(data.branchName);
        }, 'json');
    });
});

function loadExecutions(oldExecutionID)
{
    var productID = $('#product').val();
    var branchID  = $('#branch').length > 0 ? $('#branch').val() : 0;
    $('#executionsBox').load(createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=0&branch=' + branchID + '&number=&executionID=' + oldExecutionID), function()
    {
        $('#executionsBox #execution').chosen().removeAttr('onchange');
    });
}
