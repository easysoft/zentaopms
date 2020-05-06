$(function()
{
    $('#copyProjects a').click(function(){setCopyProject($(this).data('id')); $('#copyProjectModal').modal('hide')});
});

function setCopyProject(programID)
{
    location.href = createLink('program', 'create', 'type=' + type + '&copyProgramID=' + programID);
}
