$().ready(function()
{
    var oldExecutionID = $('#execution').val();
    $(document).on('change', '#product, #branch', function()
    {
        if(executionID)
        {
            loadExecutions(oldExecutionID);
        }
        else
        {
            var productID = $('#product').val();
            var branch    = $('#branch').val();
            $.get(createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=builds&build=&branch=' + branch + '&index=&needCreate=&type=noempty,notrunk,separate,singled&extra=multiple'), function(data)
            {
                if(data) $('#buildBox').html(data);
                $('#builds').attr('data-placeholder', multipleSelect).chosen();
            });
        }
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
