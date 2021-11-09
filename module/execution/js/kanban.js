function changeView(view)
{
    var link = createLink('execution', 'kanban', "executionID=" + executionID + '&type=' + view);
    location.href = link;
}

/**
 * Render user avatar
 * @param {String|{account: string, avatar: string}} user User account or user object
 * @returns {string}
 */
function renderUserAvatar(user, objectType, objectID)
{
    if(typeof user === 'string') user = {account: user};
    if(!user.avatar && window.userList && window.userList[user.account]) user = window.userList[user.account];

    var $noPrivAvatar = $('<div class="avatar has-text avatar-sm avatar-circle" />').avatar({user: user});
    if(objectType == 'task')
    {
        if(!priv.canAssignTask) return $noPrivAvatar;
        var link = createLink('task', 'assignto', 'executionID=' + executionID + '&id=' + objectID, '', true);
    }
    else if(objectType == 'story')
    {
        if(!priv.canAssignStory) return $noPrivAvatar;
        var link = createLink('story', 'assignto', 'id=' + objectID, '', true);
    }
    else if(objectType == 'bug')
    {
        if(!priv.canAssignBug) return $noPrivAvatar;
        var link = createLink('bug', 'assignto', 'id=' + objectID, '', true);
    }

    return $('<a class="avatar has-text avatar-sm avatar-circle iframe" href="' + link + '"/>').avatar({user: user});
}

/**
 * Render deadline
 * @param {String|Date} deadline Deadline
 * @returns {JQuery}
 */
function renderDeadline(deadline)
{
    if(deadline == '0000-00-00') return;

    var date = $.zui.createDate(deadline);
    var now  = new Date();
    now.setHours(0);
    now.setMinutes(0);
    now.setSeconds(0);
    now.setMilliseconds(0);
    var isEarlyThanToday = date.getTime() < now.getTime();
    var deadlineDate     = $.zui.formatDate(date, 'MM-dd');

    return $('<span class="info info-deadline"/>').text(deadlineLang + ' ' + deadlineDate).addClass(isEarlyThanToday ? 'text-red' : 'text-muted');
}

