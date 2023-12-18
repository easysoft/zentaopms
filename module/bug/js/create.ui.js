$(function()
{
    changeProductConfirmed = true;
    if(parseInt(bug.execution.id))
    {
        changeExecution();
    }
    else if(parseInt(bug.project))
    {
        const projectID = bug.projectID;
        loadProjectBuilds(projectID);
        loadAssignedToByProject(projectID);
    }
    else
    {
        const productID  = bug.productID;
        const moduleID   = bug.moduleID;
        const assignedTo = bug.assignedTo;
        if(!assignedTo) setTimeout(function(){loadAssignedToByModule(moduleID, productID)}, 500);
    }

    $('#buildBoxActions').closest('.input-group').find('.picker-box').on('inited', function(_, info){loadBuildActions()});
});
