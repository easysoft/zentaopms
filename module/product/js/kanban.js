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
    var columns         = [];
    var hasDoingProject = false;
    var executionsCol;
    $.each(kanbanColumns, function(_, column)
    {
        var colType = column.type;
        if(colType === 'doingProject') hasDoingProject = true;
        column = $.extend({}, column,
        {
            kanban:     kanbanId,
            id:         kanbanId + '-' + colType,
            parentType: (hasDoingProject && (colType === 'doingProject' || colType === 'doingExecution')) ? 'doing' : false,
        });

        if(colType === 'doingProject')
        {
            columns.push(
            {
                kanban:   kanbanId,
                id:       kanbanId + '-doing',
                type:     'doing',
                asParent: true,
                name:     doingText,
                count:    ''
            });
        }
        else if(colType === 'doingExecution')
        {
            executionsCol = column;
            executionsCol.count = 0;
        }
        columns.push(column);
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
            if(hasDoingProject)
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

                        executionsCol.count++;
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
                    $.each(productExecutions, function(_, execution)
                    {
                        if(!execution || !execution.id) return;
                        var executionID = execution.id;
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

/** Calculate column height */
function calcColHeight(col, lane, colCards, colHeight)
{
    if (col.type !== 'doingProject') return colHeight;
    return colCards.length * 62;
}

/**
 * Init kanban.
 *
 * @access public
 * @return void
 */
function initKanban()
{
    $.each(kanbanList, function(key, programsData)
    {
        var $kanban = $('#kanban-' + key);
        if(!$kanban.length) return;
        var data = processKanbanData(key, programsData);
        $kanban.kanban(
        {
            data:            data,
            noLaneName:      isLightMode,
            virtualize:      true,
            virtualCardList: true,
            calcColHeight:   calcColHeight
        });
    });
}

$(function()
{
    /* Init all kanbans */
    initKanban();

    $('#showAllProjects').click(function()
    {
        var showAllProjects = $(this).prop('checked') ? 1 : 0;
        $.post(createLink('product', 'ajaxSetShowSetting'), {"showAllProjects": showAllProjects}, function()
        {
            $.get(createLink('product', 'kanban'), function(data)
            {
                $('#kanbanList').html($(data).find('#kanbanList').html());
                initKanban();
            });
        })
    })
});
