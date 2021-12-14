function loadMore(type)
{
    var method = 'browseArchived' + type;
    var selector = '#more' + type + 's';
    var link = createLink('kanban', method, 'kanbanID=' + kanbanID);
    $(selector).load(link, function()
    {
        var windowHeight = $(window).height();
        $(selector + ' .panel-body').css('height', windowHeight - 100);
        $(selector + ' .avatar').renderAvatar();
        $(selector).animate({right: 0}, 500);
    });
}

/**
 * Display the kanban in full screen.
 *
 * @access public
 * @return void
 */
function fullScreen()
{
    var element       = document.getElementById('kanban');
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullscreen;
    if(requestMethod)
    {
        var afterEnterFullscreen = function()
        {
            $('#mainContent').addClass('scrollbar-hover');
            $.cookie('isFullScreen', 1);
        };

        var whenFailEnterFullscreen = function(error)
        {
            $.cookie('isFullScreen', 0);
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
    $('#mainContent').removeClass('scrollbar-hover');
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

/**
 * Render header of a column.
 */
function renderHeaderCol($column, column, $header, kanbanData)
{
    /* Render group header. */
    var privs       = kanbanData.actions;
    var columnPrivs = kanbanData.columns[0].actions;

    if(privs.includes('sortGroup'))
    {
        var groups = regions[column.region].groups;
        if($header.closest('.kanban').data('zui.kanban'))
        {
            groups = $header.closest('.kanban').data('zui.kanban').data;
        }
        if(groups.length > 1)
        {
            $column.closest('.kanban-board').addClass('sort');
            $column.closest('.kanban-header').find('.kanban-group-header').remove();
            $column.closest('.kanban-header').prepend('<div class="kanban-group-header"><i class="icon icon-md icon-move"></i></div>');
        }
    }

    var regionID     = $column.closest('.kanban').data('id');
    var groupID      = $column.closest('.kanban-board').data('id');
    var laneID       = column.$kanbanData.lanes[0].id ? column.$kanbanData.lanes[0].id : 0;
    var columnID     = $column.closest('.kanban-col').data('id');
    var printMoreBtn = (columnPrivs.includes('editColumn') || columnPrivs.includes('setWIP') || columnPrivs.includes('createColumn') || columnPrivs.includes('copyColumn') || columnPrivs.includes('archiveColumn') || columnPrivs.includes('deleteColumn') || columnPrivs.includes('splitColumn'));

    /* Render more menu. */
    if(columnPrivs.includes('createCard') || printMoreBtn)
    {
        var addItemBtn = '';
        var moreAction = '';

        if(!$column.children('.actions').length) $column.append('<div class="actions"></div>');
        var $actions = $column.children('.actions');

        if(columnPrivs.includes('createCard'))
        {
            var cardUrl = createLink('kanban', 'createCard', 'kanbanID=' + kanbanID + '&regionID=' + regionID + '&groupID=' + groupID + '&laneID=' + laneID + '&columnID=' + columnID);
            addItemBtn  = ['<a data-contextmenu="columnCreate" data-toggle="modal" data-action="addItem" data-column="' + column.id + '" data-lane="' + laneID + '" href="' + cardUrl + '" class="text-primary iframe">', '<i class="icon icon-expand-alt"></i>', '</a>'].join('');
        }

        var moreAction = ' <button class="btn btn-link action"  title="' + kanbanLang.moreAction + '" data-contextmenu="column" data-column="' + column.id + '"><i class="icon icon-ellipsis-v"></i></button>';
        $actions.html(addItemBtn + moreAction);
    }
}

/**
 * Render items count of a column.
 */
function renderCount($count, count, column)
{
    /* Render WIP. */
    var limit = !column.limit || column.limit == '-1' ? '<i class="icon icon-md icon-infinite"></i>' : column.limit;
    if($count.parent().find('.limit').length)
    {
        $count.parent().find('.limit').html(limit);
    }
    else
    {
        $count.parent().find('.count').before("<span class='include-first text-grey'>(</span>");
        $count.parent().find('.count').after("<span class='divider text-grey'>/</span><span class='limit text-grey'>" + limit + "</span><span class='include-last text-grey'>)</span>");
    }

    if(column.limit != -1 && column.limit < count)
    {
        $count.parents('.title').parent('.kanban-header-col').css('background-color', '#F6A1A1');
        $count.parents('.title').find('.text').css('max-width', $count.parents('.title').width() - 200);
        $count.css('color', '#E33030');
        if(!$count.parent().find('.error').length) $count.parent().find('.include-last').after("<span class='error text-grey'>(" + kanbanLang.limitExceeded + ")</span>");
    }
    else
    {
        $count.parents('.title').parent('.kanban-header-col').css('background-color', 'transparent');
        $count.parents('.title').find('.text').css('max-width', $count.parents('.title').width() - 120);
        $count.css('color', '#8B91A2');
        $count.parent().find('.error').remove();
    }
}

function renderLaneName($lane, lane, $kanban, columns, kanban)
{
    var canSet    = lane.actions.includes('setLane');
    var canSort   = lane.actions.includes('sortLane') && kanban.lanes.length > 1;
    var canDelete = lane.actions.includes('deleteLane');

    $lane.parent().toggleClass('sort', canSort);

    if(!$lane.children('.actions').length && (canSet || canDelete))
    {
        $([
          '<div class="actions" title="' + lang.more + '">',
          '<a data-contextmenu="lane" data-lane="' + lane.id + '" data-kanban="' + kanban.id + '">',
          '<i class="icon icon-ellipsis-v"></i>',
          '</a>',
          '</div>'
        ].join('')).appendTo($lane);
    }
}

/**
 * The function for rendering kanban item
 */
function renderKanbanItem(item, $item)
{
    if(item.status == 'done')
    {
        $item.addClass('gray');
    }
    var $title = $item.children('.title');
    if(!$title.length)
    {
        $title = $('<a class="title" data-toggle="modal" data-width="80%"></a>')
            .appendTo($item);
    }
    $title.text(item.name);
    $title.attr('href', createLink('sys.task', 'view', 'taskID=' + item.id + '&from=kanban'));

    var $more = $item.children('.actions');
    if(!$more.length)
    {
        $(
        [
            '<div class="actions" title="' + lang.more + '">',
              '<button class="btn btn-link action" data-contextmenu="task" data-id="' + item.id + '" data-status="' + item.status + '">',
                '<i class="icon icon-ellipsis-v"></i>',
              '</button>',
            '</div>'
        ].join('')).appendTo($item);
    }

    var $info = $item.children('.info');
    if(!$info.length) $info = $(
    [
        '<div class="info">',
            '<span class="pri"></span>',
            '<span class="time label label-light"></span>',
            '<div class="user"></div>',
        '</div>'
    ].join('')).appendTo($item);

    $item.data('task', item);

    $info.children('.pri')
        .attr('class', 'pri label-pri label-pri-' + item.pri)
        .text(item.pri);

    var $time = $info.children('.time');
    if(item.deadline && item.deadline !== '0000-00-00')
    {
        var deadline = $.zui.createDate(item.deadline);
        var today    = $.zui.createDate(today);
        var isExpired = deadline.getTime() < today.getTime();
        var dateFormat = (today.getFullYear() === deadline.getFullYear() ? 'MM-dd ' : 'yyyy-MM-dd ') + kanbanLang.deadline;
        $time.text($.zui.formatDate(deadline, dateFormat))
            .toggleClass('text-red', isExpired)
            .show();
    }
    else
    {
        $time.hide();
    }

    var $user = $info.children('.user');
    if(item.assignedTo)
    {
        var member = members[item.assignedTo] || {account: item.assignedTo};
        var canAssign = item.status !== 'closed' && item.status !== 'cancel';
        var $avatar = $(
            canAssign ? '<a class="avatar avatar-sm avatar-user" data-toggle="modal"></a>'
                : '<div class="avatar avatar-sm avatar-user"></div>'
            ).attr('data-name', member.realname || member.account)
            .attr('data-id', member.id || member.account);
        if(member.avatar)
        {
            $avatar.append('<img src="' + member.avatar + '" />');
        }
        $avatar.renderAvatar();
        if(canAssign)
        {
            $avatar.attr('href', createLink('sys.task', 'assignto', 'taskID=' + item.id + '&fromKanban=1'));
        }

        $user.empty()
            .append($avatar)
            .attr('title', member.realname || member.account)
            .show();
    }
    else
    {
        $user.hide();
    }

    $item.css('background-color', item.color);
    $item.toggleClass('has-color', item.color != '#FFFFFF');
    if(item.color == '#FFFFFF')
    {
        $item.find('.info > .label-light').css('background-color', '#F2F2F2');
    }
    else
    {
        var colors = {'#E01B1B': '#DD5858', '#F7B500': '#ECC046', '#288427': '#609F60'}
        $item.find('.info > .label-light').css('background-color', colors[item.color]);
    }
}

/**
 * Show error message
 * @param {string|object} message Message
 */
function showErrorMessager(message)
{
    var html = false;
    if(message instanceof Error)
    {
        message = message.message;
    }
    else if(typeof message === 'object')
    {
        html = [];
        $.each(message, function(key, msg)
        {
            html.push($.isArray(msg) ? msg.join('') : String(msg));
        });
        message = html.join('<br/>');
    }
    else
    {
        message = String(message);
    }

    if(typeof message === 'string' && message.length)
    {
        $.zui.messager.danger(message, {html: !!html});
    }
}

/**
 * Update kanban
 * @param {string} [regionID] Region ID
 * @param {string} [groupID] Group ID
 * @param {function(Error)} [callback] Callback on completed
 */
function updateRegion(regionID, groupID, callback)
{
    if(!regionID) return false;
    if(typeof groupID == 'function')
    {
        callback = groupID;
        groupID = 0;
    }

    var url = createLink('kanban', 'ajaxGetData', 'kanbanID=' + kanbanID + '&regionID=' + regionID + '&group=' + (groupID || 0));
    $.ajax(
    {
        method:   'get',
        dataType: 'json',
        url:      url,
        success: function(response)
        {
            var kanban = $('#kanban' + regionID).data('zui.kanban');
            if(groupID) kanban.renderKanban(response.data);
            else kanban.render(response.data);
            typeof callback === 'function' && callback();
        },
        error: function(xhr, status, error)
        {
            showErrorMessager(error || lang.timeout);
            typeof callback === 'function' && callback(error);
        }
    });
}

/**
 * Hide kanban action
 */
function hideKanbanAction()
{
    $('.kanban').attr('data-action-enabled', null);
    $('.contextmenu').removeClass('contextmenu-show');
    $('.contextmenu .contextmenu-menu').removeClass('open').removeClass('in');
    $('#moreTasks, #moreColumns').animate({right: -400}, 500);
}

/**
 * Open form for adding task
 * @param {JQuery} $element Trigger element
 */
function openAddTaskForm($element)
{
    var regionID = $element.closest('.kanban').data('id');
    var groupID  = $element.closest('.kanban-board').data('id');
    var laneID   = $element.closest('.kanban-lane').data('id');
    var columnID = $element.closest('.kanban-col').data('id');
    var status   = $element.closest('.kanban-col').data('type');
    var modalUrl = createLink('kanban', 'createCard', 'kanbanID=' + kanbanID + '&regionID=' + regionID + '&groupID=' + groupID + '&laneID=' + laneID + '&columnID=' + columnID);
    $.zui.modalTrigger.show(
    {
        url: modalUrl,
        width: '1000px'
    });
    hideKanbanAction();
}

/**
 * Reset lane height according to window height.
 */
function resetLaneHeight()
{
    var maxHeight = '360px';
    if(laneCount < 2)
    {
        var windowHeight = $(window).height();
        var marginTop    = $('#mainContent').css('margin-top');
        var headerHeight = $('.kanban > .kanban-board:first > .kanban-header').outerHeight();
        var actionHeight = $('.kanban > .kanban-board:first > .kanban-lane > .kanban-col:first > .kanban-lane-actions').outerHeight();

        maxHeight = windowHeight - parseInt(marginTop) - headerHeight - actionHeight;
    }
    $('.kanban-lane-items').css('max-height', maxHeight);
}

/**
 * Close modal and update kanban data.
 */
function closeModalAndUpdateKanban(regionID)
{
    setTimeout(function()
    {
        $.zui.closeModal();
        updateRegion(regionID);
    }, 1200);
}

/**
 * Status change map
 */
var statusChangeMap =
{
    wait:   ['doing', 'done', 'cancel'],
    doing:  ['done', 'cancel'],
    done:   ['doing', 'closed'],
    cancel: ['doing', 'closed'],
    closed: ['doing']
};

/**
 * Find drop columns
 * @param {JQuery} $element Drag element
 * @param {JQuery} $root Dnd root element
 */
function findDropColumns($element, $root)
{
    var $task  = $element;
    var task   = $task.data('task');
    var status = task.status;
    var $col   = $task.closest('.kanban-col');
    var col    = $col.data();
    var lane   = $col.closest('.kanban-lane').data('lane');
    var allStatusCanChange = statusChangeMap[status];

    hideKanbanAction();

    return $root.find('.kanban-lane-col:not([data-type="EMPTY"],[data-type=""])').filter(function()
    {
        var $newCol = $(this);
        var newCol = $newCol.data();
        var $newLane = $newCol.closest('.kanban-lane');
        var newLane = $newLane.data('lane');

        $newCol.addClass('can-drop-here');
        return true;
    });
}

/**
 * Handle drop task
 * @param {Object} event Drop event object
 */
function handleDropTask($element, event, kanban)
{
    if(!event.target || !event.isNew) return;

    var $task    = $element;
    var $oldCol  = $task.closest('.kanban-col');
    var $newCol  = $(event.target).closest('.kanban-col');
    var oldCol   = $oldCol.data();
    var newCol   = $newCol.data();
    var oldLane  = $oldCol.closest('.kanban-lane').data('lane');
    var newLane  = $newCol.closest('.kanban-lane').data('lane');
    var kanbanID = $task.closest('.kanban-board').data('id');
    var regionID = $task.closest('.kanban').data('id');

    if(oldCol.id === newCol.id && newLane.id === oldLane.id) return false;

    var newStatus = newCol.type;
    var task = $task.data('task');

    /* Task status not change */
    if(newStatus === task.status && task.kanbanLane !== newLane.id)
    {
        var url = createLink('sys.task', 'move', 'taskID=' + task.id + '&groupID=' + newLane.group);
        return $.ajax(
        {
            method:   'post',
            dataType: 'json',
            url:      url,
            data:     {lane: newLane.id},
            success: function(data)
            {
                if(data && data.result === 'success')  updateRegion(regionID);
                else showErrorMessager(data && data.message);
            },
            error: function(xhr, status, error)
            {
                showErrorMessager(error || lang.timeout);
            }
        });
    }

    /* Show dialog to user for changing status */
    var methodToChangeStatus =
    {
        wait:   'activate',
        doing:  'start',
        done:   'finish',
        cancel: 'cancel',
        closed: 'close',
    };

    $.getJSON(createLink('task', 'move', 'taskID=' + task.id + '&groupID=' + newLane.group + '&laneID=' + newLane.id + '&columnID=' + newCol.id), function(response)
    {
        if(response.result == 'success') updateRegion(regionID);
    });
}

/**
 * Handle finish drop task
 */
function handleFinishDrop()
{
    $('.kanban').find('.can-drop-here').removeClass('can-drop-here');
}

/**
 * Adjust add button postion in column
 */
function adjustAddBtnPosition($kanban)
{
    if(!$kanban)
    {
        $('.kanban').children('.kanban-board').each(function()
        {
            adjustAddBtnPosition($(this));
        });
        return;
    }

    $kanban.find('.kanban-lane-col:not([data-type="EMPTY"])').each(function()
    {
        var $col = $(this);
        var items = $col.children('.kanban-lane-items')[0];
        $col.toggleClass('has-scrollbar', items.scrollHeight > items.clientHeight);
    });
}

/**
 * Kanban action handlers
 */
var kanbanActionHandlers =
{
    addItem:  openAddTaskForm,
    dropItem: handleDropTask
};

/**
 * Handle kanban action
 */
function handleKanbanAction(action, $element, event, kanban)
{
    $('.kanban').attr('data-action-enabled', action);
    var handler = kanbanActionHandlers[action];
    if(handler) handler($element, event, kanban);
}

function processMinusBtn()
{
    var columnCount = $('#splitTable .child-column').size();
    if(columnCount > 2 && columnCount < 10)
    {
        $('#splitTable .btn-plus').show();
        $('#splitTable .btn-close').show();
    }
    else if(columnCount <= 2)
    {
        $('#splitTable .btn-close').hide();
    }
    else if(columnCount >= 10)
    {
        $('#splitTable .btn-plus').hide();
    }
}

function createLaneMenu(options)
{
    var lane = options.$trigger.closest('.kanban-lane').data('lane');
    var privs = lane.actions;
    if(!privs.length) return [];

    var items = [];
    if(privs.includes('setLane')) items.push({label: kanbanLang.editLane, icon: 'edit', url: createLink('kanban', 'setLane', 'laneID=' + lane.id + '&executionID=0&from=kanban'), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    if(privs.includes('deleteLane')) items.push({label: kanbanLang.deleteLane, icon: 'trash', url: createLink('kanban', 'deleteLane', 'lane=' + lane.id), className: 'confirmer', attrs: {'data-confirmTitle': kanbanlaneLang.confirmDelete, 'data-confirmDetail': kanbanlaneLang.confirmDeleteDetail}});

    var bounds = options.$trigger[0].getBoundingClientRect();
    items.$options = {x: bounds.right, y: bounds.top};
    return items;
}

function createTaskMenu(options)
{
    var task = options.$trigger.closest('.kanban-item').data('task');
    var privs = task.actions;
    if(!privs.length) return [];

    var actions = {
        'edit': {'label': taskLang.edit, 'icon': '', 'url': createLink('task', 'edit', "taskID=" + task.id + "&from=kanban"), 'attrs': {'data-toggle': 'modal', 'data-width': '1125px'}},
        'move': {'label': taskLang.move, 'icon': '', 'url': createLink('task', 'move', "taskID=" + task.id + "&groupID=" + task.kanbanGroup), 'attrs': {'data-toggle': 'modal', 'data-width': 400}},
        'copy': {'label': taskLang.copy, 'icon': '', 'url': createLink('task', 'copy', "taskID=" + task.id + "&from=kanban"), 'attrs': {'data-toggle': 'modal', 'data-width': '1000px'}},
        'start': {'label': taskLang.start, 'icon': '', 'url': createLink('task', 'start', "taskID=" + task.id + "&from=kanban"), 'attrs': {'data-toggle': 'modal'}},
        'finish': {'label': taskLang.finish, 'icon': '', 'url': createLink('task', 'finish', "taskID=" + task.id + "&from=kanban"), 'attrs': {'data-toggle': 'modal'}},
        'activate': {'label': taskLang.activate, 'icon': '', 'url': createLink('task', 'activate', "taskID=" + task.id + "&from=kanban"), 'attrs': {'data-toggle': 'modal'}},
        'cancel': {'label': taskLang.cancel, 'icon': '', 'url': createLink('task', 'cancel', "taskID=" + task.id + "&from=kanban"), 'attrs': {'data-toggle': 'modal'}},
        'close': {'label': taskLang.close, 'icon': '', 'url': createLink('task', 'close', "taskID=" + task.id + "&from=kanban"), 'attrs': {'data-toggle': 'modal'}},
        'archive': {'label': taskLang.archive, 'icon': '', 'url': createLink('task', 'archive', "taskID=" + task.id + "&from=kanban"), 'attrs': {'class': 'confirmer', 'data-confirmTitle': taskLang.confirmArchive, 'data-confirmDetail': taskLang.confirmArchiveDetail, 'data-confirmButton': taskLang.archive, 'data-confirming': taskLang.archiving}},
        'delete': {'label': taskLang.delete, 'icon': '', 'url': createLink('task', 'delete', "taskID=" + task.id + '&from=kanban'), 'attrs': {'class': 'confirmer', 'data-confirmTitle': taskLang.confirmDelete, 'data-confirmDetail': taskLang.confirmDeleteDetail}},
        'setColor':  {'label': taskLang.setColor, 'icon': '', 'url': createLink('task', 'setColor', "taskID=" + task.id), 'attrs': {'data-toggle': 'modal', 'data-width': 400}},
    };

    var items = [];
    $.each(actions, function(actionKey, actionItem)
    {
        if(privs.includes(actionKey)) items.push({label: actionItem.label, icon: '', url: actionItem.url, attrs: actionItem.attrs});
    });

    var bounds = options.$trigger[0].getBoundingClientRect();
    items.$options = {x: bounds.right, y: bounds.top};
    return items;
}

function createColumnMenu(options)
{
    var column = options.$trigger.closest('.kanban-col').data('col');
    var privs = column.actions;
    if(!privs.length) return [];

    var items = [];
    if(privs.includes('editColumn')) items.push({label: kanbanLang.editColumn, icon: 'edit', url: createLink('kanban', 'setColumn', 'columnID=' + column.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    if(privs.includes('setWIP')) items.push({label: kanbanLang.setWIP, icon: 'alert', url: createLink('kanban', 'setWIP', 'columnID=' + column.id), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width' : '500px'}});
    if(privs.includes('splitColumn')) items.push({label: kanbanLang.splitColumn, icon: 'col-split', url: createLink('kanban', 'splitColumn', 'columnID=' + column.id), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    if(privs.includes('setColumn')) items.push({label: kanbanLang.editColumn, icon: '', url: createLink('kanban', 'setColumn', 'columnID=' + column.id + '&executionID=0&from=kanban', '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    if(privs.includes('createColumn'))
    {
        items.push({label: kanbanLang.createColumnOnLeft, icon: 'col-add-left', url: createLink('kanban', 'createColumn', 'columnID=' + column.id + '&position=left'), className: 'iframe', attrs: {'data-toggle': 'modal'}});
        items.push({label: kanbanLang.createColumnOnRight, icon: 'col-add-right', url: createLink('kanban', 'createColumn', 'columnID=' + column.id + '&position=right'), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    }
    if(privs.includes('copyColumn')) items.push({label: kanbanLang.copyColumn, icon: 'copy', url: createLink('kanban', 'copyColumn', 'columnID=' + column.id), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    if(privs.includes('archiveColumn')) items.push({label: kanbanLang.archiveColumn, icon: 'card-archive', url: createLink('kanban', 'archiveColumn', 'columnID=' + column.id), className: 'confirmer',  attrs: {'data-confirmTitle': kanbancolumnLang.confirmArchive, 'data-confirmDetail': kanbancolumnLang.confirmArchiveDetail, 'data-confirmButton': lang.archive, 'data-confirming': lang.archiving}});
    if(privs.includes('deleteColumn')) items.push({label: kanbanLang.deleteColumn, icon: 'trash', url: createLink('kanban', 'deleteColumn', 'columnID=' + column.id), className: 'confirmer',  attrs: {'data-confirmTitle': kanbancolumnLang.confirmDelete, 'data-confirmDetail': kanbancolumnLang.confirmDeleteDetail}});

    var bounds = options.$trigger[0].getBoundingClientRect();
    items.$options = {x: bounds.right, y: bounds.top};
    return items;
}

/* Define menu creators */
window.menuCreators =
{
    task: createTaskMenu,
    lane: createLaneMenu,
    column: createColumnMenu
};

/**
 * init Kanban
 */
function initKanban($kanban)
{
    var id = $kanban.data('id');
    var region = regions[id];

    $kanban.kanban(
    {
        data:              region.groups,
        maxColHeight:      510,
        createColumnText:  kanbanLang.createColumn,
        addItemText:       '',
        itemRender:        renderKanbanItem,
        onAction:          handleKanbanAction,
        onRenderKanban:    adjustAddBtnPosition,
        onRenderLaneName:  renderLaneName,
        onRenderHeaderCol: renderHeaderCol,
        onRenderCount:     renderCount,
        droppable:
        {
            target:       findDropColumns,
            finish:       handleFinishDrop,
            mouseButton: 'left'
        }
    }).on('click', '.action-cancel', hideKanbanAction);
}

/**
 * Init when page ready
 */
$(function()
{
    if($.cookie('isFullScreen') == 1) fullScreen();

    /* Init first kanban */
    $('.kanban').each(function()
    {
        initKanban($(this));
    });

    $('.icon-double-angle-up,.icon-double-angle-down').on('click', function()
    {
        $(this).toggleClass('icon-double-angle-up icon-double-angle-down');
        $(this).parents('.region').find('.kanban').toggle();
        hideKanbanAction();
    });

    $('.region-header').on('click', '.action', hideKanbanAction);
    $('#TRAction').on('click', '.btn', hideKanbanAction);

    /* Hide action box when user click document */
    $(document).on('click', function(e)
    {
        $('.kanban').each(function()
        {
            var currentAction = $(this).kanban().attr('data-action-enabled');
            var canHideAction = (currentAction === 'headerMore' || currentAction === 'editLaneName')
                && !$(e.target).closest('.action,.action-box').length;
            if(canHideAction) hideKanbanAction();
        });
    });

    /* Init contextmenu */
    $('#kanban').on('click', '[data-contextmenu]', function(event)
    {
        var $trigger    = $(this);
        var menuType    = $trigger.data('contextmenu');
        var menuCreator = window.menuCreators[menuType];
        if(!menuCreator) return;

        var options = $.extend({event: event, $trigger: $trigger}, $trigger.data());
        var items = menuCreator(options);
        if(!items || !items.length) return;

        $.zui.ContextMenu.show(items, items.$options || {event: event});
    });

    /* Adjust the add button position on window resize */
    $(window).on('resize', function(a)
    {
        adjustAddBtnPosition();
    });

    resetLaneHeight();

    /* Hide contextmenu when page scroll */
    $(window).on('scroll', function()
    {
        $.zui.ContextMenu.hide();
    });

    $(document).on('click', '#splitTable .btn-plus', function()
    {
        var tr = $(this).closest('tr');
        tr.after($('#childTpl').html().replace(/key/g, key));
        tr.next().find('input[name^=color]').colorPicker();
        key++;
        processMinusBtn();
        return false;
    });

    /* Remove a trade detail item. */
    $(document).on('click', '#splitTable .btn-close', function()
    {
        $(this).closest('tr').remove();
        processMinusBtn();
        return false;
    });

    /* Init sortable */
    var sortType = '';
    $('#kanban').sortable(
    {
        selector: '.region, .kanban-board, .kanban-lane',
        trigger: '.region.sort > .region-header, .kanban-board.sort > .kanban-header > .kanban-group-header, .kanban-lane.sort > .kanban-lane-name',
        targetSelector: function($ele)
        {
            /* Sort regions */
            if($ele.hasClass('region'))
            {
                sortType = 'region';
                return $ele.parent().children('.region');
            }

            /* Sort boards */
            if($ele.hasClass('kanban-board'))
            {
                sortType = 'board';
                return $ele.parent().children('.kanban-board');
            }

            /* Sort lanes */
            if($ele.hasClass('kanban-lane'))
            {
                sortType = 'lane';
                return $ele.parent().children('.kanban-lane');
            }

            /* Sort lanes */
            if($ele.hasClass('kanban-item'))
            {
                sortType = 'item';
                return $ele.parent().children('.kanban-item');
            }
        },
        start: function(e)
        {
            if(sortType == 'region')
            {
                $('.icon-double-angle-up').attr('class', 'icon-double-angle-down');
                $('.region').find('.kanban').hide();
                hideKanbanAction();
            }
        },
        finish: function(e)
        {
            var url = '';
            var orders = [];
            e.list.each(function(index, data)
            {
                orders.push(data.item.data('id'));
            });

            if(sortType == 'region')
            {
                $('.icon-double-angle-down').attr('class', 'icon-double-angle-up');
                $('.region').find('.kanban').show();
            }
            if(sortType == 'board')
            {
                var region = e.element.parent().data('id');
                url = createLink('kanban', 'sortGroup', 'region=' + region + '&groups=' + orders.join(','));
            }
            if(sortType == 'lane')
            {
                var region = e.element.parent().parent().data('id');
                url = createLink('kanban', 'sortLane', 'region=' + region + '&lanes=' + orders.join(','));
            }
            if(sortType == 'item')
            {
                url = createLink('task', 'sort', 'kanbanID=' + kanbanID + '&tasks=' + orders.join(','));
            }
            if(!url) return true;

            $.getJSON(url, function(response)
            {
                if(response.result == 'fail' && response.message.length)
                {
                    bootbox.alert(response.message);
                    setTimeout(function(){return location.reload()}, 3000);
                }
            });
        }
    });
});
