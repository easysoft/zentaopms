searchValue = '';
const kanbanDropRules =
{
    story:
    {
        'backlog': ['ready'],
        'ready': ['backlog'],
        'tested': ['verified'],
        'verified': ['tested', 'released'],
        'released': ['verified', 'closed'],
        'closed': ['released'],
    },
    bug:
    {
        'unconfirmed': ['confirmed', 'fixing', 'fixed'],
        'confirmed': ['fixing', 'fixed'],
        'fixing': ['fixed'],
        'fixed': ['testing', 'tested', 'fixing'],
        'testing': ['tested', 'closed', 'fixing'],
        'tested': ['closed', 'fixing'],
        'closed': ['fixing'],
    },
    task:
    {
        'wait': ['developing', 'developed', 'canceled'],
        'developing': ['developed', 'pause', 'canceled'],
        'developed': ['developing', 'closed'],
        'pause': ['developing'],
        'canceled': ['developing', 'closed'],
        'closed': ['developing'],
    }
};

window.changeBrowseType = function()
{
    const type = $('.c-type [name=type]').val();
    loadPage($.createLink('execution', 'taskKanban', "executionID=" + executionID + '&type=' + type));
};

window.changeGroupBy = function()
{
    const group = $('.c-group [name=group]').val();
    const type  = $('.c-type [name=type]').val();
    loadPage($.createLink('execution', 'taskKanban',  'executionID=' + executionID + '&type=' + type + '&orderBy=order_asc' + '&groupBy=' + group));
};

