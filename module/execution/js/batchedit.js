$(function()
{
    $('[id^="projects"]').change(function()
    {
        var executionID = $(this).attr('id').replace('projects', '');
        var $td = $(this).closest('td');
        if($td.find('[id^="sync"]').length == 0)
        {
            $td.append("<input type='hidden' id='sync" + executionID + "' name='sync[" + executionID + "]' value='no' />");
        }
        alert(confirmSync);
        $("#sync" + executionID).val('yes');
    })
});
