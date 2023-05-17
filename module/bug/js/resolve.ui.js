function setDuplicate()
{
    var resolution = $(this).val();
    if(resolution == 'duplicate')
    {
        $.ajaxSettings.async = false;
        $.get($.createLink('bug', 'ajaxGetProductBugs', 'projectID=' + productID + '&bugID=' + bugID),function(data)
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
