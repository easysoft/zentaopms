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
                        if(!project || !project.id) return;
                        var projectItem = $.extend({}, project, {id: 'project-' + projectID, _id: projectID});
                        items.doingProject.push(projectItem);

                        var execution = latestExecutions[projectID];
                        if(!execution || !execution.id) return;
                        projectItem.execution = $.extend({}, execution, {id: 'execution-' + execution.id, _id: execution.id});
                    });
                }
            }
            else
            {
                /* doing execution */
                items.doingExecution = [];
                var productExecutions = classicExecution[productID];
                if(productExecutions)
                {
                    console.log(productExecutions);
                    $.each(productExecutions, function(executionID, execution)
                    {
                        if(!execution || !execution.id) return;
                        var executionItem = $.extend({}, execution, {id: 'execution-' + executionID, _id: executionID});
                        items.doingExecution.push(executionItem);
                    });
                }
            }


            /* normal release */
            items.normalRelease = [];
            var releases = releaseList[productID];
            if(releases)
            {
                $.each(releases, function(releaseID, release)
                {
                    if(!release || !release.id) return;
                    var releaseItem = $.extend({}, release, {id: 'release-' + releaseID, _id: releaseID});
                    items.normalRelease.push(releaseItem);
                });
            }

            subLanes.push({id: kanbanId + '-' + programId + '-' + productID, items: items});
        });

        var programName = programList[programId];
        if(programName === undefined) programName = '(' + programId + ')';
        lanes.push({id: programId, kanban: kanbanId, name: programName, subLanes: subLanes});
    });

    return {id: kanbanId, columns: columns, lanes: lanes};
}

/**
 * Render project item
 * @param {Object} item  Project item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderDoingProjectItem(item, $item)
{
    $item.removeClass('kanban-item').addClass('project-row clearfix').empty();

    var $projectCol = $('<div class="project-col"></div>').appendTo($item);
    var $projectItem = $('<div class="kanban-item project-item"></div>').appendTo($projectCol);
    renderProjectItem(item, $projectItem);

    var $executionCol = $('<div class="project-col"></div>').appendTo($item);
    if(item.execution)
    {
        var $executionItem = $('<div class="kanban-item execution-item"></div>').appendTo($executionCol);
        renderExecutionItem(item, $executionItem);
    }

    return $item;
}


$(function()
{
    /* Add custom renderer for doing project */
    addColumnRenderer('doingProject', renderDoingProjectItem);

    /* Init all kanbans */
    $.each(kanbanList, function(key, programsData)
    {
        var data = processKanbanData(key, programsData);
        $('#kanban-' + key).kanban({data: data, noLaneName: isClassicMode});
    });
});
