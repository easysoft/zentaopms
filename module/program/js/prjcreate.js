$(function()
{
    $('#copyProjects a').click(function(){setCopyProject($(this).data('id')); $('#copyProjectModal').modal('hide')});
    $('#longTime').change(function()
    {
        if($(this).prop('checked'))
        {
            $('#end').val('').attr('disabled', 'disabled');
            $('#days').val('');
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

function setAclList(programID)
{
    if(programID != 0)
    {
        $('.aclBox').html($('#PGMAcl').html());
    }
    else
    {
        $('.aclBox').html($('#PRJAcl').html());
    }
}