/**
 * Render story item  提供方法渲染看板中的需求卡片
 * @param {Object} item  Story item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderStoryItem(item, $item, col)
{
    var $title = $item.find('.title');
    if(!$title.length)
    {
        $title = $('<a class="title iframe"><i class="icon icon-lightbulb text-muted"></i> <span class="text"></span></a>')
                .attr('href', $.createLink('story', 'view', 'storyID=' + item.id, '', true));
        $title.appendTo($item);
    }
    $title.attr('title', item.title).find('.text').text(item.title);

    var $infos = $item.find('.infos');
    if(!$infos.length)
    {
        $infos = $('<div class="infos"></div>').appendTo($item);
    }
    $infos.html(
    [
        '<span class="info info-id text-muted">#' + item.id + '</span>',
        '<span class="info info-pri label-pri label-pri-' + item.pri + '" title="' + item.pri + '">' + item.pri + '</span>',
        item.estimate ? '<span class="info info-estimate text-muted">' + item.estimate + 'h</span>' : '',
    ].join(''));
    if(item.assignedTo) $infos.append(renderUserAvatar(item.assignedTo, 'story', item.id));

    var $actions = $item.find('.actions');
    if(!$actions.length && item.menus.length)
    {
        $actions = $([
            '<div class="actions">',
                '<a data-contextmenu="story" data-col="' + col.type + '">',
                    '<i class="icon icon-ellipsis-v"></i>',
                '</a>',
            '</div>'
        ].join('')).appendTo($item);
    }

    $item.attr('data-type', 'story').addClass('kanban-item-story');

    return $item;
}

/**
 * Render bug item  提供方法渲染看板中的 Bug 卡片
 * @param {Object} item  Bug item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderBugItem(item, $item, col)
{
    var $title = $item.find('.title');
    if(!$title.length)
    {
        $title = $('<a class="title iframe"><i class="icon icon-bug text-muted"></i> <span class="text"></span></a>')
                .attr('href', $.createLink('bug', 'view', 'bugID=' + item.id, '', true));
        $title.appendTo($item);
    }
    $title.attr('title', item.title).find('.text').text(item.title);

    var $infos = $item.find('.infos');
    if(!$infos.length)
    {
        $infos = $('<div class="infos"></div>').appendTo($item);
    }
    $infos.html(
    [
        '<span class="info info-id text-muted">#' + item.id + '</span>',
        '<span class="info info-severity label-severity" data-severity="' + item.severity + '" title="' + item.severity + '"></span>',
        '<span class="info info-pri label-pri label-pri-' + item.pri + '" title="' + item.pri + '">' + item.pri + '</span>',
    ].join(''));
    if(item.deadline) $infos.append(renderDeadline(item.deadline));
    if(item.assignedTo) $infos.append(renderUserAvatar(item.assignedTo, 'bug', item.id));

    var $actions = $item.find('.actions');
    if(!$actions.length && item.menus.length)
    {
        $actions = $([
            '<div class="actions">',
                '<a data-contextmenu="bug" data-col="' + col.type + '">',
                    '<i class="icon icon-ellipsis-v"></i>',
                '</a>',
            '</div>'
        ].join('')).appendTo($item);
    }

    $item.attr('data-type', 'bug').addClass('kanban-item-bug');

    return $item;
}

/**
 * Render task item  提供方法渲染看板中的任务卡片
 * @param {Object} item  Task item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderTaskItem(item, $item, col)
{
    var $title = $item.find('.title');
    if(!$title.length)
    {
        $title = $('<a class="title iframe"><i class="icon icon-checked text-muted"></i> <span class="text"></span></a>')
                .attr('href', $.createLink('task', 'view', 'taskID=' + item.id, '', true));
        $title.appendTo($item);
    }
    $title.attr('title', item.name).find('.text').text(item.name);

    var $infos = $item.find('.infos');
    if(!$infos.length)
    {
        $infos = $('<div class="infos"></div>').appendTo($item);
    }
    $infos.html(
    [
        '<span class="info info-id text-muted">#' + item.id + '</span>',
        '<span class="info i nfo-pri label-pri label-pri-' + item.pri + '" title="' + item.pri + '">' + item.pri + '</span>',
        item.estimate ? '<span class="info info-estimate text-muted">' + item.estimate + 'h</span>' : '',
    ].join(''));
    if(item.deadline) $infos.append(renderDeadline(item.deadline));
    if(item.assignedTo) $infos.append(renderUserAvatar(item.assignedTo, 'task', item.id));

    var $actions = $item.find('.actions');
    if(!$actions.length && item.menus.length)
    {
        $actions = $([
            '<div class="actions">',
                '<a data-contextmenu="task" data-col="' + col.type + '">',
                    '<i class="icon icon-ellipsis-v"></i>',
                '</a>',
            '</div>'
        ].join('')).appendTo($item);
    }

    $item.attr('data-type', 'task').addClass('kanban-item-task');

    return $item;
}

/* Add column renderer/  添加特定列类型或列条目类型渲染方法 */
addColumnRenderer('story', renderStoryItem);
addColumnRenderer('bug',   renderBugItem);
addColumnRenderer('task',  renderTaskItem);

/**
 * Render column count 渲染看板列头上的卡片数目
 * @param {JQuery} $count Kanban count element
 * @param {number} count  Column cards count
 * @param {number} col    Column object
 * @param {Object} kanban Kanban intance
 */
function renderColumnCount($count, count, col)
{
    var text = count + '/' + (col.limit < 0 ? '<i class="icon icon-infinite"></i>' : col.limit);
    $count.html(text + '<i class="icon icon-arrow-up"></i>');
}

/**
 * Render header column 渲染看板列头部
 * @param {JQuery} $col    Header column element
 * @param {Object} col     Header column object
 * @param {JQuery} $header Header element
 * @param {Object} kanban  Kanban object
 */
