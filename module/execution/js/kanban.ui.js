searchValue = '';
window.getLane = function(lane)
{
    /* 看板只有一个泳道时，铺满全屏。 */
    if(laneCount < 2) lane.minHeight = window.innerHeight - 235;
}

window.getCol = function(col)
{
    /* 计算WIP。*/
    const limit = col.limit == -1 ? "<i class='icon icon-md icon-infinite'></i>" : col.limit;
    const cards = col.cards;

    col.subtitleClass = 'ml-1';

    let wip = `(${cards} / ${limit})`;

    if(col.limit != -1 && cards > col.limit)
    {
        col.subtitleClass += ' text-danger';
        wip += ' <i class="icon icon-exclamation-sign" data-toggle="tooltip" data-title="' + kanbanLang.limitExceeded + '"></i>';
    }

    col.subtitle = {html: wip};
}

/*
 * 构造看板泳道上的操作按钮。
 * Build action buttons on the kanban lane.
 */
window.getLaneActions = function(lane)
{
    if(!lane.hasOwnProperty('actionList')) return false;

    return [{
        type: 'dropdown',
        icon: 'ellipsis-v',
        caret: false,
        items: [
            lane.actionList.includes('editLaneName') ? {text: kanbanLang.editLaneName, icon: 'edit',  url: $.createLink('kanban', 'editLaneName', 'id=' + lane.id), 'data-toggle': 'modal'} : null,
            lane.actionList.includes('editLaneColor') ? {text: kanbanLang.editLaneColor, icon: 'color',  url: $.createLink('kanban', 'editLaneColor', 'id=' + lane.id), 'data-toggle': 'modal'} : null,
            lane.actionList.includes('deleteLane') ? {text: kanbanLang.deleteLane, icon: 'trash',  url: $.createLink('kanban', 'deleteLane', 'regionID=' + lane.region + '&id=' + lane.id), 'data-confirm': laneLang.confirmDelete, 'innerClass': 'ajax-submit'} : null,
        ],
    }];
}

