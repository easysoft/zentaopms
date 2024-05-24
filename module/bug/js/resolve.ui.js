$(document).off('change', "[name^='resolution']").on('change', "[name^='resolution']", function()
{
    if(requiredFields.indexOf('resolvedBuild') == -1)
    {
        var resolvedBuildGroup = $('#resolvedBuildBox').find('label.form-label');
        if($(this).val() == 'fixed')
        {
            resolvedBuildGroup.addClass('required');
        }
        else
        {
            resolvedBuildGroup.removeClass('required');
        }
    }
});

$(function()
{
    $('#createBuild').on('change', function()
    {
        if($(this).prop('checked'))
        {
            $('#resolvedBuildBox').addClass('hidden');
            $('#newBuildBox').removeClass('hidden');
            $('#newBuildExecutionBox').removeClass('hidden');
        }
        else
        {
            $('#resolvedBuildBox').removeClass('hidden');
            $('#newBuildBox').addClass('hidden');
            $('#newBuildExecutionBox').addClass('hidden');
        }
    })
})
