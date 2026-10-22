window.changeExecutionBuilds = function(event)
{
    const executionID = $(event.target).val();
    if(executionID) return loadExecutionBuilds(executionID);

    const projectID = $('[name="project"]').val();
    if(projectID) return loadProjectBuilds(projectID);

    return loadProductBuilds($('[name="product"]').val());
};
