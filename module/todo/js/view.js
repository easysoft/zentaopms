$(function()
{
    $('#toTaskButton').click(function()
    {
        var projectID = $(this).closest('.input-group').find('#project').val();
        var link      = createLink('task', 'create', 'projectID=' + projectID + '&storyID=0&moduleID=0&taskID=0&todoID=' + todoID);
        parent.location.href = link;
    })
});
