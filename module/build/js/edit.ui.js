function changeProduct()
{
    let oldExecutionID = $('input[name=execution]').val();
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
                    $buildsPicker.$.setValue('');
                    $('#builds').attr('data-placeholder', multipleSelect);
                }
            });
    }

    $.get($.createLink('product', 'ajaxGetProductById', 'productID=' + productID), function(data)
        {
            $('[name^=branch]').closest('.form-group').find('.form-label').html(data.branchName);
        }, 'json');

    $('[name^=branch]').zui('picker').$.setValue('');
    loadBranches();
}

function changeBranches()
{
    let newBranch = $('[name^=branch]').val() ? $('[name^=branch]').val().toString() : '';
    $.get($.createLink('build', 'ajaxGetBranch', 'buildID=' + buildID + '&newBranch=' + newBranch), function(unlinkBranch)
        {
            if(unlinkBranch != '')
            {
                let result = confirm(unlinkBranch) ? true : false;
                if(!result)
                {
                    $('[name^=branch]').zui('picker').$.setValue(build.branch.split(','));
                }
            }
        });

}

function loadExecutions(oldExecutionID)
{
    let productID = $('input[name=product]').val();
    let branchID  = $('[name^=branch]').length > 0 ? $('[name^=branch]').val() : 0;
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