window.getColActions = function(col)
{
    let actionList = [];
    const firstCol = ['backlog', 'unconfirmed', 'wait'];

    if(firstCol.includes(col.type))
    {
        actionList.push(
            {
                type: 'dropdown',
                icon: 'expand-alt text-primary',
                caret: false,
                items: buildColCardActions(col),
            }
        );
    }

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

window.buildColActions = function(col)
{
    let actions = [];

    if(col.actionList.includes('setColumn')) actions.push({text: kanbanLang.setColumn, url: $.createLink('kanban', 'setColumn', `columnID=${col.id}&executionID=${executionID}&from=RDKanban`), 'data-toggle': 'modal', 'icon': 'edit'});
    if(col.actionList.includes('setWIP')) actions.push({text: kanbanLang.setWIP, url: $.createLink('kanban', 'setWIP', `columnID=${col.id}&executionID=${executionID}&from=RDKanban`), 'data-toggle': 'modal', 'icon': 'alert'});

    return actions;
}

window.buildColCardActions = function(col)
{
    let actions = [];

    if(col.type == 'backlog')
    {
        if(priv.canCreateStory) actions.push({text: storyLang.create, url: $.createLink('story', 'create', 'productID=' + productID + '&branch=0&moduleID=0&storyID=0&objectID=' + executionID + '&bugID=0&planID=0&todoID=0&extra=regionID=' + col.region + ',laneID=' + 0 + ',columnID=' + col.id), 'data-toggle': 'modal', 'data-size' : 'lg'});
        if(priv.canBatchCreateStory) actions.push({text: storyLang.batchCreate, url: productCount > 1 ? '#batchCreateStory' : $.createLink('story', 'batchCreate', 'productID=' + productID + '&branch=0&moduleID=0&storyID=0&objectID=' + executionID + '&planID=0&storyType=story&extra=regionID=' + col.region + ',laneID=' + 0 + ',columnID=' + col.id), 'data-toggle': 'modal', 'data-size' : 'lg'});
        if(priv.canLinkStory) actions.push({text: executionLang.linkStory, url: $.createLink('execution', 'linkStory', 'executionID=' + executionID + '&browseType=&param=0&orderBy=id_desc&recPerPage=50&pageID=1&extra=laneID=0,columnID=' + col.id), 'data-toggle': 'modal', 'data-size' : 'lg'});
        if(priv.canLinkStoryByPlan) actions.push({text: executionLang.linkStoryByPlan, url: '#linkStoryByPlan', 'data-toggle': 'modal'});
    }
    else if(col.type == 'unconfirmed')
    {
        if(priv.canCreateBug) actions.push({text: bugLang.create, url: $.createLink('bug', 'create', 'productID=' + productID + '&moduleID=0&extra=regionID=' + col.region + ',laneID=' + 0 + ',columnID=' + col.id + ',executionID=' + executionID), 'data-toggle': 'modal', 'data-size' : 'lg'});
        if(priv.canBatchCreateBug)
        {
            if(productCount > 1) actions.push({text: bugLang.batchCreate, url: '#batchCreateBug', 'data-toggle': 'modal'});
            else actions.push({text: bugLang.batchCreate, url: $.createLink('bug', 'batchcreate', 'productID=' + productID + '&branch=all&executionID=' + executionID + '&module=0&extra=regionID=' + col.region + ',laneID=' + 0 + ',columnID=' + col.id), 'data-toggle': 'modal', 'data-size' : 'lg'});
        }
    }
    else if(col.type == 'wait')
    {
        if(priv.canCreateTask) actions.push({text: taskLang.create, url: $.createLink('task', 'create', 'executionID=' + executionID + '&storyID=0&moduleID=0&taskID=0&todoID=0&extra=regionID=' + col.region + ',laneID=' + 0 + ',columnID=' + col.id), 'data-toggle': 'modal', 'data-size' : 'lg'});
        if(priv.canBatchCreateTask) actions.push({text: taskLang.batchCreate, url: $.createLink('task', 'batchcreate', 'executionID=' + executionID + '&storyID=0&moduleID=0&taskID=0&iframe=0&extra=regionID=' + col.region + ',laneID=' + 0 + ',columnID=' + col.id), 'data-toggle': 'modal', 'data-size' : 'lg'});
        if(priv.canImportBug && vision == 'rnd') actions.push({text: executionLang.importBug, url: $.createLink('execution', 'importBug', 'executionID=' + executionID + '&storyID=0&moduleID=0&taskID=0&todoID=0&extra=laneID=' + 0 + ',columnID=' + col.id), 'data-toggle': 'modal', 'data-size' : 'lg'});
    }

    return actions;
}

window.getItem = function(info)
{
    const avatar = renderAvatar(info.item);
    if(info.item.cardType == 'story')
    {
        info.item.icon = 'product';
        if(priv.canViewStory)
        {
            info.item.titleUrl   = $.createLink('story', 'view', `id=${info.item.id}`);
            info.item.titleAttrs = {'data-toggle': 'modal', 'data-size' : 'lg', 'title' : info.item.title};
        }

        const content = `
        <div class='flex items-center'>
          <span class='mr-1'>#${info.item.id}</span>
          <span class='pri-${info.item.pri}'>${info.item.pri}</span>
          <div class='flex-1 flex justify-end'>${avatar}</div>
        </div>
        `;
        info.item.content = {html: content};
    }
    else if(info.item.cardType == 'bug')
    {
        info.item.icon = 'bug';
        if(priv.canViewBug)
        {
            info.item.titleUrl   = $.createLink('bug', 'view', `id=${info.item.id}`);
            info.item.titleAttrs = {'data-toggle': 'modal', 'data-size' : 'lg', 'title' : info.item.title};
        }

        const content = `
        <div class='flex items-center'>
          <span class='mr-1'>#${info.item.id}</span>
          <span class='severity' data-severity='${info.item.severity}'></span>
          <div class='flex-1 flex justify-end'>${avatar}</div>
        </div>
        `;
        info.item.content = {html: content};
    }
    else if(info.item.cardType == 'task')
    {
        info.item.icon = 'checked';
        if(priv.canViewTask)
        {
            info.item.titleUrl   = $.createLink('task', 'view', `id=${info.item.id}`);
            info.item.titleAttrs = {'data-toggle': 'modal', 'data-size' : 'lg', 'title' : info.item.name};
        }

        const content = `
        <div class='flex items-center'>
          <span class='pri-${info.item.pri}'>${info.item.pri}</span>
          <span class='text-sm ml-2 mr-1'>${taskLang.estimateAB}</span>
          <span class='text-sm'>${info.item.estimate}h</span>
          <div class='flex-1 flex justify-end'>${avatar}</div>
        </div>
        `;
        info.item.content = {html: content};
    }

    if(searchValue != '')
    {
        info.item.title = info.item.title.replaceAll(searchValue, "<span class='text-danger'>" + searchValue + "</span>");
        info.item.title = {html: info.item.title};
    }
}

window.renderAvatar = function(item)
{
    let assignLink = '';
    if(item.cardType == 'story' && priv.canAssignStory) assignLink = $.createLink('story', 'assignTo', "id=" + item.id);
    if(item.cardType == 'bug' && priv.canAssignBug) assignLink = $.createLink('bug', 'assignTo', "id=" + item.id);
    if(item.cardType == 'task' && priv.canAssignTask) assignLink = $.createLink('task', 'assignTo', "executionID=" + executionID + "&id=" + item.id);

    if(item.assignedTo.length == 0)
    {
        if(assignLink != '') return '<a href="' + assignLink + '" class="avatar rounded-full size-xs ml-1" title="' + cardLang.noAssigned + '" data-toggle="modal" style="background: #ccc; color: #fff"><i class="icon icon-person"></i></a>';
        return '<div class="avatar rounded-full size-xs ml-1" title="' + cardLang.noAssigned + '" style="background: #ccc"><i class="icon icon-person"></i></div>';
    }
    else
    {
        if(assignLink != '') return '<a href="' + assignLink + '" class="avatar rounded-full size-xs ml-1 primary" title="' + item.realnames + '" data-toggle="modal">' + item.avatarList[0] + '</a>';
        return '<div class="avatar rounded-full size-xs ml-1 primary" title="' + item.realnames + '">' + item.avatarList[0] + '</div>';
    }
}

window.getItemActions = function(item)
{
    let actions = [];
    if(item.cardType == 'story')     actions = buildStoryActions(item);
    else if(item.cardType == 'bug')  actions = buildBugActions(item);
    else if(item.cardType == 'task') actions = buildTaskActions(item);

    return [{
        type: 'dropdown',
        icon: 'ellipsis-v',
        caret: false,
        items: actions
    }];
}

window.buildStoryActions = function(item)
{
    let actions = [];

    if(priv.canEditStory) actions.push({text: storyLang.edit, icon: 'edit', url: $.createLink('story', 'edit', 'storyID=' + item.id + '&kanbanGroup=' + groupBy), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canChangeStory && item.status == 'active') actions.push({text: storyLang.change, icon: 'change', url: $.createLink('story', 'change', 'storyID=' + item.id), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canCreateTask && item.status == 'active') actions.push({text: executionLang.wbs, icon: 'plus', url: $.createLink('task', 'create', 'executionID=' + executionID + '&storyID=' + item.id), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canBatchCreateTask && item.status == 'active') actions.push({text: executionLang.batchWBS, icon: 'pluses', url: $.createLink('task', 'batchCreate', 'executionID=' + executionID + '&storyID=' + item.id), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canActivateStory && item.status == 'closed') actions.push({text: executionLang.activate, icon: 'magic', url: $.createLink('story', 'activate', 'storyID=' + item.id), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canUnlinkStory) actions.push({text: executionLang.unlinkStory, icon: 'unlink', url: $.createLink('execution', 'unlinkStory', 'executionID=' + executionID + '&storyID=' + item.id + '&confirm=no&from=' + '&laneID=' + item.lane + '&columnID=' + item.column), 'innerClass' : 'ajax-submit'});
    if(priv.canDeleteStory) actions.push({text: storyLang.delete, icon: 'trash', url: $.createLink('story', 'delete', 'storyID=' + item.id), 'innerClass': 'ajax-submit'});

    return actions;
}

window.buildBugActions = function(item)
{
    let actions = [];

    if(priv.canEditBug) actions.push({text: bugLang.edit, icon: 'edit', url: $.createLink('bug', 'edit', 'bugID=' + item.id), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canResolveBug && (item.status == 'unconfirmed' || item.status == 'confirmed' || item.status == 'fixing')) actions.push({text: bugLang.resolve, icon: 'checked', url: $.createLink('bug', 'resolve', 'bugID=' + item.id), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canConfirmBug && (item.status == 'fixed' || item.status == 'testing' || item.status == 'tested')) actions.push({text: bugLang.close, icon: 'off', url: $.createLink('bug', 'close', 'bugID=' + item.id), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canConfirmBug && item.status == 'unconfirmed') actions.push({text: bugLang.confirm, icon: 'ok', url: $.createLink('bug', 'confirm', 'bugID=' + item.id), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canCopyBug) actions.push({text: bugLang.copy, icon: 'copy', url: $.createLink('bug', 'create', 'productID=' + productID + '&branch=&extras=bugID=' + item.id + ',regionID=' + item.lane + ',laneID=' + item.lane + ',columnID=' + item.column + ',executionID=' + executionID), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canToStoryBug && (item.status != 'closed')) actions.push({text: bugLang.toStory, icon: 'lightbulb', url: $.createLink('story', 'create', 'product=' + productID + '&branch=' + '0' + '&module=' + '0' + '&story=' + '0' + '&execution=' + '0' + '&bugID=' + item.id), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canActivateBug && (item.status == 'fixed' || item.status == 'testing' || item.status == 'tested' || item.status == 'closed')) actions.push({text: bugLang.activate, icon: 'magic', url: $.createLink('bug', 'activate', 'bugID=' + item.id), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canDeleteBug) actions.push({text: bugLang.delete, icon: 'trash', url: $.createLink('bug', 'delete', 'bugID=' + item.id), 'data-confirm': bugLang.confirmDelete, 'innerClass': 'ajax-submit'});

    return actions;
}

window.buildTaskActions = function(item)
{
    let actions = [];

    if(priv.canEditTask) actions.push({text: taskLang.edit, icon: 'edit', url: $.createLink('task', 'edit', 'taskID=' + item.id + '&comment=&kanbanGroup=' + groupBy), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canRestartTask && item.status == 'pause') actions.push({text: taskLang.restart, icon: 'play', url: $.createLink('task', 'restart', 'taskID=' + item.id + '&from=execution'), 'data-toggle': 'modal'});
    if(priv.canPauseTask && item.status == 'developing') actions.push({text: taskLang.pause, icon: 'pause', url: $.createLink('task', 'pause', 'taskID=' + item.id), 'data-toggle': 'modal'});
    if(priv.canRecordWorkhourTask) actions.push({text: executionLang.effort, icon: 'time', url: $.createLink('task', 'recordWorkhour', 'taskID=' + item.id), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canActivateTask && (item.status == 'developed' || item.status == 'canceled' || item.status == 'closed')) actions.push({text: executionLang.activate, icon: 'magic', url: $.createLink('task', 'activate', 'taskID=' + item.id), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canCreateTask) actions.push({text: taskLang.copy, icon: 'copy', url: $.createLink('task', 'create', 'executionID=' + executionID + '&storyID=' + '0' + '&moduleID=' + '0' + '&taskID=' + item.id), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canCancelTask && (item.status == 'wait' || item.status == 'developing' || item.status == 'pause')) actions.push({text: taskLang.cancel, icon: 'cancel', url: $.createLink('task', 'cancel', 'taskID=' + item.id), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(priv.canDeleteTask) actions.push({text: taskLang.delete, icon: 'trash', url: $.createLink('task', 'delete', 'taskID=' + item.id), 'data-confirm': taskLang.confirmDelete, 'innerClass': 'ajax-submit'});

    return actions;
}

window.canDrop = function(dragInfo, dropInfo)
{
    if(!dragInfo) return false;

    const fromCol  = this.getCol(dragInfo.col);
    const fromLane = this.getLane(dragInfo.lane);
    const toCol    = this.getCol(dropInfo.col);
    const toLane   = this.getLane(dropInfo.lane);
    const item     = dragInfo.item;
    if(!toCol || !toLane) return false;

    /* 卡片的排序目前仅支持本单元格内排序 */
    if(dropInfo.type == 'item' && (fromCol != toCol || fromLane != toLane)) return false;
    if(item.lane != toLane.id) return false;

    const kanbanRules = window.kanbanDropRules[item.cardType];
    const colRules    = kanbanRules[fromCol.type];

    if(!colRules || !colRules.includes(toCol.type)) return false;
}

window.onDrop = function(changes, dropInfo)
{
    if(!dropInfo) return false;

    const item     = dropInfo['drag']['item'];
    const fromCol  = item.column;
    const fromLane = item.lane;
    const toCol    = dropInfo['drop']['col']
    const toLane   = dropInfo['drop']['lane']

    if(fromCol == toCol && fromLane == toLane)
    {
        let sortList = '';
        for(let i = 0; i < dropInfo['data']['list'].length; i++) sortList += dropInfo['data']['list'][i] + ',';
        url = $.createLink('kanban', 'sortCard', `kanbanID=${kanbanID}&laneID=${toLaneID}&columnID=${toColID}&cards=${sortList}`);
    }
    else
    {
        const fromColType = this.getCol(fromCol).type;
        const toColType   = this.getCol(toCol).type;
        const regionID    = this.getLane(toLane).region;
        changeCardColType(item.id, fromCol, toCol, fromLane, toLane, item.cardType, fromColType, toColType, regionID);
        return false;
    }
}

/* Define drag and drop rules */
if(!window.kanbanDropRules)
{
    window.kanbanDropRules =
    {
        story:
        {
            backlog: ['ready', 'backlog'],
            ready: ['backlog', 'ready'],
            tested: ['verified'],
            verified: ['tested', 'released'],
            released: ['verified', 'closed'],
            closed: ['released'],
        },
        bug:
        {
            'unconfirmed': ['unconfirmed', 'confirmed', 'fixing', 'fixed'],
            'confirmed': ['confirmed', 'fixing', 'fixed'],
            'fixing': ['fixing', 'fixed'],
            'fixed': ['fixed', 'testing', 'tested', 'fixing'],
            'testing': ['testing', 'tested', 'closed', 'fixing'],
            'tested': ['tested', 'closed', 'fixing'],
            'closed': ['closed', 'fixing'],
        },
        task:
        {
            'wait': ['wait', 'developing', 'developed', 'canceled'],
            'developing': ['developing', 'developed', 'pause', 'canceled'],
            'developed': ['developed', 'developing', 'closed'],
            'pause': ['pause', 'developing', 'canceled'],
            'canceled': ['canceled', 'developing', 'closed'],
            'closed': ['closed', 'developing'],
        }
    }
}

function changeCardColType(cardID, fromColID, toColID, fromLaneID, toLaneID, cardType, fromColType, toColType, regionID = 0)
{
    let objectID   = cardID;
    let showIframe = false;
    let moveCard   = false;

    regionID = regionID ? regionID : 0;

    /* Task lane. */
    if(cardType == 'task')
    {
        if(toColType == 'developed')
        {
            if((fromColType == 'developing' || fromColType == 'wait') && priv.canFinishTask)
            {
                var link = $.createLink('task', 'finish', 'taskID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'pause')
        {
            if(fromColType == 'developing' && priv.canPauseTask)
            {
                var link = $.createLink('task', 'pause', 'taskID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'developing')
        {
            if((fromColType == 'canceled' || fromColType == 'closed' || fromColType == 'developed') && priv.canActivateTask)
            {
                var link = $.createLink('task', 'activate', 'taskID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
            if(fromColType == 'pause' && priv.canActivateTask)
            {
                var link = $.createLink('task', 'restart', 'taskID=' + objectID + '&from=execution', '', true);
                showIframe = true;
            }
            if(fromColType == 'wait' && priv.canStartTask)
            {
                var link = $.createLink('task', 'start', 'taskID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'canceled')
        {
            if((fromColType == 'developing' || fromColType == 'wait' || fromColType == 'pause') && priv.canCancelTask)
            {
                var link = $.createLink('task', 'cancel', 'taskID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'closed')
        {
            if((fromColType == 'developed' || fromColType == 'canceled') && priv.canCloseTask)
            {
                var link = $.createLink('task', 'close', 'taskID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }

        if(fromLaneID != toLaneID && fromColID == toColID) ajaxMoveCard(objectID, fromColID, toColID, fromLaneID, toLaneID, regionID);
    }

    /* Bug lane. */
    if(cardType == 'bug')
    {
        if(toColType == 'confirmed')
        {
            if(fromColType == 'unconfirmed' && priv.canConfirmBug)
            {
                var link = $.createLink('bug', 'confirm', 'bugID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'fixing')
        {
            if(fromColType == 'confirmed' || fromColType == 'unconfirmed') moveCard = true;
            if((fromColType == 'closed' || fromColType == 'fixed' || fromColType == 'testing' || fromColType == 'tested') && priv.canActivateBug)
            {
                var link = $.createLink('bug', 'activate', 'bugID=' + objectID + '&kanbanInfo=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'fixed')
        {
            if(fromColType == 'fixing' || fromColType == 'confirmed' || fromColType == 'unconfirmed')
            {
                var link = $.createLink('bug', 'resolve', 'bugID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'testing')
        {
            if(fromColType == 'fixed') moveCard = true;
        }
        else if(toColType == 'tested')
        {
            if(fromColType == 'fixed' || fromColType == 'testing') moveCard = true;
        }
        else if(toColType == 'closed')
        {
            if(fromColType == 'testing' || fromColType == 'tested')
            {
                var link = $.createLink('bug', 'close', 'bugID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }

        if(moveCard || (fromLaneID != toLaneID && fromColID == toColID)) ajaxMoveCard(objectID, fromColID, toColID, fromLaneID, toLaneID, regionID);
    }

    /* Story lane. */
    if(cardType == 'story')
    {
        if(toColType == 'closed' && priv.canCloseStory)
        {
            var link = $.createLink('story', 'close', 'storyID=' + objectID, '', true);
            showIframe = true;
        }
        else
        {
            if(toColType == 'ready')
            {
                $.get($.createLink('story', 'ajaxGetInfo', "storyID=" + cardID), function(data)
                {
                    data = JSON.parse(data);
                    if(data.status == 'draft' || data.status == 'changing' || data.status == 'reviewing')
                    {
                        zui.Modal.alert(executionLang.storyDragError);
                    }
                    else
                    {
                        ajaxMoveCard(objectID, fromColID, toColID, fromLaneID, toLaneID, regionID);
                    }
                });
            }
            else
            {
                ajaxMoveCard(objectID, fromColID, toColID, fromLaneID, toLaneID, regionID);
            }

        }
    }

    if(showIframe)
    {
        zui.Modal.open({url: link});
    }
}

window.ajaxMoveCard = function(objectID, fromColID, toColID, fromLaneID, toLaneID, regionID)
{
    const link = $.createLink('kanban', 'ajaxMoveCard', 'cardID=' + objectID + '&fromColID=' + fromColID + '&toColID=' + toColID + '&fromLaneID=' + fromLaneID + '&toLaneID=' + toLaneID + '&execitionID=' + executionID + '&browseType=' + browseType + '&groupBy=' + groupBy + '&regionID=' + regionID+ '&orderBy=' + orderBy );
    refreshKanban(link);
}

window.refreshKanban = function(url)
{
    if(typeof url == 'undefined') url = $.createLink('execution', 'ajaxUpdateKanban', "executionID=" + executionID + "&entertime=0&browseType=" + browseType + "&groupBy=" + groupBy + '&from=execution&searchValue=&orderBy=' + orderBy);

    const $kanbanList = $('[data-zui-kanbanlist]').zui('kanbanList');
    let   options     = $kanbanList.options;
    $.getJSON(url, function(data)
    {
        for(const region of data)
        {
            for(const group of region.items)
            {
                group.getLane     = window.getLane;
                group.getCol      = window.getCol;
                group.getItem     = window.getItem;
                group.canDrop     = window.canDrop;
                group.onDrop      = window.onDrop;
                group.minColWidth = minColWidth;
                group.maxColWidth = maxColWidth;
                group.colProps    = {'actions': window.getColActions};
                group.itemProps   = {'actions': window.getItemActions};
            }
        }
        options.items = data;
        $kanbanList.render(options);

        if(data.length == 0)
        {
            if($('#kanbanList').find('.dtable-empty-tip').length == 0) $('#kanbanList').prepend('<div class="dtable-empty-tip" style="background:#fff"><div class="row gap-4 items-center"><span class="text-gray">' + cardLang.empty + '</span></div></div>');
        }
        else
        {
            $('#kanbanList .dtable-empty-tip').remove();
        }
    });
}

window.fullScreen = function()
{
    var element       = document.getElementById('kanbanList');
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullscreen;

    if(requestMethod)
    {
        var afterEnterFullscreen = function()
        {
            $('#kanbanList').addClass('fullscreen').css('background', '#fff');
            $('#kanbanList .kanban-list').css('height', '100%');
            window.hideAllAction();
            $.cookie.set('isFullScreen', 1, {expires:config.cookieLife, path:config.webRoot});
        };

        var whenFailEnterFullscreen = function()
        {
            exitFullScreen();
        };

        try
        {
            var result = requestMethod.call(element);
            if(result && (typeof result.then === 'function' || result instanceof window.Promise))
            {
                result.then(afterEnterFullscreen).catch(whenFailEnterFullscreen);
            }
            else
            {
                afterEnterFullscreen();
            }
        }
        catch (error)
        {
            whenFailEnterFullscreen(error);
        }
    }
}

/**
 * Exit full screen.
 *
 * @access public
 * @return void
 */
function exitFullScreen()
{
    $('.btn').show();
    $('#kanbanList .kanban-list').css('height', 'calc(100vh - 120px)');
    $.cookie.set('isFullScreen', 0, {expires:config.cookieLife, path:config.webRoot});
}

document.addEventListener('fullscreenchange', function (e)
{
    if(!document.fullscreenElement) exitFullScreen();
});

document.addEventListener('webkitfullscreenchange', function (e)
{
    if(!document.webkitFullscreenElement) exitFullScreen();
});

document.addEventListener('mozfullscreenchange', function (e)
{
    if(!document.mozFullScreenElement) exitFullScreen();
});

document.addEventListener('msfullscreenChange', function (e)
{
    if(!document.msfullscreenElement) exitFullScreen();
});

window.hideAllAction = function()
{
    $('.btn').hide();
}

window.changeBrowseType = function()
{
    const type = $('.c-type [name=type]').val();
    loadPage($.createLink('execution', 'kanban', "executionID=" + executionID + '&type=' + type));
};

window.changeGroupBy = function()
{
    const group = $('.c-group [name=group]').val();
    const type  = $('.c-type [name=type]').val();
    loadPage($.createLink('execution', 'kanban',  'executionID=' + executionID + '&type=' + type + '&orderBy=order_asc' + '&groupBy=' + group));
};

window.toggleSearchBox = function()
{
    $('#kanbanSearch').toggle();

    if($('#kanbanSearch').css('display') != 'none')
    {
        $(".querybox-toggle").css("color", "#0c64eb");
    }
    else
    {
        $(".querybox-toggle").css("color", "#3c495c");
        $('#kanbanSearchInput').attr('value', '');
        searchCards('');
    }
};

window.debounce = function (callback, delay)
{
    let timer;
    return function() {
        const context = this;
        const args = arguments;

        clearTimeout(timer);

        timer = setTimeout(function () {
            callback.apply(context, args);
        }, delay);
    };
};

$('#kanbanSearchInput').on('input', debounce(function(){
    searchCards($(this).val());
}, 500));

window.searchCards = function(value, order)
{
    searchValue = value;
    if(typeof order == 'undefined') order = orderBy;
    refreshKanban($.createLink('execution', 'ajaxUpdateKanban', "executionID=" + executionID + "&entertime=0&browseType=" + browseType + "&groupBy=" + groupBy + '&from=execution&searchValue=' + value + '&orderBy=' + order));
};

window.changeStoryProduct = function()
{
    const productID = $('#batchCreateStory [name=productName]').val();
    if(productID) $('#batchCreateStoryButton').attr('href', $.createLink('story', 'batchCreate', 'productID=' + productID + '&branch=&moduleID=0&storyID=0&executionID=' + executionID));
};

window.changeBugProduct = function()
{
    const productID = $('#batchCreateBug [name=productName]').val();
    if(productID) $('#batchCreateBugButton').attr('href', $.createLink('bug', 'batchCreate', 'productID=' + productID + '&branch=&executionID=' + executionID));
};

window.linkPlanStory = function()
{
    const planID = $('[name=plan]').val();
    if(planID)
    {
        var param = "&param=executionID=" + executionID + ",browseType=" + browseType + ",orderBy=id_asc,groupBy=" + groupBy;
        $.ajaxSubmit({url: $.createLink('execution', 'importPlanStories', 'executionID=' + executionID + '&planID=' + planID + '&productID=0&fromMethod=taskKanban&extra=' + param)});
    }
};
