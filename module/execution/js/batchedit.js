function changeProject(obj, executionID, projectID)
{
    var lastSelected = $(obj).data('lastselected');
    var $td = $(obj).closest('td');

    if($td.find('[id^="syncStories"]').length == 0)
    {
        $td.append("<input type='hidden' id='syncStories" + executionID + "' name='syncStories[" + executionID + "]' value='no' />");
    }

    var confirmVal = confirm(confirmSync);
    $("#syncStories" + executionID).val(confirmVal ? 'yes' : 'no');

    if(!confirmVal)
    {
        $(obj).val(lastSelected).trigger("chosen:updated");
    }
    else
    {
        lastSelected = $(obj).val();
        $(obj).data("lastselected", lastSelected);
    }
}

$('#executionForm').on('change input mousedown', '.has-error', function()
{
    $(this).parent().find('.text-danger').remove();
    $(this).removeClass('has-error');
})

$(function()
{
    $('[data-toggle="popover"]').popover();
})
