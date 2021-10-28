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
function renderUserAvatar(user)
{
    if(typeof user === 'string') user = {account: user};
    if(!user.avatar && window.userList && window.userList[user.account]) user = window.userList[user.account];
    return $('<div class="avatar has-text avatar-sm avatar-circle" />').avatar({user: user});
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
                .attr('href', $.createLink('story', 'view', 'storyID=' + item.id));
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
    if(item.assignedTo) $infos.append(renderUserAvatar(item.assignedTo));

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
                .attr('href', $.createLink('bug', 'view', 'bugID=' + item.id));
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
    if(item.assignedTo) $infos.append(renderUserAvatar(item.assignedTo));

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
                .attr('href', $.createLink('task', 'view', 'taskID=' + item.id));
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
    if(item.assignedTo) $infos.append(renderUserAvatar(item.assignedTo));

    $item.attr('data-type', 'task').addClass('kanban-item-task');

    return $item;
}

/* Add column renderer/  添加特定列类型或列条目类型渲染方法 */
/* Add column renderer/  添加特定列类型或列卡片类型渲染方法 */
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
    var text = count + '/' + (!col.limit ? '<i class="icon icon-infinite"></i>' : '');
    $count.html(text + '<i class="icon icon-arrow-up"></i>');
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
    var element = document.getElementById('kanbanContainer');
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullScreen;
    if(requestMethod)
    {
        var afterEnterFullscreen = function()
        {
            $('#kanbanContainer').addClass('scrollbar-hover');
            $.cookie('isFullScreen', 1);
        }

        var whenFailEnterFullscreen = function()
        {
            $.cookie('isFullScreen', 0);
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
    $('#content .actions').removeClass('hidden');
    $.cookie('isFullScreen', 0);
}

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
 * Handle finish drop task
 * @param {Object} event Event object
 * @returns {void}
 */
function handleFinishDrop(event)
{
    var $item = $(event.element); // The drag item
    var $dragCol = $item.closest('.kanban-lane-col');
    var $dropCol = $(event.target);

    /* Get d-n-d(drag and drop) infos  获取拖放操作相关信息 */
    var item = $item.data('item');
    var fromColType = $dragCol.data('type');
    var toColType = $dropCol.data('type');
    var kanbanID = $item.closest('.kanban').data('id');

    /* TODO: Save d-n-d infos to server 将拖放操作信息提交到服务器  */
    console.log('TODO: Save d-n-d infos to server 将拖放操作信息提交到服务器', {item, fromColType, toColType, kanbanID});

    /*
        // TODO: The server must return a updated kanban data  服务器返回更新后的看板数据

        // 调用 updateKanban 更新看板数据
        updateKanban(kanbanID, newKanbanData);
     */

    $('#kanbans').find('.can-drop-here').removeClass('can-drop-here');
}

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
        $('#kanbanContainer').css('min-width', maxWidth + 40);
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
        countRender:     renderColumnCount,
        droppable:
        {
            target:       findDropColumns,
            finish:       handleFinishDrop,
            mouseButton: 'left'
        }
    };

    /* Create story kanban 创建需求看板 */
    if(browseType == 'all' || browseType == 'story') createKanban('story', kanbanGroup.story, commonOptions);

    /* Create bug kanban 创建 Bug 看板 */
    if(browseType == 'all' || browseType == 'bug') createKanban('bug', kanbanGroup.bug, commonOptions);

    /* Create task kanban 创建 任务 看板 */
    if(browseType == 'all' || browseType == 'task') createKanban('task', kanbanGroup.task, commonOptions);
});
