$(function()
{
    $('#toTaskButton').click(function()
    {
        var onlybody    = config.onlybody;
        config.onlybody = 'no';

        var projectID = $(this).closest('.input-group').find('#project').val();
        var link      = createLink('task', 'create', 'projectID=' + projectID + '&storyID=0&moduleID=0&taskID=0&todoID=' + todoID);

        config.onlybody      = onlybody;
        parent.location.href = link;

    })
});
