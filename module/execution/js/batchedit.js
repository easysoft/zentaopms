function sync(obj, executionID, projectID)
{
    var $td = $(obj).closest('td');
    if($td.find('[id^="syncStories"]').length == 0)
    {
        console.log($td);
        $td.append("<input type='hidden' id='syncStories" + executionID + "' name='syncStories[" + executionID + "]' value='no' />");
    }
    var confirmVal = confirm(confirmSync);
    $("#syncStories" + executionID).val(confirmVal ? 'yes' : 'no');
    if(!confirmVal)
    {
        $(obj).val(projectID).trigger("chosen:updated");
    }
};
