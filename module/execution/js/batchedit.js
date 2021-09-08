$(function()
{
    $('[id^="projects"]').change(function()
    {
        var executionID = $(this).attr('id').replace('projects', '');
        var $td = $(this).closest('td');
        if($td.find('[id^="syncStories"]').length == 0)
        {
            $td.append("<input type='hidden' id='syncStories" + executionID + "' name='syncStories[" + executionID + "]' value='no' />");
        }
        $("#syncStories" + executionID).val(confirm(confirmSyncStories) ? 'yes' : 'no');
    })
});
