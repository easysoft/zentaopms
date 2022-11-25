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
    var executionsCol;
    $.each(kanbanColumns, function(_, column)
    {
        var colType = column.type;
        column = $.extend({}, column,
        {
            kanban:     kanbanId,
            id:         kanbanId + '-' + column.type,
            parentType: (colType === 'doingProject' || colType === 'doingExecution') ? 'doing' : false,
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
    $.each(programsData, function(programId, program)
    {
        var subLanes = [];

        $.each(program.products, function(_, product)
        {
            var items   = {};

            /* unclosed products */
            var productID = product.id;
            var productItem = {id: 'product-' + productID, _id: productID, name: product.name, shadow: product.shadow};
            items.unclosedProduct = [productItem];

            /* plans */
            items.unexpiredPlan = [];
            var plans = product.plans;
            if(plans)
            {
                $.each(plans, function(_, plan)
                {
                    var planID = plan.id;
                    items.unexpiredPlan.push($.extend({}, plan, {id: 'plan-' + planID, _id: planID}));
                });
            }

            /* wait projects */
            items.waitProject = [];
            var waitProjects = product.projects && product.projects.wait;
            if(waitProjects)
            {
                $.each(waitProjects, function(_, project)
                {
                    var projectID = project.id;
                    var projectItem = $.extend({}, project, {id: 'project-' + projectID, _id: projectID});
                    items.waitProject.push(projectItem);
                });
            }

            /* doing projects and executions */
            items.doingProject = [];
            var doingProjects = product.projects && product.projects.doing;
            if(doingProjects)
            {
                $.each(doingProjects, function(_, project)
                {
                    var projectID = project.id;
                    var projectItem = $.extend({}, project, {id: 'project-' + projectID, _id: projectID, execution: null});
                    items.doingProject.push(projectItem);

                    var execution = project.execution;
                    if(!execution || !execution.id) return;

                    executionsCol.count++;
                    projectItem.execution = $.extend({}, execution, {id: 'execution-' + execution.id, _id: execution.id});
                });
            }

            /* normal release */
            items.normalRelease = [];
            var releases = product.releases;
            if(releases)
            {
                $.each(releases, function(_, release)
                {
                    if(!release || !release.id) return;
                    var releaseID = release.id;
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
    $.each(kanbanGroup, function(key, programsData)
    {
        var $kanban = $('#kanban-' + key);
        if(!$kanban.length) return;
        $kanban.kanban(
        {
            data:            processKanbanData(key, programsData),
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
        $.post(createLink('program', 'ajaxSetShowSetting'), {"showAllProjects": showAllProjects}, function()
        {
            $.get(createLink('program', 'kanban'), function(data)
            {
                $('#kanbanList').html($(data).find('#kanbanList').html());
                initKanban();
            });
        })
    })
});
