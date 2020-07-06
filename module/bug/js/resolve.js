function setDuplicate(resolution)
{
    if(resolution == 'duplicate')
    {
        $('#duplicateBugBox').show();
    }
    else
    {
        $('#duplicateBugBox').hide();
    }
}

$(function()
{
    /* Fix bug #3227. */
    var requiredFields = config.requiredFields;
    if(requiredFields.indexOf('resolvedBuild') == -1)
    {
        resolvedBuildTd  = $('#resolvedBuild').closest('td');
        $('#resolution').change(function()
        {
            if($(this).val() == 'fixed')
            {
                resolvedBuildTd.addClass('required');
            }
            else
            {
                resolvedBuildTd.removeClass('required');
            }
        });
    }

    $('#createBuild').change(function()
    {
        if($(this).prop('checked'))
        {
            $('#resolvedBuildBox').addClass('hidden');
            $('#newBuildBox').removeClass('hidden');
            $('#newBuildProjectBox').removeClass('hidden');
        }
        else
        {
            $('#resolvedBuildBox').removeClass('hidden');
            $('#newBuildBox').addClass('hidden');
            $('#newBuildProjectBox').addClass('hidden');
        }
    })
})
