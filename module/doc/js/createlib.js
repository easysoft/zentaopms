$('#project').change(function()
{
    $.get(createLink('doc', 'ajaxGetExecution', 'projectID=' + $(this).val()), function(data)
    {
        if(data)
        {
            $('#execution').replaceWith(data);
            $('#execution_chosen').remove();
            $('#execution').chosen();
        }
    });
});
