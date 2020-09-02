$(function()
{
    $('#copyProjects a').click(function(){setCopyProject($(this).data('id')); $('#copyProjectModal').modal('hide')});
    $('#isCat').change(function()
    {
        if($(this).prop('checked'))
        {
            $('#longTimeBox').removeClass('hidden');
        }
        else
        {
            $('#longTimeBox').addClass('hidden');
            $('#longTimeBox').find('#longTime').prop('checked', false).change();
        }
    });

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

function setCopyProject(copiedProgramID)
{
    location.href = createLink('program', 'create', 'template=' + template + '&programID=' + parentProgramID + '&copyProgramID=' + copiedProgramID);
}
