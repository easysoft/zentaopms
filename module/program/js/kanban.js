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
    $.each(programsData, function(programId, program)
    {
        var subLanes = [];

        $.each(program.products, function(productID, product)
        {
            var items   = {};

            /* unclosed products */
            var productItem = {id: 'product-' + productID, _id: productID, name: product.name};
            items.unclosedProduct = [productItem];

            /* plans */
            items.unexpiredPlan = [];
            var plans = product.plans;
            if(plans)
            {
                $.each(plans, function(planID, plan)
                {
                    items.unexpiredPlan.push($.extend({}, plan, {id: 'plan-' + planID, _id: planID}));
                });
            }

            /* wait projects */
            items.waitProject = [];
            var waitProjects = product.projects && product.projects.wait;
            if(waitProjects)
            {
                $.each(waitProjects, function(projectID, project)
                {
                    var projectItem = $.extend({}, project, {id: 'project-' + projectID, _id: projectID});
                    items.waitProject.push(projectItem);
                });
            }

            /* doing projects and executions */
            items.doingProject = [];
            items.doingExecution = [];
            var doingProjects = product.projects && product.projects.doing;
            if(doingProjects)
            {
                $.each(doingProjects, function(projectID, project)
                {
                    var projectItem = $.extend({}, project, {id: 'project-' + projectID, _id: projectID});
                    items.doingProject.push(projectItem);

                    var execution = project.execution;
                    if(!execution) return;
                    var executionItem = $.extend({}, execution, {id: 'execution-' + execution.id, _id: execution.id});
                    items.doingExecution.push(executionItem);
                });
            }

            /* normal release */
            items.normalRelease = [];
            var releases = product.releases;
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

        var programItem = $.extend({}, program, {id: programId, kanban: kanbanId, subLanes: subLanes});
        lanes.push(programItem);
    });

    return {id: kanbanId, columns: columns, lanes: lanes};
}

$(function()
{
    /* Init all kanbans */
    $.each(kanbanGroup, function(key, programsData)
    {
        var $kanban = $('#kanban-' + key);
        if(!$kanban.length) return;
        $kanban.kanban({data: processKanbanData(key, programsData)});
    });
});
