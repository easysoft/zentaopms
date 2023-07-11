$(document).on('click', '#toStory', function()
{
    const message = $(this).data('confirm');
    const url     = $(this).data('url');
    zui.Modal.confirm({message, onResult: function(result)
    {
        if(result) loadPage(url);
    }});
});

$(document).on('click', '#toTaskButton', function()
{
    const projectID   = $('#taskProjects').val();
    const executionID = $('#execution').val() ? $('#execution').val() : 0;

    if(projectID && executionID != 0)
    {
        const url = $.createLink('task', 'create', 'executionID=' + executionID + '&storyID=0&moduleID=0&taskID=0&todoID=0&extra=projectID=' + projectID + '&bugID=' + bugID);
        loadPage(url);
    }
    else if(projectID == 0)
    {
        zui.Modal.alert(errorNoProject);
    }
    else
    {
        zui.modal.alert(errorNoExecution);
    }

});

function changeTaskProjects(event)
{
    const projectID = $(event.target).val();
    const link = $.createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=' + projectID +'&branch=' + branchID + '&number=&executionID=0&from=bugToTask');
    $('#executionBox').load(link);
}
