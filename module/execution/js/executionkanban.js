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
    console.log('findDropColumns', {$element, $root, kanbanID, col, lane});
    var kanbanRules = window.kanbanDropRules ? window.kanbanDropRules[kanbanID] : null;

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
 * 变更卡片类型
 * @param {Object} card        Card object
 * @param {String} fromColType The column type before change
 * @param {String} toColType   The column type after change
 * @param {String} kanbanID    Kanban ID
 */
function changeCardColType(card, fromColType, toColType, kanbanID)
{
    /* TODO: Post data to server on change card type 将变更卡片类型操作提交到服务器  */
    console.log('TODO: Post data to server on change card type 将变更卡片类型操作提交到服务器', {card, fromColType, toColType, kanbanID});

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
        data:         processKanbanData(),
        // noLaneName:   true,
        droppable:
        {
            selector:     '.kanban-item:not(.kanban-item-span)',
            target:       findDropColumns,
            finish:       handleFinishDrop,
            mouseButton: 'left'
        },
    });
});
