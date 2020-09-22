$(function()
{
    $('#copyProjects a').click(function(){setCopyProject($(this).data('id')); $('#copyProjectModal').modal('hide')});
    $('#longTime').change(function()
    {
        if($(this).prop('checked'))
        {
            $('#end').val('').attr('disabled', 'disabled');
        }
        else
        {
            $('#end').removeAttr('disabled');
        }
    });
});

function setCopyProject(copyProjectID)
{
    location.href = createLink('program', 'PRJCreate', 'template=' + template + '&programID=' + programID + '&from=' + from + '&copyProjectID=' + copyProjectID);
}
