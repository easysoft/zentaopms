$().ready(function()
{
    let oldExecutionID = $('#execution').val();
    $(document).on('change', '#product, #branch', function()
    {
        let productID = $('#product').val();
        if(executionID)
        {
            loadExecutions(oldExecutionID);
        }
        else
        {
            $.get($.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=builds&build=&branch=all&index=&needCreate=&type=noempty,notrunk,separate,singled&extra=multiple'), function(data)
            {
                $('#builds').replaceWith(data);
                $('#builds').attr('data-placeholder', multipleSelect);
            });
        }

        let newBranch = $('#branch').val() ? $('#branch').val().toString() : '';
        $.get($.createLink('build', 'ajaxGetBranch', 'buildID=' + buildID + '&newBranch=' + newBranch), function(unlinkBranch)
        {
            if(unlinkBranch != '')
            {
                let result = confirm(unlinkBranch) ? true : false;
                if(!result)
                {
                    $('#branch').val(oldBranch[buildID].split(','));
                }
            }
        });

        $.get($.createLink('product', 'ajaxGetProductById', 'produtID=' + productID), function(data)
        {
            $('#branch').closest('.form-label').text(data.branchName);
        }, 'json');
    });
});

function loadExecutions(oldExecutionID)
{
    let productID = $('#product').val();
    let branchID  = $('#branch').length > 0 ? $('#branch').val() : 0;
    $('#execution').load($.createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=0&branch=' + branchID + '&number=&executionID=' + oldExecutionID), function()
    {
        $('#execution').removeAttr('onchange');
    });
}