function renderHeaderCol($col, col, $header, kanban)
{
    if(col.asParent) $col = $col.children('.kanban-header-col');
    if(!$col.children('.actions').length)
    {
        var $actions = $('<div class="actions" />');
        var printStoryButton =  printTaskButton = printBugButton = false;
        if(priv.canCreateStory || priv.canBatchCreateStory || priv.canLinkStory || priv.canLinkStoryByPlane) printStoryButton = true;
        if(priv.canCreateTask  || priv.canBatchCreateTask) printTaskButton = true;
        if(priv.canCreateBug   || priv.canBatchCreateBug)  printBugButton  = true;

        if((col.type === 'backlog' && printStoryButton) || (col.type === 'wait' && printTaskButton) || (col.type == 'unconfirmed' && printBugButton))
        {
            $actions.append([
                '<a data-contextmenu="columnCreate" data-type="' + col.type + '" data-kanban="' + kanban.id + '" data-parent="' + (col.parentType || '') +  '" class="text-primary">',
                    '<i class="icon icon-expand-alt"></i>',
                '</a>'
            ].join(''));
        }

        $actions.append([
            '<a data-contextmenu="column" title="' + kanbanLang.moreAction + '" data-type="' + col.type + '" data-kanban="' + kanban.id + '" data-parent="' + (col.parentType || '') +  '">',
                '<i class="icon icon-ellipsis-v"></i>',
            '</a>'
        ].join(''));
        $actions.appendTo($col);
    }
}

/**
 * Render lane name 渲染看板泳道名称
 * @param {JQuery} $name    Name element
 * @param {Object} lane     Lane object
 * @param {JQuery} $kanban  $kanban element
 * @param {Object} columns  Kanban columns
 * @param {Object} kanban   Kanban object
 */
function renderLaneName($name, lane, $kanban, columns, kanban)
{
    if(lane.id != 'story' && lane.id != 'task' && lane.id != 'bug') return false;
    if(!$name.children('.actions').length && (priv.canSetLane || priv.canMoveLane))
    {
        $([
            '<div class="actions" title="' + kanbanLang.moreAction + '">',
                '<a data-contextmenu="lane" data-lane="' + lane.id + '" data-kanban="' + kanban.id + '">',
                    '<i class="icon icon-ellipsis-v"></i>',
                '</a>',
            '</div>'
        ].join('')).appendTo($name);
    }
}

/**
 * Updata kanban data
 * 更新看板上的数据
 * @param {string} kanbanID Kanban id   看板 ID
 * @param {Object} data     Kanban data 看板数据
 */
function updateKanban(kanbanID, data)
{
    var $kanban = $('#kanban-' + kanbanID);
    if(!$kanban.length) return;

    $kanban.data('zui.kanban').render(data);
}

/**
 * Create kanban in page
 * 在界面上创建一个看板界面
 * @param {string} kanbanID Kanban id      看板 ID
 * @param {Object} data     Kanban data    看板数据
 * @param {Object} options  Kanban options 组件初始化数据 看板名称
 */
function createKanban(kanbanID, data, options)
{
    var $kanban = $('#kanban-' + kanbanID);
    if($kanban.length) return updateKanban(kanbanID, data);

    $kanban = $('<div id="kanban-' + kanbanID + '" data-id="' + kanbanID + '"></div>').appendTo('#kanbans');
    $kanban.kanban($.extend({data: data}, options));
}

