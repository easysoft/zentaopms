function setDuplicate(resolution)
{
    if(resolution == 'duplicate')
    {
        $.ajaxSettings.async = false;
        $.get(createLink('bug', 'ajaxGetProductBugs', 'projectID=' + productID + '&bugID=' + bugID),function(data)
        {
            $('#duplicateBug').replaceWith(data);
            $('#pk_duplicateBug-search').parent().parent().remove();
            $('#duplicateBug').picker(
            {
                disableEmptySearch : true,
                dropWidth : 'auto'
            });
        });
        $.ajaxSettings.async = true;
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
            $('#newBuildExecutionBox').removeClass('hidden');
        }
        else
        {
            $('#resolvedBuildBox').removeClass('hidden');
            $('#newBuildBox').addClass('hidden');
            $('#newBuildExecutionBox').addClass('hidden');
        }
    })

    $('#duplicateBug').picker(
    {
        disableEmptySearch : true,
        dropWidth : 'auto'
    });
})
