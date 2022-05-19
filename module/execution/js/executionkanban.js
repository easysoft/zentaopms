/**
 * Process kanban data
 * @returns {Object} kanban data
 */
function processKanbanData()
{
    /* Generate columns */
    var columns = [{id: 'project', type: 'project', name: window.langDoingProject, cardType: 'span'}];
    $.each(kanbanColumns, function(type, name)
    {
        columns.push({id: type, type: type, name: name, cardType: 'execution'});
    });

    /* Format lanes data */
    var lanes = [];
    $.each(kanbanGroup, function(projectID, statusMap)
    {
        var projectName = +projectID ? projectNames[projectID] : langMyExecutions;
        var cards       = {project: [{id: projectID, name: projectName}]};

        $.each(kanbanColumns, function(type)
        {
            var cardList   = [];
            var executions = statusMap[type];

            if(!executions) return;

            $.each(executions, function(index, execution)
            {
                var executionCard = $.extend({}, execution, {id: projectID + '-' + execution.id, _id: execution.id});
                cardList.push(executionCard);
            });
            cards[type] = cardList;
        });

        lanes.push({id: projectID, name: projectName, cards: cards});
    });

    return {id: 'executions', columns: columns, lanes: lanes};
}

/* Define drag and drop rules */
if(!window.kanbanDropRules)
{
    window.kanbanDropRules =
    {
        wait: ['doing', 'suspended', 'closed'],
        doing: ['suspended', 'closed'],
        suspended: ['doing', 'closed'],
        closed: ['doing']
    }
}

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
    var kanbanRules = window.kanbanDropRules ? window.kanbanDropRules : null;

    if(!kanbanRules) return $root.find('.kanban-lane[data-id="' + lane.id + '"] .kanban-lane-col:not([data-type="project"],[data-type="' + col.type + '"])');

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
    var cardID      = card.id;
    var executionID = cardID.substr(cardID.indexOf("-") + 1);;
    var showIframe  = false;

    if(toColType == 'doing')
    {
        if(fromColType == 'wait' && priv.canStart)
        {
            var link   = createLink('execution', 'start', 'executionID=' + executionID, '', true);
            showIframe = true;
        }
        if((fromColType == 'suspended' || fromColType == 'closed') && priv.canActivate)
        {
            var link = createLink('execution', 'activate', 'executionID=' + executionID, '', true);
            showIframe = true;
        }
    }
    else if(toColType == 'suspended')
    {
        if((fromColType == 'wait' || fromColType == 'doing') && priv.canSuspend)
        {
            var link = createLink('execution', 'suspend', 'executionID=' + executionID, '', true);
            showIframe = true;
        }
    }
    else if(toColType == 'closed')
    {
        if(priv.canClose)
        {
            var link = createLink('execution', 'close', 'executionID=' + executionID, '', true);
            showIframe = true;
        }
    }

    if(showIframe)
    {
        var modalTrigger = new $.zui.ModalTrigger({type: 'iframe', width: '80%', url: link});
        modalTrigger.show();
    }

    /*
        // TODO: The server must return a updated kanban data  服务器返回更新后的看板数据

        // 调用 updateKanban 更新看板数据
        updateKanban(kanbanID, newKanbanData);
    */
}

/**
 * Handle finish drop task
 * @param {Object} event Event object
 * @returns {void}
 */
function handleFinishDrop(event)
{
    var $card = $(event.element); // The drag card
    var $dragCol = $card.closest('.kanban-lane-col');
    var $dropCol = $(event.target);

    /* Get d-n-d(drag and drop) infos  获取拖放操作相关信息 */
    var card = $card.data('item');
    var fromColType = $dragCol.data('type');
    var toColType = $dropCol.data('type');
    var kanbanID = $card.closest('.kanban').data('id');

    changeCardColType(card, fromColType, toColType, kanbanID);
}

$(function()
{
    var kanbanGroup = window.kanbanGroup;
    if(!kanbanGroup) return;

    $('#kanban').kanban(
    {
        data:            processKanbanData(),
        laneNameWidth:   5,
        virtualize:      true,
        virtualCardList: true,
        droppable:
        {
            selector:     '.kanban-item:not(.kanban-item-span)',
            target:       findDropColumns,
            finish:       handleFinishDrop,
            mouseButton: 'left'
        }
    });
});
