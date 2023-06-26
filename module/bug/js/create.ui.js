$(function()
{
    changeProductConfirmed = true;
    if(parseInt($('#execution').val()))
    {
        changeExecution();
    }
    else if(parseInt($('#project').val()))
    {
        const projectID = $('#project').val()
        loadProjectBuilds(projectID);
        loadAssignedToByProject(projectID);
    }
    else
    {
        const productID  = $('#product').val();
        const moduleID   = $('#module').val();
        const assignedTo = $('#assignedTo').val();
        if(!assignedTo) setTimeout(function(){loadAssignedToByModule(moduleID, productID)}, 500);
    }
    loadBuildActions();
});