window.changeBugProduct = function()
{
    const productID = $('[name=productName]').val();
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

window.toggleSearchBox = function()
{
    $('#taskKanbanSearch').toggle();

    if($('#taskKanbanSearch').css('display') == 'block')
    {
        $(".querybox-toggle").css("color", "#0c64eb");
    }
    else
    {
        $(".querybox-toggle").css("color", "#3c495c");
        $('#taskKanbanSearchInput').attr('value', '');
        searchCards('');
    }
};

window.getLane = function(lane)
{
    /* 看板只有一个泳道时，铺满全屏。 */
    if(laneCount < 2) lane.minHeight = window.innerHeight - 235;
}

/*
 * 构造看板泳道上的操作按钮。
 * Build action buttons on the kanban lane.
 */
window.getLaneActions = function(lane)
{
    return [{
        type: 'dropdown',
        icon: 'ellipsis-v',
        caret: false,
    }];
}

window.getCol = function(col)
{
    /* 计算WIP。*/
    const limit = col.limit == -1 ? "<i class='icon icon-md icon-infinite'></i>" : col.limit;
    const cards = col.cards;

    col.subtitleClass = 'ml-1';

    let wip = `(${cards}/${limit})`;

    if(col.limit != -1 && cards > col.limit)
    {
        col.subtitleClass += ' text-danger';
        wip += ' <i class="icon icon-exclamation-sign" data-toggle="tooltip" data-title="' + kanbanLang.limitExceeded + '"></i>';
    }

    col.subtitle = {html: wip};
}

window.getColActions = function(col)
{
    let actionList = [];

    /* 父列不需要创建卡片相关的操作按钮。 */
    if(col.parent != '-1' && (col.type == 'backlog' || col.type == 'unconfirmed' || col.type == 'wait'))
    {
        let cardActions = buildColCardActions(col);
        if(cardActions.length > 0) actionList.push({type:'dropdown', icon:'expand-alt text-primary', caret:false, items:cardActions});
    }

    actionList.push({type:'dropdown', icon:'ellipsis-v', caret:false, items:buildColActions(col)});
    return actionList;
}

/*
 * 构造看板上的创建卡片相关操作按钮。
 * Build create card related action buttons on the kanban.
 */
window.buildColCardActions = function(col)
{
    let actions = [];
    if(col.type == 'backlog')
    {
        if(priv.canCreateStory)      actions.push({text: storyLang.create, url:$.createLink('story', 'create', 'productID=' + productID + '&branch=0&moduleID=0&storyID=0&objectID=' + executionID), 'data-toggle': 'modal', 'data-size': 'lg'});
        if(priv.canBatchCreateStory) actions.push({text: executionLang.batchCreateStory, url: $.createLink('story', 'batchcreate', 'productID=' + productID + '&branch=0&moduleID=0&storyID=0&executionID=' + executionID), 'data-toggle': 'modal', 'data-size': 'lg'});
        if(priv.canLinkStory)        actions.push({text: executionLang.linkStory, url: $.createLink('execution', 'linkStory', 'executionID=' + executionID), 'data-toggle': 'modal', 'data-size': 'lg'});
        if(priv.canLinkStoryByPlan)  actions.push({text: executionLang.linkStoryByPlan, url: '#linkStoryByPlan', 'data-toggle': 'modal'});
    }
    else if(col.type == 'unconfirmed')
    {
        if(priv.canCreateBug) actions.push({text: bugLang.create, url: $.createLink('bug', 'create', 'productID=0&moduleID=0&extra=executionID=' + executionID), 'data-toggle': 'modal', 'data-size': 'lg'});
        if(priv.canBatchCreateBug)
        {
            if(productNum > 1) actions.push({text: bugLang.batchCreate, url: '#batchCreateBug', 'data-toggle': 'modal', 'data-size': 'lg'});
            else actions.push({text: bugLang.batchCreate, url: $.createLink('bug', 'batchcreate', 'productID=' + productID + '&moduleID=0&executionID=' + executionID), 'data-toggle': 'modal', 'data-size': 'lg'});
        }
    }
    else if(col.type == 'wait')
    {
        if(priv.canCreateTask)                actions.push({text: taskLang.create, url: $.createLink('task', 'create', 'executionID=' + executionID), 'data-toggle': 'modal', 'data-size': 'lg'});
        if(priv.canBatchCreateTask)           actions.push({text: taskLang.batchCreate, url: $.createLink('task', 'batchcreate', 'executionID=' + executionID), 'data-toggle': 'modal', 'data-size': 'lg'});
        if(priv.canImportBug && canImportBug) actions.push({text: executionLang.importBug, url: $.createLink('execution', 'importBug', 'executionID=' + executionID), 'data-toggle': 'modal', 'data-size': 'lg'});
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

    if(priv.canEditName && col.actionList.includes('setColumn')) actions.push({text: kanbanLang.setColumn, url: $.createLink('kanban', 'setColumn', `columnID=${col.id}&executionID=${executionID}&from=RDKanban`), 'data-toggle': 'modal', 'icon': 'edit'});
    if(priv.canSetWIP   && col.actionList.includes('setWIP'))    actions.push({text: kanbanLang.setWIP, url: $.createLink('kanban', 'setWIP', `columnID=${col.id}&executionID=${executionID}&from=RDKanban`), 'data-toggle': 'modal', 'icon': 'alert'});

    return actions;
}

/**
 * 渲染卡片内容。
 * Render card content.
 */
window.getItem = function(info)
{
    let begin       = info.item.begin;
    let end         = info.item.end;
    let beginAndEnd = '';
    let assignLink  = '';
    let assignedTo  = '';
    let avatar      = "<span class='avatar rounded-full size-xs ml-1 bg-lighter text-canvas' title='" + noAssigned + "'><i class='icon icon-person'></i></span>";

    if(begin < '1970-01-01' && end > '1970-01-01')
    {
        beginAndEnd = end + ' ' + cardLang.deadlineAB;
    }
    else if(end < '1970-01-01' && begin > '1970-01-01')
    {
        beginAndEnd = begin + ' ' + cardLang.beginAB;
    }
    else if(begin > '1970-01-01' && end > '1970-01-01')
    {
        beginAndEnd = formatDate(begin) + ' ~ ' + formatDate(end);
    }

    if(info.item.assignedTo && typeof userList[info.item.assignedTo] != 'undefined')
    {
        user       = userList[info.item.assignedTo];
        assignedTo = user.realname;
        userAvatar = user.avatar ? "<img src='" + user.avatar + "' />" : assignedTo.substr(0, 1).toUpperCase();
        avatar     = "<span class='avatar rounded-full size-xs ml-1 primary' title=" + assignedTo + '>' + userAvatar + '</span>';
    }

    if(info.laneInfo.type == 'story' && priv.canAssignStory) assignLink = $.createLink('story', 'assignto', 'id=' + info.item.id + '&kanbanGroup=default&from=taskkanban');
    if(info.laneInfo.type == 'task' && priv.canAssignTask)   assignLink = $.createLink('task', 'assignto', 'executionID=' + executionID + '&id=' + info.item.id + '&kanbanGroup=default&from=taskkanban');
    if(info.laneInfo.type == 'bug' && priv.canAssignBug)     assignLink = $.createLink('bug', 'assignto', 'id=' + info.item.id + '&kanbanGroup=default&from=taskkanban');
    if(assignLink) avatar = "<a href='" + assignLink + "' data-toggle='modal'>" + avatar + "</a>";

    const content = `
      <div class='flex items-center'>
        <span class='text-gray mr-1'>#${info.item.id}</span>
        <span class='pri-${info.item.pri}'>${info.item.pri}</span>
        <span class='date ml-1'>${beginAndEnd}</span>
        <div class='flex-1 flex justify-end'>${avatar}</div>
      </div>
    `;

    if(searchValue != '')
    {
        info.item.title = info.item.title.replaceAll(searchValue, "<span class='text-danger'>" + searchValue + "</span>");
        info.item.title = {html: info.item.title};
    }
    info.item.titleUrl   = $.createLink(info.laneInfo.type, 'view', `id=${info.item.id}`);
    info.item.titleAttrs = {'data-toggle': 'modal', 'data-size' : 'lg', 'title' : info.item.title};

    info.item.content = {html: content};
    if(info.item.color && info.item.color != '#fff') info.item.className = 'color-' + info.item.color.replace('#', '');
}

window.getItemActions = function(item)
{
    return [{
        type: 'dropdown',
        icon: 'ellipsis-v',
        caret: false,
        items: buildCardActions(item),
    }];
}

window.buildCardActions = function(item)
{
    let actions = [];

    item.actionList.forEach(action =>
    {
        actionMap = {'text': action.label, 'icon': action.icon, 'url': action.url};
        if(typeof(action.confirm) != 'undefined') actionMap['data-confirm'] = action.confirm;
        if(typeof(action.modal) != 'undefined')   actionMap['data-toggle'] = 'modal';
        if(typeof(action.size) != 'undefined')    actionMap['data-size']   = action.size;
        if(typeof(action.modal) == 'undefined')   actionMap['innerClass'] = 'ajax-submit';
        actions.push(actionMap);
    });
    return actions;
}

window.canDrop = function(dragInfo, dropInfo)
{
    if(!dragInfo) return false;

    const fromColumn  = this.getCol(dragInfo.col);
    const toColumn    = this.getCol(dropInfo.col);
    const lane        = this.getLane(dropInfo.lane);
    const laneType    = lanePairs[lane.id];
    const fromColType = colPairs[fromColumn.id];
    const toColType   = colPairs[toColumn.id];
    if(!fromColumn || !lane) return false;

    if(priv.canSortCards && dropInfo.type == 'item' && (dropInfo.col != dragInfo.item.col || dropInfo.lane != dragInfo.item.lane)) return false;
    if(!priv.canSortCards && dropInfo.type == 'item') return false;

    /* 卡片可在同组内拖动。 */
    if(dragInfo.lane != dropInfo.lane) return false;
    if(dragInfo.item.group != toColumn.group) return false;

    if(dropInfo.type != 'item')
    {
        let kanbanRules = kanbanDropRules[laneType];
        let colRules    = typeof kanbanRules[fromColType] == 'undefined' ? null : kanbanRules[fromColType];
        if(!colRules) return false;
        if(!colRules.includes(toColType)) return false;
    }
}

window.onDrop = function(changes, dropInfo)
{
    if(!dropInfo) return false;

    const toLaneID    = dropInfo.drop.lane;
    const toColumn    = this.getCol(dropInfo.drop.col);
    const laneType    = lanePairs[dropInfo.drag.lane];
    const fromColID   = dropInfo.drag.col;
    const toColID     = dropInfo.drop.col;
    const fromColType = colPairs[fromColID];
    const toColType   = colPairs[toColID];
    const item        = dropInfo.drag.item;
    const objectID    = item.id;

    let link     = '';
    let moveCard = false;
    let laneID   = toLaneID;
    if(typeof toColumn.laneName != 'undefined') laneID = toColumn.laneName;

    if(item.col == toColID && item.lane == toLaneID && priv.canSortCards)
    {
        link = $.createLink('kanban', 'sortCard', 'kanbanID=' + executionID + '&laneID=' + laneID + '&columnID=' + toColID + '&cards=' + dropInfo['data']['list'].join(','));
        $.get(link, function(){refreshKanban()})
        return true;
    }

    /* Task lane. */
    if(laneType == 'task')
    {
        if(toColType == 'developed' && (fromColType == 'developing' || fromColType == 'wait') && priv.canFinishTask) link = $.createLink('task', 'finish', 'taskID=' + objectID + '&extra=from=taskkanban');
        if(toColType == 'pause' && fromColType == 'developing' && priv.canPauseTask) link = $.createLink('task', 'pause', 'taskID=' + objectID + '&extra=from=taskkanban');
        if(toColType == 'canceled' && (fromColType == 'developing' || fromColType == 'wait' || fromColType == 'pause') && priv.canCancelTask) link = createLink('task', 'cancel', 'taskID=' + objectID + '&cardPosition=&from=taskkanban');
        if(toColType == 'closed' && (fromColType == 'developed' || fromColType == 'canceled') && priv.canCloseTask) link = createLink('task', 'close', 'taskID=' + objectID + '&extra=from=taskkanban');
        if(toColType == 'developing')
        {
            if((fromColType == 'canceled' || fromColType == 'closed' || fromColType == 'developed') && priv.canActivateTask) link = $.createLink('task', 'activate', 'taskID=' + objectID + '&extra=&from=taskkanban');
            if(fromColType == 'pause' && priv.canActivateTask) link = $.createLink('task', 'restart', 'taskID=' + objectID + '&from=taskkanban');
            if(fromColType == 'wait' && priv.canStartTask) link = $.createLink('task', 'start', 'taskID=' + objectID + '&extra=from=taskkanban');
        }
    }

    if(laneType == 'bug')
    {
        if(toColType == 'confirmed' && fromColType == 'unconfirmed' && priv.canConfirmBug) link = $.createLink('bug', 'confirm', 'bugID=' + objectID + '&extra=&from=taskkanban');
        if(toColType == 'fixed' && (fromColType == 'fixing' || fromColType == 'confirmed' || fromColType == 'unconfirmed') && priv.canResolveBug) link = $.createLink('bug', 'resolve', 'bugID=' + objectID + '&extra=&from=taskkanban');
        if(toColType == 'closed' && (fromColType == 'testing' || fromColType == 'tested') && priv.canCloseBug) link = $.createLink('bug', 'close', 'bugID=' + objectID + '&extra=&from=taskkanban');
        if(toColType == 'testing' && fromColType == 'fixed') moveCard = true;
        if(toColType == 'tested' && (fromColType == 'fixed' || fromColType == 'testing')) moveCard = true;
        if(toColType == 'fixing')
        {
            if(fromColType == 'confirmed' || fromColType == 'unconfirmed') moveCard = true;
            if((fromColType == 'closed' || fromColType == 'fixed' || fromColType == 'testing' || fromColType == 'tested') && priv.canActivateBug) link = $.createLink('bug', 'activate', 'bugID=' + objectID);
        }

        if(moveCard)
        {
            link = $.createLink('kanban', 'ajaxMoveCard', 'cardID=' + objectID + '&fromColID=' + fromColID + '&toColID=' + toColID + '&fromLaneID=' + laneID + '&toLaneID=' + laneID + '&execitionID=' + executionID + '&browseType=' + browseType + '&groupBy=' + groupBy);
            refreshKanban(link);
            return true;
        }
    }

    if(laneType == 'story')
    {
        if(toColType == 'closed' && priv.canCloseStory) link = $.createLink('story', 'close', 'storyID=' + objectID + '&from=taskkanban');
        if(toColType == 'ready')
        {
            if(item.status == 'draft' || item.status == 'changing' || item.status == 'reviewing')
            {
                zui.Modal.alert(executionLang.storyDragError);
                return false;
            }
        }
        if(!link)
        {
            ajaxMoveCard(objectID, fromColID, toColID, laneID, laneID);
            return true;
        }
    }

    if(link) zui.Modal.open({url: link});
    return false;
}

function formatDate(inputDate)
{
    const date = new Date(inputDate);
    const formattedDate = `${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getDate().toString().padStart(2, '0')}`;

    return formattedDate;
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

/**
 * AJAX: move card.
 *
 * @param  int $objectID
 * @param  int $fromColID
 * @param  int $toColID
 * @param  int $fromLaneID
 * @param  int $toLaneID
 * @access public
 * @return void
 */
window.ajaxMoveCard = function(objectID, fromColID, toColID, fromLaneID, toLaneID)
{
    var link = $.createLink('kanban', 'ajaxMoveCard', 'cardID=' + objectID + '&fromColID=' + fromColID + '&toColID=' + toColID + '&fromLaneID=' + fromLaneID + '&toLaneID=' + toLaneID + '&execitionID=' + executionID + '&browseType=' + browseType + '&groupBy=' + groupBy);
    refreshKanban(link);
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

$('#taskKanbanSearchInput').on('input', debounce(function(){
      searchCards($(this).val());
}, 500));

window.searchCards = function(value, order)
{
    searchValue = value;
    if(typeof order == 'undefined') order = orderBy;
    refreshKanban($.createLink('execution', 'ajaxUpdateKanban', "executionID=" + executionID + "&entertime=0&browseType=" + browseType + "&groupBy=" + groupBy + '&from=taskkanban&searchValue=' + value + '&orderBy=' + order));
};

window.refreshKanban = function(url)
{
    if(typeof url == 'undefined') url = $.createLink('execution', 'ajaxUpdateKanban', "executionID=" + executionID + "&entertime=0&browseType=" + browseType + "&groupBy=" + groupBy + '&from=taskkanban&searchValue=&orderBy=' + orderBy);

    const $kanbanList = $('[data-zui-kanbanlist]').zui('kanbanList');
    let   options     = $kanbanList.options;
    $.getJSON(url, function(data)
    {
        for(const group of data)
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
        options.items = data;
        $kanbanList.render(options);
    });
};

waitDom('.c-group .picker-box .picker-single-selection', function(){this.html(kanbanLang.laneGroup + ': ' + this.html());});
