function setDuplicate()
{
    var resolution = $(event.target).val();
    if(resolution == 'duplicate')
    {
        $.getJSON($.createLink('bug', 'ajaxGetProductBugs', 'projectID=' + productID + '&bugID=' + bugID),function(bugs)
        {
            if(!bugs) return;

            $('[name="duplicateBug"]').zui('picker').render({items: bugs});
        });

        $('#duplicateBugBox').removeClass('hidden');
    }
    else
    {
        $('#duplicateBugBox').addClass('hidden');
    }
}

$(function()
{
    var requiredFields = config.requiredFields;
    if(requiredFields.indexOf('resolvedBuild') == -1)
    {
        var resolvedBuildGroup = $('#resolvedBuild').closest('.form-group');
        $('#resolution').on('change', function()
        {
            if($(this).val() == 'fixed')
            {
                resolvedBuildGroup.addClass('required');
            }
            else
            {
                resolvedBuildGroup.removeClass('required');
            }
        });
    }

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
