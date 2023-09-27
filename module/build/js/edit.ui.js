$().ready(function()
{
    let oldExecutionID = $('input[name=execution]').val();
    $(document).on('change', '#product, #branch', function()
    {
        let productID = $('input[name=product]').val();
        if(executionID)
        {
            loadExecutions(oldExecutionID);
        }
        else
        {
            $.get($.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&letName=builds&build=&branch=all&needCreate=&type=noempty,notrunk,separate,singled'), function(data)
            {
                if(data)
                {
                    data = JSON.parse(data);
                    const $buildsPicker = $('select[name^=builds]').zui('picker');
                    $buildsPicker.render({items: data, multiple: true});
                    $('#builds').attr('data-placeholder', multipleSelect);
                }
            });
        }

        let newBranch = $('input[name=branch]').val() ? $('input[name=branch]').val().toString() : '';
        $.get($.createLink('build', 'ajaxGetBranch', 'buildID=' + buildID + '&newBranch=' + newBranch), function(unlinkBranch)
        {
            if(unlinkBranch != '')
            {
                let result = confirm(unlinkBranch) ? true : false;
                if(!result)
                {
                    $('input[name^=branch]').zui('picker').$.setValue(build.branch.split(','));
                }
            }
        });

        $.get($.createLink('product', 'ajaxGetProductById', 'produtID=' + productID), function(data)
        {
            $('#branch').prev('.form-label').html(data.branchName);
        }, 'json');
    });
});

function loadExecutions(oldExecutionID)
{
    let productID = $('input[name=product]').val();
    let branchID  = $('#branch').length > 0 ? $('input[name=branch]').val() : 0;
    $.get($.createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=0&branch=' + branchID + '&number=&executionID=' + oldExecutionID), function(data)
    {
        if(data)
        {
            const $executionPicker = $('input[name=execution]').zui('picker');
            data = JSON.parse(data);
            $executionPicker.render({items: data});
        }
    });
}
