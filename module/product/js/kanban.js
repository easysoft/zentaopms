/**
 * Process kanban data
 * @param {string} key          Kanban key, used as kanban id
 * @param {Object} programsData Programs data
 * @returns {Object} kanban data
 */
function processKanbanData(key, programsData)
{
    var kanbanId = key;

    /* Generate columns */
    var columns = [];
    $.each(kanbanColumns, function(_, column)
    {
        columns.push($.extend({}, column,
        {
            kanban: kanbanId,
            id:     kanbanId + '-' + column.type,
        }));
    });

    /* Format lanes data */
    var lanes = [];
    $.each(programsData, function(programId, programProducts)
    {
        var programName = programList[programId];
        var subLanes = [];
        programProducts.forEach(function(productID)
        {
            var product = productList[productID];
            var items   = {};

            /* unclosed products */
            var productItem = {id: 'product-' + productID, _id: productID, name: product.name};
            items.unclosedProduct = [productItem];

            /* plans */
            items.unexpiredPlan = [];
            var plans = planList[productID];
            if(plans)
            {
                $.each(plans, function(planID, plan)
                {
                    items.unexpiredPlan.push($.extend({}, plan, {id: 'plan-' + planID, _id: planID}));
                });
            }

            /* doing projects */
            if(kanbanColumns.doingProject)
            {
                items.doingProject = [];
                var productProjects = projectProduct[productID];
                if(productProjects)
                {
                    $.each(productProjects, function(projectID)
                    {
                        var project = projectList[projectID];
                        if(!project) return;
                        var projectItem = $.extend({}, project, {id: 'project-' + projectID, _id: projectID});
                        items.doingProject.push(projectItem);
                    });
                }
            }

            /* doing execution */
            items.doingExecution = [];
            var productProjects = projectProduct[productID];
            if(productProjects)
            {
                $.each(productProjects, function(projectID)
                {
                    var execution = latestExecutions[projectID];
                    if(!execution) return;
                    var executionItem = $.extend({}, execution, {id: 'execution-' + execution.id, _id: execution.id});
                    items.doingExecution.push(executionItem);
                });
            }

            /* normal release */
            items.normalRelease = [];
            var releases = releaseList[productID];
            if(releases)
            {
                $.each(releases, function(releaseID, release)
                {
                    if(!release) return;
                    var releaseItem = $.extend({}, release, {id: 'release-' + releaseID, _id: releaseID});
                    items.normalRelease.push(releaseItem);
                });
            }

            subLanes.push({id: kanbanId + '-' + programId + '-' + productID, items: items});
        });

        lanes.push({id: programId, kanban: kanbanId, name: programName, subLanes: subLanes});
    });

    return {id: kanbanId, columns: columns, lanes: lanes};
}

$(function()
{
    /* Init all kanbans */
    $.each(kanbanList, function(key, programsData)
    {
        $('#kanban-' + key).kanban({data: processKanbanData(key, programsData)});
    });
});
