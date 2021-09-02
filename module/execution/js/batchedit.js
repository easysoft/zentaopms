$(function()
{
    $('[id^="projects"]').change(function()
    {
        executionID = $(this).attr('id').replace('projects', '');
        $td = $(this).closest('td');
        if($td.find('[id^="syncStories"]').length == 0)
        {
            $td.append("<input type='hidden' id='syncStories" + executionID + "' name='syncStories[" + executionID + "]' value='no' />");
        }
        $("#syncStories" + executionID).val(confirm(confirmSyncStories) ? 'yes' : 'no');
    })
});
