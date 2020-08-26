$(function()
{
    $('#copyProjects a').click(function(){setCopyProject($(this).data('id')); $('#copyProjectModal').modal('hide')});
});

function setCopyProject(copiedProgramID)
{
    location.href = createLink('program', 'create', 'template=' + template + '&programID=' + programID + '&copyProgramID=' + copiedProgramID);
}
