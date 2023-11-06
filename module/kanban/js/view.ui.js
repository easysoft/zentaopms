window.getLane = function(lane)
{
    /* 看板只有一个泳道时，铺满全屏。 */
    if(laneCount < 2) lane.minHeight = window.innerHeight - 235;
}

window.getLaneActions = function(lane)
{
}

window.getCol = function(col)
{
    /* 计算WIP。*/
    const limit = col.limit == -1 ? "<i class='icon icon-md icon-infinite'></i>" : col.limit;
    const cards = columnCount[col.id] || 0;
    col.subtitle = {html: `(${cards} / ${limit})`};
    col.subtitleClass = 'ml-1';
}

window.getColActions = function(col)
{
    let actionList = [];

    /* 父列不需要创建卡片相关的操作按钮。 */
    if(col.parent != '-1') actionList.push(
        {
            type: 'dropdown',
            icon: 'expand-alt text-primary',
            caret: false,
            items: buildColCardActions(col),
        }
    );

    actionList.push(
        {
            type: 'dropdown',
            icon: 'ellipsis-v',
            caret: false,
            items: buildColActions(col),
        }
    );

    return actionList;
}

/*
 * 构造看板上的创建卡片相关操作按钮。
 * Build create card related action buttons on the kanban.
 */
window.buildColCardActions = function(col)
{
    let actions = [];

    if(col.actionList.includes('createCard')) actions.push({text: kanbanLang.createCard, url: $.createLink('kanban', 'createCard', `kanbanID=${kanbanID}&reginID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal'});
    if(col.actionList.includes('batchCreateCard')) actions.push({text: kanbanLang.batchCreateCard, url: $.createLink('kanban', 'batchCreateCard', `kanbanID=${kanbanID}&reginID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal'});
    if(kanban.object.indexOf('cards') != -1) actions.push({text: kanbanLang.importCard, url: $.createLink('kanban', 'importCard', `kanbanID=${kanbanID}&regionID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal'});
    if(kanban.object && kanban.object != 'cards' && vision != 'lite')
    {
        actions.push({type: 'divider'});
        actions.push(
            {
                text: kanbanLang.importAB,
                caret: true,
                items: [
                    {text: kanbanLang.importPlan, url: $.createLink('kanban', 'importPlan', `kanbanID=${kanbanID}&regionID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal'},
                    {text: kanbanLang.importRelease, url: $.createLink('kanban', 'importRelease', `kanbanID=${kanbanID}&regionID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal'},
                    {text: kanbanLang.importExecution, url: $.createLink('kanban', 'importExecution', `kanbanID=${kanbanID}&regionID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal'},
                    {text: kanbanLang.importBuild, url: $.createLink('kanban', 'importBuild', `kanbanID=${kanbanID}&regionID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal'},
                    {text: kanbanLang.importTicket, url: $.createLink('kanban', 'importTicket', `kanbanID=${kanbanID}&regionID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal'},
                ]
            }
        );
    }

    return actions;
}

/*
 * 构造看板上的创建列、拆分列等操作按钮。
 * Build create column, split column and other action buttons on the kanban.
 */
window.buildColActions = function(col)
{
    let actions = [];

    if(col.actionList.includes('setColumn')) actions.push({text: kanbanLang.setColumn, url: $.createLink('kanban', 'setColumn', `columnID=${col.id}`), 'data-toggle': 'modal', 'icon': 'edit'});
    if(col.actionList.includes('setWIP')) actions.push({text: kanbanLang.setWIP, url: $.createLink('kanban', 'setWIP', `columnID=${col.id}`), 'data-toggle': 'modal', 'icon': 'alert'});
    actions.push({type: 'divider'});
    if(col.actionList.includes('splitColumn')) actions.push({text: kanbanLang.splitColumn, url: $.createLink('kanban', 'splitColumn', `columnID=${col.id}`), 'data-toggle': 'modal', 'icon': 'col-split'});
    if(col.actionList.includes('createColumn')) actions.push({text: kanbanLang.createColumnOnLeft, url: $.createLink('kanban', 'createColumn', `columnID=${col.id}&position=left`), 'data-toggle': 'modal', 'icon': 'col-add-left'});
    if(col.actionList.includes('createColumn')) actions.push({text: kanbanLang.createColumnOnRight, url: $.createLink('kanban', 'createColumn', `columnID=${col.id}&position=right`), 'data-toggle': 'modal', 'icon': 'col-add-right'});
    actions.push({type: 'divider'});
    if(col.actionList.includes('archiveColumn')) actions.push({text: kanbanLang.archiveColumn, url: $.createLink('kanban', 'archiveColumn', `columnID=${col.id}`), 'icon': 'card-archive', 'data-confirm': columnLang.confirmArchive, 'innerClass': 'ajax-submit'});
    if(col.actionList.includes('deleteColumn')) actions.push({text: kanbanLang.deleteColumn, url: $.createLink('kanban', 'deleteColumn', `columnID=${col.id}`), 'icon': 'trash', 'innerClass': 'ajax-submit', 'data-confirm': columnLang.confirmDelete});

    return actions;
}
