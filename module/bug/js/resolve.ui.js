function setDuplicate()
{
    var resolution = $(this).val();
    if(resolution == 'duplicate')
    {
        $.ajaxSettings.async = false;
        $.getJSON($.createLink('bug', 'ajaxGetProductBugs', 'projectID=' + productID + '&bugID=' + bugID),function(bugs)
        {
            if(!bugs) return;

            const $duplicateBug = $('#duplicateBug').empty();
            $.each(bugs, function(index, bug)
            {
                $duplicateBug.append('<option value="' + bug.value + '">' + bug.text + '</option>');
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