function fullScreen()
{
    var element       = document.getElementById('kanbanContainer');
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullScreen;
    if(requestMethod)
    {
        var afterEnterFullscreen = function()
        {
            $('#kanbanContainer').addClass('scrollbar-hover');
            $('.actions').hide();
            $('#kanbanContainer a.iframe').each(function()
            {
                if($(this).hasClass('iframe'))
                {
                    var href = $(this).attr('href');
                    $(this).removeClass('iframe');
                    $(this).attr('href', 'javascript:void(0)');
                    $(this).attr('href-bak', href);
                }
            })
            $.cookie('isFullScreen', 1);
        }

        var whenFailEnterFullscreen = function()
        {
            exitFullScreen();
        }

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
    $('#kanbanContainer').removeClass('scrollbar-hover');
    $('.actions').show();
    $('#kanbanContainer a').each(function()
    {
        var hrefBak = $(this).attr('href-bak');
        if(hrefBak)
        {
            $(this).addClass('iframe');
            $(this).attr('href', hrefBak);
        }
    })
    $.cookie('isFullScreen', 0);
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

/* Define drag and drop rules */
if(!window.kanbanDropRules)
{
    window.kanbanDropRules =
    {
        story:
        {
            blacklog: true,
            ready: ['blacklog', 'dev-doing'],
            'dev-doing': ['dev-done'],
            'dev-done': ['test-doing'],
            'test-doing': ['test-done'],
            'test-done': ['accepted'],
            'accepted': ['published'],
            'published': false,
        }
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
    var kanbanID    = $root.data('id');
    var kanbanRules = window.kanbanDropRules ? window.kanbanDropRules[kanbanID] : null;

    if(!kanbanRules) return $root.find('.kanban-lane-col:not([data-type="' + col.type + '"])');

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
        var canDropHere = colRules.indexOf(newCol.type) > -1 && newLane.id === lane.id;
        if(canDropHere) $newCol.addClass('can-drop-here');
        return canDropHere;
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

    $('#kanbans').find('.can-drop-here').removeClass('can-drop-here');
}

/**
 * Handle sort cards in column 处理对列卡片进行排序
 */
function handleSortColCards()
{
    /* TODO: handle sort cards from column contextmenu */
    return false;
}

/**
 * Create column menu  创建列操作菜单
 * @returns {Object[]}
 */
function createColumnMenu(options)
{
    var $col     = options.$trigger.closest('.kanban-col');
    var col      = $col.data('col');
    var kanbanID = options.kanban;

	var items = [];
	if(priv.canEditName) items.push({label: executionLang.editName, url: $.createLink('kanban', 'setColumn', 'col=' + col.columnID + '&executionID=' + executionID), className: 'iframe', attrs: {'data-width': '500px'}})
	if(priv.canSetWIP) items.push({label: executionLang.setWIP, url: $.createLink('kanban', 'setWIP', 'col=' + col.columnID + '&executionID=' + executionID), className: 'iframe', attrs: {'data-width': '500px'}})
	if(priv.canSortCards) items.push({label: executionLang.sortColumn, items: ['按ID倒序', '按ID顺序'], className: 'iframe', onClick: handleSortColCards})
    return items;
}

/**
 * Create column create button menu  创建列添加按钮操作菜单
 * @returns {Object[]}
 */
function createColumnCreateMenu(options)
{
    var $col  = options.$trigger.closest('.kanban-col');
    var col   = $col.data('col');
    var items = [];

    if(col.laneType == 'story')
    {
        if(priv.canCreateStory) items.push({label: storyLang.create, url: $.createLink('story', 'create', 'productID=' + productID, '', true), className: 'iframe'});
        if(priv.canBatchCreateStory) items.push({label: storyLang.batchCreate, url: $.createLink('story', 'batchcreate', 'productID=' + productID + '&branch=0&moduleID=0&storyID=0&executionID=' + executionID, '', true), className: 'iframe'});
        if(priv.canLinkStory) items.push({label: executionLang.linkStory, url: $.createLink('execution', 'linkStory', 'executionID=' + executionID, '', true), className: 'iframe', attrs: {'data-width': '90%'}});
        if(priv.canLinkStoryByPlane) items.push({label: executionLang.linkStoryByPlan, url: '#linkStoryByPlan', 'attrs' : {'data-toggle': 'modal'}});
    }
    else if(col.laneType == 'bug')
    {
        if(priv.canCreateBug) items.push({label: bugLang.create, url: $.createLink('bug', 'create', 'productID=0&moduleID=0&extra=executionID=' + executionID, '', true), className: 'iframe'});
        if(priv.canBatchCreateBug) items.push({label: bugLang.batchCreate, url: $.createLink('bug', 'batchcreate', 'productID=' + productID + '&moduleID=0&executionID=' + executionID, '', true), className: 'iframe'});
    }
    else
    {
        if(priv.canCreateTask) items.push({label: taskLang.create, url: $.createLink('task', 'create', 'executionID=' + executionID, '', true), className: 'iframe'});
        if(priv.canBatchCreateTask) items.push({label: taskLang.batchCreate, url: $.createLink('task', 'batchcreate', 'executionID=' + executionID, '', true), className: 'iframe'});
    }
    return items;
}

/**
 * Create lane menu  创建泳道操作菜单
 * @returns {Object[]}
 */
function createLaneMenu(options)
{
    var $lane            = options.$trigger.closest('.kanban-lane');
    var $kanban          = $lane.closest('.kanban');
    var lane             = $lane.data('lane');
    var kanbanID         = options.kanban;
    var upTargetKanban   = $kanban.prev('.kanban').length ? $kanban.prev('.kanban').data('id') : '';
    var downTargetKanban = $kanban.next('.kanban').length ? $kanban.next('.kanban').data('id') : '';

    var items = [];
    if(priv.canSetLane)  items.push({label: kanbanLang.setLane, icon: 'edit', url: $.createLink('kanban', 'setLane', 'lane=' + lane.laneID + '&executionID=' + executionID), className: 'iframe'});
    if(priv.canMoveLane) items.push(
        {label: kanbanLang.moveUp, icon: 'arrow-up', url: $.createLink('kanban', 'laneMove', 'executionID=' + executionID + '&currentLane=' + lane.id + '&targetLane=' + upTargetKanban), className: 'iframe', disabled: !$kanban.prev('.kanban').length},
        {label: kanbanLang.moveDown, icon: 'arrow-down', url: $.createLink('kanban', 'laneMove', 'executionID=' + executionID + '&currentLane=' + lane.id + '&targetLane=' + downTargetKanban), className: 'iframe', disabled: !$kanban.next('.kanban').length}
    );

    var bounds = options.$trigger[0].getBoundingClientRect();
    items.$options = {x: bounds.right, y: bounds.top};
    return items;
}

/**
 * Create story menu  创建需求卡片操作菜单
 * @returns {Object[]}
 */
function createStoryMenu(options)
{
    var $card = options.$trigger.closest('.kanban-item');
    var story = $card.data('item');

    var items = [];
    $.each(story.menus, function()
    {
        var item = {label: this.label, icon: this.icon, url: this.url, attrs: {'data-toggle': 'modal', 'data-type': 'iframe'}};
        if(this.size) item.attrs['data-width'] = this.size;

        if(this.icon == 'unlink') item = {label: this.label, icon: this.icon, url: this.url, attrs: {'target': 'hiddenwin'}};
        items.push(item);
    });

    return items;
}

/**
 * Create bug menu  创建 Bug 卡片操作菜单
 * @returns {Object[]}
 */
function createBugMenu(options)
{
    var $card = options.$trigger.closest('.kanban-item');
    var bug   = $card.data('item');

    var items = [];
    $.each(bug.menus, function()
    {
        var item = {label: this.label, icon: this.icon, url: this.url, attrs: {'data-toggle': 'modal', 'data-type': 'iframe'}};
        if(this.size) item.attrs['data-width'] = this.size;

        items.push(item);
    });

    return items;
}

 /**
 * Create task menu  创建任务卡片操作菜单
 * @returns {Object[]}
 */
function createTaskMenu(options)
{
    var $card = options.$trigger.closest('.kanban-item');
    var task  = $card.data('item');

    var items = [];
    $.each(task.menus, function()
    {
        var item = {label: this.label, icon: this.icon, url: this.url, attrs: {'data-toggle': 'modal', 'data-type': 'iframe'}};
        if(this.size) item.attrs['data-width'] = this.size;

        items.push(item);
    });

    return items;
}

/** Resize kanban container size */
function resizeKanbanContainer()
{
    var $container = $('#kanbanContainer');
    var maxHeight = window.innerHeight - 98 - 15;
    $container.children('.panel-body').css('max-height', maxHeight);
}

/* Define menu creators */
window.menuCreators =
{
    column:       createColumnMenu,
    columnCreate: createColumnCreateMenu,
    lane:         createLaneMenu,
    story:        createStoryMenu,
    bug:          createBugMenu,
    task:         createTaskMenu,
};

/* Set kanban affix container */
window.kanbanAffixContainer = '#kanbanContainer>.panel-body';

/* Overload kanban default options */
$.extend($.fn.kanban.Constructor.DEFAULTS,
{
    onRender: function()
    {
        var maxWidth = 0;
        $('#kanbans .kanban-board').each(function()
        {
            maxWidth = Math.max(maxWidth, $(this).outerWidth());
        });
        $('#kanbans').css('min-width', maxWidth);
    }
});

/* Example code: */
$(function()
{
    /* Common options 用于初始化看板的通用选项 */　
    var commonOptions =
    {
        maxColHeight:   'auto',
        minColWidth:     240,
        maxColWidth:     240,
        showCount:       true,
        showZeroCount:   true,
        fluidBoardWidth: true,
        droppable:
        {
            target:       findDropColumns,
            finish:       handleFinishDrop,
            mouseButton: 'left'
        },
        onRenderHeaderCol: renderHeaderCol,
        onRenderLaneName:  renderLaneName,
        onRenderCount:     renderColumnCount
    };

    /* Create kanban 创建看板 */
    if(groupBy == 'default')
    {
        var kanbanLane = '';
        for(var i in kanbanList)
        {
            if(kanbanList[i] == 'story') kanbanLane = kanbanGroup.story;
            if(kanbanList[i] == 'bug')   kanbanLane = kanbanGroup.bug;
            if(kanbanList[i] == 'task')  kanbanLane = kanbanGroup.task;

            if(browseType == kanbanList[i] || browseType == 'all') createKanban(kanbanList[i], kanbanLane, commonOptions);
        }
    }
    else
    {
        /* Create kanban by group. 分泳道创建看板. */
        createKanban(browseType, kanbanGroup[groupBy], commonOptions);
    }

    /* Init iframe modals */
    $(document).on('click', '#kanbans .iframe,.contextmenu-menu .iframe', function(event)
    {
        $(this).modalTrigger({show: true});
        event.preventDefault();
    });

    /* Init contextmenu */
    $('#kanbans').on('click', '[data-contextmenu]', function(event)
    {
        var $trigger    = $(this);
        var menuType    = $trigger.data('contextmenu');

        var menuCreator = window.menuCreators[menuType];
        if(!menuCreator) return;

        var options = $.extend({event, $trigger: $trigger}, $trigger.data());
        var items   = menuCreator(options);
        if(!items || !items.length) return;

        $.zui.ContextMenu.show(items, items.$options || {event: event});
    });

    /* Resize kanban container on window resize */
    resizeKanbanContainer();
    $(window).on('resize', resizeKanbanContainer);

    /* Hide contextmenu when page scroll */
    $(window).on('scroll', function()
    {
        $.zui.ContextMenu.hide();
    });

    $('#toStoryButton').on('click', function()
    {
        var planID = $('#plan').val();
        if(planID)
        {
            location.href = createLink('execution', 'importPlanStories', 'executionID=' + executionID + '&planID=' + planID + '&productID=0&fromMethod=kanban');
        }
    })

    $('#type_chosen .chosen-single span').prepend('<i class="icon-kanban"></i>');
    $('#group_chosen .chosen-single span').prepend(kanbanLang.laneGroup + ': ');
});

$('#type').change(function()
{
    var type = $('#type').val();
    if(type != 'all')
    {
        $('.c-group').show();
        $.get(createLink('execution', 'ajaxGetGroup', 'type=' + type), function(data)
        {
            $('#group_chosen').remove();
            $('#group').replaceWith(data);
            $('#group').chosen();
        })
    }

    var link = createLink('execution', 'kanban', "executionID=" + executionID + '&type=' + type);
    location.href = link;
});

$('.c-group').change(function()
{
    $('.c-group').show();

    var type  = $('#type').val();
    var group = $('#group').val();
    var link  = createLink('execution', 'kanban', 'executionID=' + executionID + '&type=' + type + '&orderBy=order_asc' + '&groupBy=' + group);
    location.href = link;
});
