/**
 * Process kanban data
 * @param {string} key          Kanban key, used as kanban id
 * @param {Object} programGroup Group data
 * @returns {Object} kanban data
 */
function processKanbanData(key, programGroup)
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
    $.each(programGroup, function(programId, statusMap)
    {
        var programName = programPairs[programId];
        var items       = {doingExecution: []};

        /* Projects and executions */
        ['wait', 'doing', 'closed'].forEach(function(status)
        {
            var itemsList = [];
            var statusProjects = statusMap[status];
            if(statusProjects)
            {
                $.each(statusProjects, function(_, project)
                {
                    var projectID = project.id;
                    var projectItem = $.extend({}, project, {id: 'project-' + projectID, _id: projectID});
                    itemsList.push(projectItem);

                    if(status === 'doing')
                    {
                        var execution = latestExecutions[projectID];
                        if(execution && execution.id)
                        {
                            if(typeof(executionsCol) == 'object') executionsCol.count++;
                            projectItem.execution = $.extend({}, execution, {id: 'execution-' + execution.id, _id: execution.id});
                        }
                    }
                });
            }
            items[status + 'Project'] = itemsList;
        });

        lanes.push({id: programId, kanban: kanbanId, name: programName, items: items});
    });

    return {id: kanbanId, columns: columns, lanes: lanes};
}

/** Define kanban d-n-d rules */
var projectDropRules =
{
    waitProject:   ['doingProject', 'closedProject'],
    doingProject:  ['closedProject'],
    closedProject: ['doingProject'],
};
window.kanbanDropRules = {my: projectDropRules, other: projectDropRules};

/*
 * Find drop columns
 * @param {JQuery} $element Drag element
 * @param {JQuery} $root Dnd root element
 */
function findDropColumns($element, $root)
{
    var $col        = $element.closest('.kanban-col');
    var col         = $col.data();
    var lane        = $col.closest('.kanban-lane').data();
    var kanbanID    = $root.data('id');
    var kanbanRules = window.kanbanDropRules ? window.kanbanDropRules[kanbanID] : null;

    if(!kanbanRules) return $root.find('.kanban-lane[data-id="' + lane.id + '"] .kanban-lane-col:not([data-type="doingExecution"],[data-type="' + col.type + '"])');

    var colRules = kanbanRules[col.type];
    var lane     = $col.closest('.kanban-lane').data('lane');
    return $root.find('.kanban-lane-col').filter(function()
    {
        if(!colRules) return false;
        if(colRules === true) return true;

        var $newCol = $(this);
        var newCol = $newCol.data();
        if(newCol.id === col.id) return false;

        var $newLane = $newCol.closest('.kanban-lane');
        var newLane = $newLane.data('lane');
        return colRules.indexOf(newCol.type) > -1 && newLane.id === lane.id;
    });
}

/**
 * Change column type for a card
 * @param {Object} card        Card object
 * @param {String} fromColType The column type before change
 * @param {String} toColType   The column type after change
 * @param {String} kanbanID    Kanban ID
 */
function changeCardColType(card, fromColType, toColType, kanbanID)
{
    if(typeof card == 'undefined') return false;
    var cardID     = card.id;
    var projectID  = cardID.substr(cardID.indexOf("-") + 1);;
    var showIframe = false;

    if(toColType == 'doingProject')
    {
        if(fromColType == 'waitProject' && priv.canStart)
        {
            var link   = createLink('project', 'start', 'project=' + projectID, '', true);
            showIframe = true;
        }
        if(fromColType == 'closedProject' && priv.canActivate)
        {
            var link = createLink('project', 'activate', 'projectID=' + projectID, '', true);
            showIframe = true;
        }
    }
    else if(toColType == 'closedProject')
    {
        if(priv.canClose)
        {
            var link = createLink('project', 'close', 'projectID=' + projectID, '', true);
            showIframe = true;
        }
    }

    if(showIframe)
    {
        var modalTrigger = new $.zui.ModalTrigger({type: 'iframe', width: '80%', url: link});
        modalTrigger.show();
    }
}

/**
 * Handle finish drop task
 * @param {Object} event Event object
 * @returns {void}
 */
function handleFinishDrop(event)
{
    var $card    = $(event.element).closest('.kanban-item'); // The drag card
    var $dragCol = $card.closest('.kanban-lane-col');
    var $dropCol = $(event.target);

    /* Get d-n-d(drag and drop) infos. */
    var card        = $card.data('item');
    var fromColType = $dragCol.data('type');
    var toColType   = $dropCol.data('type');
    var kanbanID    = $card.closest('.kanban').data('id');

    changeCardColType(card, fromColType, toColType, kanbanID);
}

/** Calculate column height */
function calcColHeight(col, lane, colCards, colHeight)
{
    if (col.type !== 'doingProject') return colHeight;
    return colCards.length * 62;
}

$(function()
{
    /* Init all kanbans */
    $.each(kanbanGroup, function(key, programGroup)
    {
        var $kanban = $('#kanban-' + key);
        if(!$kanban.length) return;
        $kanban.kanban(
        {
            data:            processKanbanData(key, programGroup),
            calcColHeight:   calcColHeight,
            virtualize:      true,
            virtualCardList: true,
            droppable:
            {
                selector:     '.kanban-card:not(.execution-item)',
                target:       findDropColumns,
                finish:       handleFinishDrop
            },
        });
    });
});
