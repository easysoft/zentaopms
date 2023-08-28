$(function()
{
    $('#products').change(function()
    {
        var selectProductID = $('#products').val();
        var attr            = createLink('story', 'batchCreate', 'productID=' + selectProductID + '&branch=0' + '&moduleID=0&story=0&execution=' + executionID, '', true);
        $('#batchCreateStoryButton').attr('href',attr);
    });
})

/**
 * When execution status change.
 *
 * @param  stirng executionStatus
 * @access public
 * @return void
 */
function changeStatus(executionStatus)
{
    if(executionStatus == 'wait')
    {
        $('.startButton').removeClass('hidden');
        $('.putoffButton').removeClass('hidden');
        $('.suspendButton').removeClass('hidden');
        $('.closeButton').removeClass('hidden');
        $('.activateButton').addClass('hidden');
    }
    else if(executionStatus == 'doing')
    {
        $('.startButton').addClass('hidden');
        $('.putoffButton').removeClass('hidden');
        $('.suspendButton').removeClass('hidden');
        $('.closeButton').removeClass('hidden');
        $('.activateButton').addClass('hidden');
    }
    else if(executionStatus == 'suspended')
    {
        $('.startButton').addClass('hidden');
        $('.putoffButton').addClass('hidden');
        $('.suspendButton').addClass('hidden');
        $('.closeButton').removeClass('hidden');
        $('.activateButton').removeClass('hidden');
    }
    else if(executionStatus == 'closed')
    {
        $('.startButton').addClass('hidden');
        $('.putoffButton').addClass('hidden');
        $('.suspendButton').addClass('hidden');
        $('.closeButton').addClass('hidden');
        $('.activateButton').removeClass('hidden');
    }
}

/**
 * Hide all action.
 *
 * @access public
 * @return void
 */
function hideAction()
{
    $('.actions').hide();
    $('.action').hide();
    $('.avatar').removeAttr('data-toggle');
    $('.kanban-group-header').hide();
    $(".title").attr("disabled", true).css("pointer-events", "none");
    $(".avatar").attr("disabled", true).css("pointer-events", "none");
    window.sortableDisabled = true;
}

/**
 * Display the kanban in full screen.
 *
 * @access public
 * @return void
 */
function fullScreen()
{
    var element       = document.getElementById('kanbanContainer');
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullscreen;

    if(requestMethod)
    {
        var afterEnterFullscreen = function()
        {
            $('#kanbanContainer').addClass('fullscreen')
                .on('scroll', tryUpdateKanbanAffix);

            hideAction();
            $.cookie('isFullScreen', 1);
        };

        var whenFailEnterFullscreen = function(error)
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
    $('#kanbanContainer').removeClass('fullscreen')
        .off('scroll', tryUpdateKanbanAffix);
    $('.actions').show();
    $('.action').show();
    $('.avatar').attr('data-toggle', 'modal');
    $('.kanban-group-header').show();
    $(".title").attr("disabled", false).css("pointer-events", "auto");
    $(".avatar").attr("disabled", false).css("pointer-events", "auto");
    window.sortableDisabled = false;
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

/** Change kanban scale size */
function changeKanbanScaleSize(newScaleSize)
{
    var newScaleSize = Math.max(1, Math.min(4, newScaleSize));
    if(newScaleSize === window.kanbanScaleSize) return;

    window.kanbanScaleSize = newScaleSize;
    $.zui.store.set('executionKanbanScaleSize', newScaleSize);
    $('#kanbanScaleSize').text(newScaleSize);
    $('#kanbanScaleControl .btn[data-type="+"]').attr('disabled', newScaleSize >= 4 ? 'disabled' : null);
    $('#kanbanScaleControl .btn[data-type="-"]').attr('disabled', newScaleSize <= 1 ? 'disabled' : null);

    if(groupBy == 'default')
    {
        $('#kanban').children('.region').children("div[id^='kanban']").each(function()
        {
            var kanban = $(this).data('zui.kanban');
            if(!kanban) return;
            kanban.setOptions({cardsPerRow: newScaleSize, cardHeight: getCardHeight()});
        });
    }
    else
    {
        var kanban = $('#kanban' + executionID).data('zui.kanban');
        if(!kanban) return;
        kanban.setOptions({cardsPerRow: newScaleSize, cardHeight: getCardHeight()});
    }

    if(laneCount == 1) resetRegionHeight('open');

    return newScaleSize;
}

/** Get card height */
function getCardHeight()
{
    return [60, 60, 62, 62, 47][window.kanbanScaleSize];
}

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

    var link = createLink('execution', 'kanban', "executionID=" + executionID + '&browseType=' + type);
    location.href = link;
});

$('.c-group').change(function()
{
    $('.c-group').show();

    var type  = $('#type').val() ? $('#type').val() : browseType;
    var group = $('#group').val();
    var link  = createLink('execution', 'kanban', 'executionID=' + executionID + '&type=' + type + '&orderBy=id_asc' + '&groupBy=' + group);
    location.href = link;
});

/**
 * Create lane menu.
 *
 * @param  object $options
 * @access public
 * @return void
 */
function createLaneMenu(options)
{
    var lane = options.$trigger.closest('.kanban-lane').data('lane');

    var privs = lane.actions;
    if(!privs.length) return [];

    var items    = [];
    var regionID = lane.$kanbanData.region;
    var kanbanID = lane.$kanbanData.kanban;
    if(privs.includes('editLaneName')) items.push({label: kanbanLang.editLaneName, icon: 'edit', url: createLink('kanban', 'editLaneName', 'laneID=' + lane.id + '&executionID=0&from=kanban'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '635px'}});
    if(privs.includes('editLaneColor')) items.push({label: kanbanLang.editLaneColor, icon: 'color', url: createLink('kanban', 'editLaneColor', 'laneID=' + lane.id + '&executionID=0&from=kanban'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '635px'}});
    if(privs.includes('deleteLane')) items.push({label: kanbanLang.deleteLane, icon: 'trash', url: createLink('kanban', 'deleteLane', 'regionID=' + regionID + '&kanbanID=' + kanbanID + '&lane=' + lane.id), attrs: {'target': 'hiddenwin'}});

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
    if(privs.includes('setColumn')) items.push({label: kanbanLang.editColumn, icon: 'edit', url: createLink('kanban', 'setColumn', 'columnID=' + column.id + '&executionID=' + executionID + '&from=RDKanban', '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    if(privs.includes('setWIP')) items.push({label: kanbanLang.setWIP, icon: 'alert', url: createLink('kanban', 'setWIP', 'columnID=' + column.id + '&executionID=' + executionID + '&from=RDKanban', '' ,'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width' : '500px'}});

    var bounds = options.$trigger[0].getBoundingClientRect();
    items.$options = {x: bounds.right, y: bounds.top};
    return items;
}

/**
 * Create column create button menu
 * @returns {Object[]}
 */
function createColumnCreateMenu(options)
{
    var $col     = options.$trigger.closest('.kanban-col');
    var col      = $col.data('col');
    var items    = [];
    var laneID   = col.$kanbanData.lanes[0].id ? col.$kanbanData.lanes[0].id : 0;
    var regionID = col.$kanbanData.region == undefined ? 0 : col.$kanbanData.region;

    if(col.type == 'backlog')
    {
        if(priv.canCreateStory) items.push({label: storyLang.create, url: $.createLink('story', 'create', 'productID=' + productID + '&branch=0&moduleID=0&storyID=0&objectID=' + executionID + '&bugID=0&planID=0&todoID=0&extra=regionID=' + regionID + ',laneID=' + laneID + ',columnID=' + col.id, '', true), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
        if(priv.canBatchCreateStory) items.push({label: executionLang.batchCreateStory, url: productCount > 1 ? '#batchCreateStory' : $.createLink('story', 'batchcreate', 'productID=' + productID + '&branch=0&moduleID=0&storyID=0&executionID=' + executionID + '&plan=0&type=story&extra=regionID=' + regionID + ',laneID=' + laneID + ',columnID=' + col.id, '', true), className: 'iframe',attrs: {'data-toggle': 'modal', 'data-width': '90%'}});
        if(priv.canLinkStory) items.push({label: executionLang.linkStory, url: $.createLink('execution', 'linkStory', 'executionID=' + executionID + '&browseType=&param=0&recTotal=0&recPerPage=50,&pageID=1&extra=laneID=' + laneID + ',columnID=' + col.id, '', true), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '90%'}});
        if(priv.canLinkStoryByPlan) items.push({label: executionLang.linkStoryByPlan, url: '#linkStoryByPlan', 'attrs' : {'data-toggle': 'modal', 'data-target': '#linkStoryByPlan','data-col' : col.id, 'data-lane' : laneID, 'class' : 'linkStoryByPlanButton'}});
    }
    else if(col.type == 'unconfirmed')
    {
        if(priv.canCreateBug) items.push({label: bugLang.create, url: $.createLink('bug', 'create', 'productID=' + productID + '&moduleID=0&extra=regionID=' + regionID + ',laneID=' + laneID + ',columnID=' + col.id + ',executionID=' + executionID, '', true), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
        if(priv.canBatchCreateBug)
        {
            if(productNum > 1) items.push({label: bugLang.batchCreate, url: '#batchCreateBug', 'attrs' : {'data-toggle': 'modal'}});
            else items.push({label: bugLang.batchCreate, url: $.createLink('bug', 'batchcreate', 'productID=' + productID + '&branch=&executionID=' + executionID + '&module=0&extra=regionID=' + regionID + ',laneID=' + laneID + ',columnID=' + col.id, '', true), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '90%'}});
        }
    }
    else if(col.type == 'wait')
    {
        if(priv.canCreateTask) items.push({label: taskLang.create, url: $.createLink('task', 'create', 'executionID=' + executionID + "&storyID=0&moduleID=0&taskID=0&todoID=0&extra=regionID=" + regionID + ",laneID=" + laneID + ",columnID=" + col.id, '', true), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
        if(priv.canBatchCreateTask) items.push({label: taskLang.batchCreate, url: $.createLink('task', 'batchcreate', 'executionID=' + executionID + "&storyID=0&moduleID=0&taskID=0&iframe=0&extra=regionID=" + regionID + ",laneID=" + laneID + ",columnID=" + col.id, '', true), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '90%'}});
        if(priv.canImportBug && vision == 'rnd') items.push({label: executionLang.importBug, url: $.createLink('execution', 'importBug', 'executionID=' + executionID + "&storyID=0&moduleID=0&taskID=0&todoID=0&extra=laneID=" + laneID  + ",columnID=" + col.id, '', true), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '90%'}});
    }
    return items;
}

/**
 * Update region name.
 *
 * @param  int    $regionID
 * @param  string $name
 * @access public
 * @return void
 */
function updateRegionName(regionID, name)
{
    $('.region[data-id="' + regionID + '"] > .region-header > strong:first').text(name);
}

/**
 * Update lane name.
 *
 * @param  int    $laneID
 * @param  string $name
 * @access public
 * @return void
 */
function updateLaneName(laneID, name)
{
    $('.kanban-lane[data-id="' + laneID + '"] > .kanban-lane-name > span').text(name).attr('title', name);
}

/**
 * Update lane color.
 *
 * @param  int    $laneID
 * @param  string $color
 * @access public
 * @return void
 */
function updateLaneColor(laneID, color)
{
    $('.kanban-lane[data-id="' + laneID + '"] > .kanban-lane-name').css('background-color', color);
}

/**
 * Update column name.
 *
 * @param  int    $columnID
 * @param  string $name
 * @param  string $color
 * @access public
 * @return void
 */
function updateColumnName(columnID, name, color)
{
    $('.kanban-col[data-id="' + columnID + '"] > div.title > span:first').text(name).attr('title', name).css('color', color);
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
    $('.storyColumn').parent().removeClass('open');
}

/**
 * Handle finish drop task
 */
function handleFinishDrop()
{
    $('.kanban').find('.can-drop-here').removeClass('can-drop-here');
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

/*
 * Find drop columns
 * @param {JQuery} $element Drag element
 * @param {JQuery} $root Dnd root element
 */
function findDropColumns($element, $root)
{
    var $col        = $element.closest('.kanban-col');
    var col         = $col.data();
    var laneType    = $element.closest('.kanban-lane').data().lane.type;
    var kanbanRules = window.kanbanDropRules ? window.kanbanDropRules[laneType] : null;

    hideKanbanAction();

    if(!kanbanRules) return $root.find('.kanban-lane-col:not([data-type="' + col.type + '"])');

    var colRules  = kanbanRules[col.type];
    var groupID   = $col.closest('.kanban-board').data().id;
    var oldLaneID = $col.closest('.kanban-lane').data().id;
    return $root.find('.kanban-lane-col').filter(function()
    {
        if(!colRules) return false;
        if(colRules === true) return true;
        if($.cookie('isFullScreen') == 1) return false;

        var $newCol    = $(this);
        var newCol     = $newCol.data();
        var newGroupID = $newCol.closest('.kanban-board').data().id;
        var newLaneID  = $newCol.closest('.kanban-lane').data().id;

        var canDropHere = colRules.indexOf(newCol.type) > -1 && newGroupID === groupID;
        if(groupBy && groupBy != 'default' && canDropHere) canDropHere = oldLaneID === newLaneID;
        if(canDropHere) $newCol.addClass('can-drop-here');
        return canDropHere;
    });
}

/**
 * Render user avatar
 *
 * @param  array $user
 * @param  string $objectType
 * @param  int $objectID
 * @param  int $size
 * @param  string $objectStatus
 * @access public
 * @return void
 */
function renderUserAvatar(user, objectType, objectID, size, objectStatus)
{
    var avatarSizeClass = 'avatar-' + (size || 'sm');
    var $noPrivAndNoAssigned = $('<div class="avatar has-text ' + avatarSizeClass + ' avatar-circle" title="' + noAssigned + '" style="background: #ccc"><i class="icon icon-person"></i></div>');
    if(objectType == 'task')
    {
        if(!priv.canAssignTask && !user) return $noPrivAndNoAssigned;
        var link = createLink('task', 'assignto', 'executionID=' + executionID + '&id=' + objectID + '&kanbanGroup=' + groupBy, '', true);
    }
    if(objectType == 'story')
    {
        if(!priv.canAssignStory && !user) return $noPrivAndNoAssigned;
        var link = createLink('story', 'assignto', 'id=' + objectID + '&kanbanGroup=' + groupBy, '', true);
    }
    if(objectType == 'bug')
    {
        if(!priv.canAssignBug && !user) return $noPrivAndNoAssigned;
        var link = createLink('bug', 'assignto', 'id=' + objectID + '&kanbanGroup=' + groupBy, '', true);
    }

    if(!user) return objectStatus == 'closed' ? '' : $('<a class="avatar has-text ' + avatarSizeClass + ' avatar-circle iframe" title="' + noAssigned + '" style="background: #ccc" href="' + link + '" data-toggle="modal" data-width="80%"><i class="icon icon-person"></i></a>');

    if(typeof user === 'string') user = {account: user};
    if(!user.avatar && window.userList && window.userList[user.account]) user = {avatar: userList[user.account].avatar, account: user.account, realname: userList[user.account].realname};

    var $noPrivAvatar = $('<div class="avatar has-text ' + avatarSizeClass + ' avatar-circle" />').avatar({user: user});
    if(objectType == 'task'  && !priv.canAssignTask)  return $noPrivAvatar;
    if(objectType == 'story' && !priv.canAssignStory) return $noPrivAvatar;
    if(objectType == 'bug'   && !priv.canAssignBug)   return $noPrivAvatar;

    var realname = user.realname ? user.realname : user.account;
    return objectStatus == 'closed' ? '' : $('<a class="avatar has-text ' + avatarSizeClass + ' avatar-circle iframe" title="' + realname + '" href="' + link + '"/>').avatar({user: user}).attr('data-toggle', 'modal').attr('data-width', '80%');
}

/**
 * Render deadline
 * @param {String|Date} deadline Deadline
 * @param {string}      status
 * @returns {JQuery}
 */
function renderDeadline(deadline, status)
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
    var statusList       = ['doing','pause'];
    var textColor        = isEarlyThanToday && typeof(status) != 'undefined' && statusList.indexOf(status) != -1 ? 'text-red' : 'text-muted';

    return $('<span class="info info-deadline"/>').text(deadlineLang + ' ' + deadlineDate).addClass(textColor);
}

/**
 * Render estStarted
 *
 * @param  {String|Date} estStarted EstStarted
 * @param  {string}      status
 * @access public
 * @return void
 */
function renderEstStarted(estStarted, status)
{
    if(estStarted == '0000-00-00') return;

    var date = $.zui.createDate(estStarted);
    var now  = new Date();
    now.setHours(0);
    now.setMinutes(0);
    now.setSeconds(0);
    now.setMilliseconds(0);
    var isEarlyThanToday = date.getTime() < now.getTime();
    var estStartedDate   = $.zui.formatDate(date, 'MM-dd');
    var textColor        = isEarlyThanToday && typeof(status) != 'undefined' && status == 'wait' ? 'text-red' : 'text-muted';

    return $('<span class="info info-deadline"/>').text(estStartedLang + ' ' + estStartedDate).addClass(textColor);
}

/**
 * Render story item
 * @param {Object} item  Story item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderStoryItem(item, $item, col)
{
    if(groupBy == 'story' && item.id == '0')
    {
        $('.storyCell').css('width', '100%');
        $parentItem = $item[0] == undefined ? $('.storyCell') : $item.parent();
        $parentItem.addClass('text-center storyCell');
        $parentItem.css('line-height', ($parentItem.parent().height() - 20) + 'px');
        $item.replaceWith('<span class="text-muted">' + item.title + '</span>');
        return;
    }
    var scaleSize = window.kanbanScaleSize;
    if(+$item.attr('data-scale-size') !== scaleSize) $item.empty().attr('data-scale-size', scaleSize);

    if(scaleSize <= 3)
    {
        var $title = $item.find('.title');
        if(!$title.length)
        {
            $title = $('<a class="title iframe">' + (scaleSize <= 1 ? '<i class="icon icon-lightbulb text-muted"></i> ' : '') + '<span class="text"></span></a>');
            if(priv.canViewStory) $title = $title.attr('href', $.createLink('execution', 'storyView', 'storyID=' + item.id + '&executionID=' + execution.id, '', true)).attr('data-toggle', 'modal').attr('data-width', '95%');
            $title.appendTo($item);
        }
        var title = rdSearchValue != '' ? "<span class='text'>" + item.title.replaceAll(rdSearchValue, "<span class='text-danger'>" + rdSearchValue + "</span>") + "</span>": "<span class='text'>" + item.title + "</span>";
        $title.attr('title', item.title).find('.text').replaceWith(title);
    }

    if(scaleSize <= 2)
    {
        var idHtml     = scaleSize <= 1 ? ('<span class="info info-id text-muted">#' + item.id + '</span>') : '';
        var priHtml    = '<span class="info info-pri' + (item.pri ? ' label-pri label-pri-' + item.pri : '') + '" title="' + item.pri + '">' + item.pri + '</span>';
        var hoursHtml  = (item.estimate && scaleSize <= 1) ? ('<span class="info info-estimate text-muted">' + item.estimate + hourUnit + '</span>') : '';
        var avatarHtml = renderUserAvatar(item.assignedTo, 'story', item.id, '', col.type);
        var $infos = $item.find('.infos');
        if(!$infos.length) $infos = $('<div class="infos"></div>');
        $infos.html([idHtml, priHtml, hoursHtml].join(''));

        $infos[scaleSize <= 1 ? 'append' : 'prepend'](avatarHtml);
        if(scaleSize <= 1) $infos.appendTo($item);
        else if(scaleSize === 2) $infos.prependTo($item);
        else $infos.prependTo($item.find('.title'));
    }
    else if(scaleSize === 4)
    {
        $item.html(renderUserAvatar(item.assignedTo, 'story', item.id, 'md'));
    }

    if(scaleSize <= 1)
    {
        var $actions = $item.find('.actions');
        if(!$actions.length && (priv.canEditStory || priv.canActivateStory || priv.canChangeStory || priv.canCreateTask || priv.canBatchCreate || priv.canUnlinkStory))
        {
            $actions = $([
                '<div class="actions">',
                    '<a data-contextmenu="story" data-col="' + col.type + '">',
                        '<i class="icon icon-ellipsis-v"></i>',
                    '</a>',
                '</div>'
            ].join('')).appendTo($item);
        }
    }

    if($.cookie('isFullScreen') == 1) hideAction();

    return $item.attr('data-type', 'story').addClass('kanban-item-story');
}

/**
 * Render bug item
 * @param {Object} item  Bug item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderBugItem(item, $item, col)
{
    var scaleSize = window.kanbanScaleSize;
    if(+$item.attr('data-scale-size') !== scaleSize) $item.empty().attr('data-scale-size', scaleSize);

    if(scaleSize <= 3)
    {
        var $title = $item.find('.title');
        if(!$title.length)
        {
            $title = $('<a class="title iframe" data-width="95%">' + (scaleSize <= 1 ? '<i class="icon icon-bug text-muted"></i> ' : '') + '<span class="text"></span></a>');
            if(priv.canViewBug) $title =$title.attr('href', $.createLink('bug', 'view', 'bugID=' + item.id, '', true)).attr('data-toggle', 'modal').attr('data-width', '80%');
            $title.appendTo($item);
        }
        var title = rdSearchValue != '' ? "<span class='text'>" + item.title.replaceAll(rdSearchValue, "<span class='text-danger'>" + rdSearchValue + "</span>") + "</span>": "<span class='text'>" + item.title + "</span>";
        $title.attr('title', item.title).find('.text').replaceWith(title);
    }

    if(scaleSize <= 2)
    {
        var idHtml       = scaleSize <= 1 ? ('<span class="info info-id text-muted">#' + item.id + '</span>') : '';
        var severityHtml = scaleSize <= 1 ? ('<span class="info info-severity label-severity" data-severity="' + item.severity + '" title="' + item.severity + '"></span>') : '';
        var priHtml      = '<span class="info info-pri' + (item.pri ? ' label-pri label-pri-' + item.pri : '') + '" title="' + item.pri + '">' + item.pri + '</span>';
        var avatarHtml   = renderUserAvatar(item.assignedTo, 'bug', item.id, '', col.type);

        var $infos = $item.find('.infos');
        if(!$infos.length) $infos = $('<div class="infos"></div>');
        $infos.html([idHtml, severityHtml, priHtml].join(''));
        if(item.deadline && scaleSize <= 1) $infos.append(renderDeadline(item.deadline));
        $infos[scaleSize <= 1 ? 'append' : 'prepend'](avatarHtml);

        if(scaleSize <= 1) $infos.appendTo($item);
        else if(scaleSize === 2) $infos.prependTo($item);
        else $infos.prependTo($item.find('.title'));
    }
    else if(scaleSize === 4)
    {
        $item.html(renderUserAvatar(item.assignedTo, 'bug', item.id, 'md'));
    }

    if(scaleSize <= 1)
    {
        var $actions = $item.find('.actions');
        if(!$actions.length && (priv.canEditBug || priv.canResolveBug || priv.canConfirmBug || priv.canCopyBug || priv.canToStoryBug || priv.canDeleteBug))
        {
            $actions = $([
                '<div class="actions">',
                    '<a data-contextmenu="bug" data-col="' + col.type + '">',
                        '<i class="icon icon-ellipsis-v"></i>',
                    '</a>',
                '</div>'
            ].join('')).appendTo($item);
        }
    }

    if($.cookie('isFullScreen') == 1) hideAction();

    return $item.attr('data-type', 'bug').addClass('kanban-item-bug');
}

/**
 * Render task item
 * @param {Object} item  Task item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderTaskItem(item, $item, col)
{
    var scaleSize = window.kanbanScaleSize;
    if(+$item.attr('data-scale-size') !== scaleSize)  $item.empty().attr('data-scale-size', scaleSize);

    if(scaleSize <= 3)
    {
        var $title = $item.find('.title');
        if(!$title.length)
        {
            $title = $('<a class="title iframe" data-width="95%">' + (scaleSize <= 1 ? '<i class="icon icon-checked text-muted"></i> ' : '') + '<span class="text"></span></a>');
            if(priv.canViewTask) $title = $title.attr('href', $.createLink('task', 'view', 'taskID=' + item.id, '', true)).attr('data-toggle', 'modal').attr('data-width', '80%');
            $title.appendTo($item);
        }
        var name = rdSearchValue != '' ? "<span class='text'>" + item.name.replaceAll(rdSearchValue, "<span class='text-danger'>" + rdSearchValue + "</span>") + "</span>": "<span class='text'>" + item.name + "</span>";
        $title.attr('title', item.name).find('.text').replaceWith(name);
    }

    if(scaleSize <= 2)
    {
        var priHtml    = '<span class="info info-pri' + (item.pri ? ' label-pri label-pri-' + item.pri : '') + '" title="' + item.pri + '">' + item.pri + '</span>';
        var hoursHtml  = scaleSize <= 1 && item.status != 'wait' ? ('<span class="info info-estimate text-muted">' + taskLang.leftAB + ' ' + item.left + 'h</span>') : ('<span class="info info-estimate text-muted">' + taskLang.estimateAB + ' ' + item.estimate + 'h</span>');
        var avatarHtml = renderUserAvatar(item.assignedTo, 'task', item.id, '', col.type);

        var $infos = $item.find('.infos');
        if(!$infos.length) $infos = $('<div class="infos"></div>');
        $infos.html([priHtml, hoursHtml].join(''));
        if(item.deadline && scaleSize <= 1 && (item.status == 'doing' || item.status == 'pause')) $infos.append(renderDeadline(item.deadline, item.status));
        if(item.estStarted && scaleSize <= 1 && item.status == 'wait') $infos.append(renderEstStarted(item.estStarted, item.status));
        $infos[scaleSize <= 1 ? 'append' : 'prepend'](avatarHtml);

        if(scaleSize <= 1) $infos.appendTo($item);
        else if(scaleSize === 2) $infos.prependTo($item);
        else $infos.prependTo($item.find('.title'));
    }
    else if(scaleSize === 4)
    {
        $item.html(renderUserAvatar(item.assignedTo, 'task', item.id, 'md'));
    }

    if(scaleSize <= 1)
    {
        var $actions = $item.find('.actions');
        if(!$actions.length && (priv.canEditTask || priv.canRecordEstimateTask || priv.canCreateTask || priv.canCancelTask))
        {
            $actions = $([
                '<div class="actions">',
                    '<a data-contextmenu="task" data-col="' + col.type + '">',
                        '<i class="icon icon-ellipsis-v"></i>',
                    '</a>',
                '</div>'
            ].join('')).appendTo($item);
        }
    }

    $item.attr('data-type', 'task').addClass('kanban-item-task');

    if($.cookie('isFullScreen') == 1) hideAction();

    return $item;
}

/* Add column renderer */
addColumnRenderer('story', renderStoryItem);
addColumnRenderer('bug',   renderBugItem);
addColumnRenderer('task',  renderTaskItem);

/**
 * Render items count of a column.
 */
function renderCount($count, count, column)
{
    if(groupBy == 'story' && column.type == 'story')
    {
        var orderButton = '<a class="btn btn-link action storyColumn ' + (changeOrder ? 'text-primary' : '') + '" type="button" data-toggle="dropdown">'
            + "<i class='icon icon-swap'></i>"
            + '</a>'
            + '<ul class="dropdown-menu">';
        for(var order in kanbanLang.orderList) orderButton += '<li class="' + (order == orderBy ? 'active' : '') + '"><a href="###" onclick="searchCards(rdSearchValue, \'' + order + '\')">' + kanbanLang.orderList[order] + '</a></li>';
        orderButton += '</ul>';

        $count.parent().next().html(orderButton);
        $count.parent().next().addClass('createButton');
        $count.remove();
        return;
    }

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
        if(!$count.parent().find('.error').length) $count.parent().find('.include-last').after("<span class='error text-grey'><icon class='icon icon-help' title='" + kanbanLang.limitExceeded + "'></icon></span>");
    }
    else
    {
        $count.parents('.title').parent('.kanban-header-col').css('background-color', 'transparent');
        $count.parents('.title').find('.text').css('max-width', $count.parents('.title').width() - 120);
        $count.css('color', '#8B91A2');
        $count.parent().find('.error').remove();
    }
}

/**
 * Alert to link product.
 *
 * @access public
 * @return void
 */
function tips()
{
    bootbox.alert(needLinkProducts);
}

/**
 * Render header of a column.
 */
function renderHeaderCol($column, column, $header, kanbanData)
{
    if(groupBy == 'story' && column.type == 'story') return;
    /* Render group header. */
    var privs       = kanbanData.actions;
    var columnPrivs = kanbanData.columns[0].actions;
    var $actions    = $column.children('.actions');

    if(column.parent == -1)
    {
        $column.append('<div class="actions"></div>');
        $actions = $column.children('.actions');
    }

    if(groupBy == 'default' && privs.includes('sortGroup'))
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

    var printMoreBtn = (columnPrivs.includes('setColumn') || columnPrivs.includes('setWIP'));

    /* Render more menu. */
    if((column.type == 'backlog' || column.type == 'wait' || column.type == 'unconfirmed') && $actions.children('.text-primary').length == 0)
    {
        var tips = productID ? '' : 'onclick="tips()"';
        $actions.append([
                '<a data-contextmenu="columnCreate" data-type="' + column.type + '" data-kanban="' + kanban.id + '" data-parent="' + (column.parentType || '') +  '" class="text-primary"' + ((column.type !== 'wait') ? tips : '') + '>',
                '<i class="icon icon-expand-alt"></i>',
                '</a>'
        ].join(''));
    }
    if(printMoreBtn && $actions.children('.btn').length == 0)
    {
        $actions.append(' <button class="btn btn-link action"  title="' + kanbanLang.moreAction + '" data-contextmenu="column" data-column="' + column.id + '"><i class="icon icon-ellipsis-v"></i></button>');
    }
}

/**
 * Render lane name.
 *
 * @param  object  $lane
 * @param  int     lane
 * @param  object  $kanban
 * @param  array   columns
 * @param  object  $kanban
 * @access public
 * @return void
 */
function renderLaneName($lane, lane, $kanban, columns, kanban)
{
    if(groupBy == 'story')
    {
        $lane.hide();
        return;
    }
    if(groupBy != 'default') return;
    var canEditLaneColor = lane.actions.includes('editLaneColor');
    var canEditLaneName  = lane.actions.includes('editLaneName');
    var canSort          = lane.actions.includes('sortLane') && kanban.lanes.length > 1;
    var canDelete        = lane.actions.includes('deleteLane');

    $lane.parent().toggleClass('sort', canSort);

    if(!$lane.children('.actions').length && (canEditLaneColor || canEditLaneName || canDelete))
    {
        $([
          '<div class="actions" title="' + kanbanLang.more + '">',
          '<a data-contextmenu="lane" data-lane="' + lane.id + '" data-kanban="' + kanban.id + '">',
          '<i class="icon icon-ellipsis-v"></i>',
          '</a>',
          '</div>'
        ].join('')).appendTo($lane);
    }
}

/**
 * Update a region.
 *
 * @param  int      regionID
 * @param  array    regionData
 * @access public
 * @return boolean
 */
function updateRegion(regionID, regionData = [])
{
    if(groupBy != 'default') regionID = executionID;

    if(!regionID) return false;

    var $region = $('#kanban'+ regionID).kanban();

    if(!$region.length) return false;
    if(!regionData) regionData = regions[regionID];

    var data = groupBy == 'default' ? regionData.groups : regionData;
    if((data == null || data.length == 0) && rdSearchValue != '')
    {
        if(groupBy == 'default') $("div[data-id^=" + regionID + "].region").hide();
        if(groupBy != 'default') $("div[data-id^=" + regionID + "].kanban").hide();
        return false;
    }
    else
    {
        if(groupBy == 'default') $("div[data-id^=" + regionID + "].region").show();
        if(groupBy != 'default') $("div[data-id^=" + regionID + "].kanban").show();
    }
    $region.data('zui.kanban').render(data);
    resetRegionHeight('open');
    return true;
}

/**
 * Handle drop task.
 *
 * @param  object $element
 * @param  object $event
 * @param  object $kanban
 * @access public
 * @return void
 */
function handleDropTask($element, event, kanban)
{
    if(!event.target || !event.isNew) return;

    var $card    = $element;
    var $oldCol  = $card.closest('.kanban-col');
    var $newCol  = $(event.target).closest('.kanban-col');
    var oldCol   = $oldCol.data();
    var newCol   = $newCol.data();
    var oldLane  = $oldCol.closest('.kanban-lane').data('lane');
    var newLane  = $newCol.closest('.kanban-lane').data('lane');
    var cardType = $card.find('.kanban-card').data('type');

    if(!oldCol || !newCol || !newLane || !oldLane) return false;
    if(oldCol.id === newCol.id && newLane.id === oldLane.id) return false;

    var cardID      = $card.data().id;
    var fromColType = $oldCol.data('type');
    var toColType   = $newCol.data('type');
    var regionID    = $card.closest('.region').data().id;

    changeCardColType(cardID, oldCol.id, newCol.id, oldLane.id, newLane.id, cardType, fromColType, toColType, regionID);
}

var kanbanActionHandlers =
{
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

/**
 * changeCardColType
 *
 * @param  int    cardID
 * @param  int    fromColID
 * @param  int    toColID
 * @param  int    fromLaneID
 * @param  int    toLaneID
 * @param  string cardType
 * @param  string fromColType
 * @param  string toColType
 * @param  int    regionID
 * @access public
 * @return void
 */
function changeCardColType(cardID, fromColID, toColID, fromLaneID, toLaneID, cardType, fromColType, toColType, regionID = 0)
{
    var objectID   = cardID;
    var showIframe = false;
    var moveCard   = false;

    regionID = regionID ? regionID : 0;

    /* Task lane. */
    if(cardType == 'task')
    {
        if(toColType == 'developed')
        {
            if((fromColType == 'developing' || fromColType == 'wait') && priv.canFinishTask)
            {
                var link = createLink('task', 'finish', 'taskID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'pause')
        {
            if(fromColType == 'developing' && priv.canPauseTask)
            {
                var link = createLink('task', 'pause', 'taskID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'developing')
        {
            if((fromColType == 'canceled' || fromColType == 'closed' || fromColType == 'developed') && priv.canActivateTask)
            {
                var link = createLink('task', 'activate', 'taskID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
            if(fromColType == 'pause' && priv.canActivateTask)
            {
                var link = createLink('task', 'restart', 'taskID=' + objectID, '', true);
                showIframe = true;
            }
            if(fromColType == 'wait' && priv.canStartTask)
            {
                var link = createLink('task', 'start', 'taskID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'canceled')
        {
            if((fromColType == 'developing' || fromColType == 'wait' || fromColType == 'pause') && priv.canCancelTask)
            {
                var link = createLink('task', 'cancel', 'taskID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'closed')
        {
            if((fromColType == 'developed' || fromColType == 'canceled') && priv.canCloseTask)
            {
                var link = createLink('task', 'close', 'taskID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }

        if(fromLaneID != toLaneID && fromColID == toColID) shiftCard(objectID, fromColID, toColID, fromLaneID, toLaneID, regionID);
    }

    /* Bug lane. */
    if(cardType == 'bug')
    {
        if(toColType == 'confirmed')
        {
            if(fromColType == 'unconfirmed' && priv.canConfirmBug)
            {
                var link = createLink('bug', 'confirmBug', 'bugID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'fixing')
        {
            if(fromColType == 'confirmed' || fromColType == 'unconfirmed') moveCard = true;
            if((fromColType == 'closed' || fromColType == 'fixed' || fromColType == 'testing' || fromColType == 'tested') && priv.canActivateBug)
            {
                var link = createLink('bug', 'activate', 'bugID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'fixed')
        {
            if(fromColType == 'fixing' || fromColType == 'confirmed' || fromColType == 'unconfirmed')
            {
                var link = createLink('bug', 'resolve', 'bugID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
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
                var link = createLink('bug', 'close', 'bugID=' + objectID + '&extra=fromColID=' + fromColID + ',toColID=' + toColID + ',fromLaneID=' + fromLaneID + ',toLaneID=' + toLaneID + ',regionID=' + regionID, '', true);
                showIframe = true;
            }
        }

        if(moveCard || (fromLaneID != toLaneID && fromColID == toColID)) shiftCard(objectID, fromColID, toColID, fromLaneID, toLaneID, regionID);
    }

    /* Story lane. */
    if(cardType == 'story')
    {
        if(toColType == 'closed' && priv.canCloseStory)
        {
            var link = createLink('story', 'close', 'storyID=' + objectID, '', true);
            showIframe = true;
        }
        else
        {
            if(toColType == 'ready')
            {
                $.get(createLink('story', 'ajaxGetInfo', "storyID=" + cardID), function(data)
                {
                    if(data)
                    {
                        data = $.parseJSON(data);
                        if(data.status == 'draft' || data.status == 'changing' || data.status == 'reviewing')
                        {
                            bootbox.alert(executionLang.storyDragError);
                        }
                        else
                        {
                            ajaxMoveCard(objectID, fromColID, toColID, fromLaneID, toLaneID, regionID);
                        }
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
        var modalTrigger = new $.zui.ModalTrigger({type: 'iframe', width: '80%', url: link});
        modalTrigger.show();
    }
}

/**
 * AJAX: move card.
 *
 * @param  int $objectID
 * @param  int $fromColID
 * @param  int $toColID
 * @param  int $fromLaneID
 * @param  int $toLaneID
 * @param  int $regionID
 * @access public
 * @return void
 */
function ajaxMoveCard(objectID, fromColID, toColID, fromLaneID, toLaneID, regionID)
{
    var link = createLink('kanban', 'ajaxMoveCard', 'cardID=' + objectID + '&fromColID=' + fromColID + '&toColID=' + toColID + '&fromLaneID=' + fromLaneID + '&toLaneID=' + toLaneID + '&execitionID=' + executionID + '&browseType=' + browseType + '&groupBy=' + groupBy + '&regionID=' + regionID+ '&orderBy=' + orderBy );
    $.ajax(
    {
        method:   'post',
        dataType: 'json',
        url:       link,
        success: function(data)
        {
            data = groupBy == 'default' ? data[regionID] : data[groupBy];
            updateRegion(regionID, data);
        },
        error: function(xhr, status, error)
        {
            showErrorMessager(error || lang.timeout);
        }
    });
}

/**
 * Delete a card.
 *
 * @param string objectType
 * @param int    objectID
 * @param int    regionID
 * @access public
 * @return void
 */
function deleteCard(objectType, objectID, regionID)
{
    $.zui.ContextMenu.hide();

    regionID = regionID ? regionID : 0;
    setTimeout(function()
    {
        var objectLang = objectType + 'Lang';
        var result     = confirm(window[objectLang].confirmDelete) ? true : false;

        if(!result) return false;
        if(!objectID) return false;

        var url = createLink('kanban', 'deleteObjectCard', 'objectType=' + objectType + '&objectID=' + objectID + '&regionID=' + regionID);
        return $.ajax(
        {
            method:   'post',
            dataType: 'json',
            url:      url,
            success: function(data)
            {
                data = groupBy == 'default' ? data[regionID] : data[groupBy];
                updateRegion(regionID, data);
            }
        });
    }, 200)
}

/**
 * Close modal and update kanban data.
 *
 * @param  string kanbanData
 * @param  int    regionID
 * @access public
 * @return void
 */
function updateKanban(kanbanData, regionID = 0)
{
    setTimeout(function()
    {
        $.zui.closeModal();
        if(regionID && groupBy == 'default')
        {
            updateRegion(regionID, kanbanData[regionID]);
        }
        else
        {
            $('#kanban').children('.region').children("div[id^='kanban']").each(function()
            {
                var regionID = $(this).attr('data-id');
                var data     = groupBy == 'default' ? kanbanData[regionID] : kanbanData[groupBy]
                updateRegion(regionID, data);
            });
        }
    }, 200);
    resetRegionHeight('open');
}

/**
 * Shift card.
 *
 * @param  int objectID
 * @param  int fromColID
 * @param  int toColID
 * @param  int fromLaneID
 * @param  int toLaneID
 * @param  int regionID
 * @access public
 * @return void
 */
function shiftCard(objectID, fromColID, toColID, fromLaneID, toLaneID, regionID)
{
    var link  = createLink('kanban', 'ajaxMoveCard', 'cardID=' + objectID + '&fromColID=' + fromColID + '&toColID=' + toColID + '&fromLaneID=' + fromLaneID + '&toLaneID=' + toLaneID + '&execitionID=' + executionID + '&browseType=' + browseType + '&groupBy=' + groupBy + '&regionID=' + regionID + '&orderBy=' + orderBy );
    $.ajax(
    {
        method:   'post',
        dataType: 'json',
        url:       link,
        success: function(data)
        {
            data = groupBy == 'default' ? data[regionID] : data[groupBy];
            updateRegion(regionID, data);
        },
        error: function(xhr, status, error)
        {
            showErrorMessager(error || lang.timeout);
        }
    });
}

/**
 * Process minus button.
 *
 * @access public
 * @return void
 */
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

 /**
 * Create story menu
 * @returns {Object[]}
 */
function createStoryMenu(options)
{
    var $card = options.$trigger.closest('.kanban-item');
    var story = $card.data('item');

    var items      = [];
    var showAction = story.$col.type == 'backlog' || story.$col.type == 'ready' || story.$col.type == 'developing' || story.$col.type == 'developed' || story.$col.type == 'testing' || story.$col.type == 'tested' || story.$col.type == 'verified' || story.$col.type == 'released';

    if(priv.canEditStory) items.push({label: storyLang.edit, icon: 'edit', url: createLink('story', 'edit', 'storyID=' + story.id + '&kanbanGroup=' + groupBy, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '95%'}});
    if(priv.canChangeStory && showAction && story.status == 'active') items.push({label: storyLang.change, icon: 'change', url: createLink('story', 'change', 'storyID=' + story.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canCreateTask && showAction && story.status == 'active') items.push({label: executionLang.wbs, icon: 'plus', url: createLink('task', 'create', 'executionID=' + execution.id + '&storyID=' + story.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canBatchCreateTask && showAction && story.status == 'active') items.push({label: executionLang.batchWBS, icon: 'pluses', url: createLink('task', 'batchCreate', 'executionID=' + execution.id + '&storyID=' + story.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canActivateStory && story.$col.type == 'closed') items.push({label: executionLang.activate, icon: 'magic', url: createLink('story', 'activate', 'storyID=' + story.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canUnlinkStory) items.push({label: executionLang.unlinkStory, icon: 'unlink', url: createLink('execution', 'unlinkStory', 'executionID=' + execution.id + '&storyID=' + story.id + '&confirm=no&from=' + '&laneID=' + story.lane + '&columnID=' + story.column, '', 'false'), attrs: {target: 'hiddenwin'}});
    if(priv.canDeleteStory) items.push({label: storyLang.delete, icon: 'trash', onClick: function(){deleteCard('story', story.id, story.$lane.region)}});
    return items;
}

 /**
 * Create task menu
 * @returns {Object[]}
 */
function createTaskMenu(options)
{
    var $card = options.$trigger.closest('.kanban-item');
    var task  = $card.data('item');
    var items = [];
    if(priv.canEditTask) items.push({label: taskLang.edit, icon: 'edit', url: createLink('task', 'edit', 'taskID=' + task.id + '&comment=&kanbanGroup=' + groupBy, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '95%'}});
    if(priv.canRestartTask && task.$col.type == 'pause') items.push({label: taskLang.restart, icon: 'play', url: createLink('task', 'restart', 'taskID=' + task.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canPauseTask && task.$col.type == 'developing') items.push({label: taskLang.pause, icon: 'pause', url: createLink('task', 'pause', 'taskID=' + task.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canRecordEstimateTask) items.push({label: executionLang.effort, icon: 'time', url: createLink('task', 'recordEstimate', 'taskID=' + task.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canActivateTask && (task.$col.type == 'developed' || task.$col.type == 'canceled' || task.$col.type == 'closed')) items.push({label: executionLang.activate, icon: 'magic', url: createLink('task', 'activate', 'taskID=' + task.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canCreateTask) items.push({label: taskLang.copy, icon: 'copy', url: createLink('task', 'create', 'executionID=' + executionID + '&storyID=' + '0' + '&moduleID=' + '0' + '&taskID=' + task.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canCancelTask && (task.$col.type == 'wait' || task.$col.type == 'developing' || task.$col.type == 'pause')) items.push({label: taskLang.cancel, icon: 'cancel', url: createLink('task', 'cancel', 'taskID=' + task.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canDeleteTask) items.push({label: taskLang.delete, icon: 'trash', onClick: function(){deleteCard('task', task.id, task.$lane.region)}});
    return items;
}

 /**
 * Create bug menu
 * @returns {Object[]}
 */
function createBugMenu(options)
{
    var $card = options.$trigger.closest('.kanban-item');
    var bug   = $card.data('item');
    var items = [];
    if(priv.canEditBug) items.push({label: bugLang.edit, icon: 'edit', url: createLink('bug', 'edit', 'bugID=' + bug.id + '&comment=&kanbanGroup=' + groupBy, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '95%'}});
    if(priv.canResolveBug && (bug.$col.type == 'unconfirmed' || bug.$col.type == 'confirmed' || bug.$col.type == 'fixing')) items.push({label: bugLang.resolve, icon: 'checked', url: createLink('bug', 'resolve', 'bugID=' + bug.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canConfirmBug && (bug.$col.type == 'fixed' || bug.$col.type == 'testing' || bug.$col.type == 'tested')) items.push({label: bugLang.close, icon: 'off', url: createLink('bug', 'close', 'bugID=' + bug.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canConfirmBug && bug.$col.type == 'unconfirmed') items.push({label: bugLang.confirmBug, icon: 'ok', url: createLink('bug', 'confirmbug', 'bugID=' + bug.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canCopyBug) items.push({label: bugLang.copy, icon: 'copy', url: createLink('bug', 'create', 'productID=' + productID + '&branch=&extras=bugID=' + bug.id + ',regionID=' + bug.$lane.region + ',laneID=' + bug.lane + ',columnID=' + bug.column + ',executionID=' + executionID, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canToStoryBug && (bug.$col.type != 'closed')) items.push({label: bugLang.toStory, icon: 'lightbulb', url: createLink('story', 'create', 'product=' + productID + '&branch=' + '0' + '&module=' + '0' + '&story=' + '0' + '&execution=' + '0' + '&bugID=' + bug.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canActivateBug && (bug.$col.type == 'fixed') || bug.$col.type == 'testing' || bug.$col.type == 'tested' || bug.$col.type == 'closed') items.push({label: bugLang.activate, icon: 'magic', url: createLink('bug', 'activate', 'bugID=' + bug.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(priv.canDeleteBug) items.push({label: bugLang.delete, icon: 'trash', onClick: function(){deleteCard('bug', bug.id, bug.$lane.region)}});
    return items;
}

/**
 * Handle sort cards.
 *
 * @param  object event
*  @access public
 * @return void
 */
function handleSortCards(event)
{
    if(window.sortableDisabled || groupBy != 'default' || rdSearchValue != '') return;
    var newLaneID = event.element.closest('.kanban-lane').data('id');
    var newColID  = event.element.closest('.kanban-col').data('id');
    var cards     = event.element.closest('.kanban-lane-items').find('.kanban-item');
    var orders    = [];
    cards.each(function(){orders.push($(this).data('id'))});

    var url = createLink('kanban', 'sortCard', 'kanbanID=' + executionID + '&laneID=' + newLaneID + '&columnID=' + newColID + '&cards=' + orders.join(','));
    $.getJSON(url, function(response)
    {
        if(response.result === 'fail')
        {
            if(typeof response.message === 'string' && response.message.length)
            {
                bootbox.alert(response.message);
            }
            setTimeout(function(){return location.reload()}, 3000);
        }
        else
        {
            $.get(createLink('execution', 'ajaxUpdateKanban', "executionID=" + executionID + "&entertime=0&browseType=" + browseType + "&groupBy=" + groupBy + '&from=RD' + '&serachValue=' + rdSearchValue + '&orderBy=' + orderBy), function(data)
            {
                if(data && lastUpdateData !== data)
                {
                    lastUpdateData = data;
                    kanbanData     = $.parseJSON(data);
                    for(var region in kanbanData)
                    {
                        updateRegion(region, kanbanData[region]);
                    }
                }
            });
        }
    });
}

/* Define menu creators */
window.menuCreators =
{
    lane:         createLaneMenu,
    column:       createColumnMenu,
    columnCreate: createColumnCreateMenu,
    task:         createTaskMenu,
    story:        createStoryMenu,
    bug:          createBugMenu,
};

/**
 * Init kanban.
 *
 * @param  object $kanban
 * @access public
 * @return void
 */
function initKanban($kanban)
{
    var id           = $kanban.data('id');
    var region       = regions[id];

    $kanban.kanban(
    {
        data:              groupBy == 'default' ? region.groups : kanbanData[groupBy],
        maxColHeight:      'auto',
        calcColHeight:     calcColHeight,
        minColWidth:       typeof window.minColWidth === 'number' ? window.minColWidth : defaultMinColWidth,
        maxColWidth:       typeof window.maxColWidth === 'number' ? window.maxColWidth : defaultMaxColWidth,
        cardHeight:        getCardHeight(),
        fluidBoardWidth:   fluidBoard,
        displayCards:      typeof window.displayCards === 'number' ? window.displayCards : 2,
        createColumnText:  kanbanLang.createColumn,
        addItemText:       '',
        cardsPerRow:       window.kanbanScaleSize,
        onAction:          handleKanbanAction,
        onRenderLaneName:  renderLaneName,
        onRenderHeaderCol: renderHeaderCol,
        onRenderCount:     renderCount,
        droppable:         {target: findDropColumns, finish:handleFinishDrop},
        sortable:          handleSortCards,
        virtualize:        true,
        virtualCardList:   true,
        virtualRenderOptions: {container: $(window).add($('#kanbanContainer'))}
    });

    $kanban.on('click', '.action-cancel', hideKanbanAction);
    $kanban.on('scroll', function()
    {
        $.zui.ContextMenu.hide();
    });
    var kanbanMinColWidth = typeof window.minColWidth === 'number' ? window.minColWidth : defaultMinColWidth;
    if(kanbanMinColWidth < 190)
    {
        var miniColWidth = kanbanMinColWidth * 0.2;
        $('.kanban-header-col>.title>span:not(.text)').hide();
        $('.kanban-header-col>.title > span.text').css('max-width', miniColWidth + 'px');
    }
}

/**
 * Init when page ready
 */
$(function()
{
    changeStatus(execution.status);

    if($.cookie('isFullScreen') == 1) $.cookie('isFullScreen', 0);

    changeOrder = false;

    window.kanbanScaleSize = +$.zui.store.get('executionKanbanScaleSize', 1);
    $('#kanbanScaleSize').text(window.kanbanScaleSize);
    $('#kanbanScaleControl .btn[data-type="+"]').attr('disabled', window.kanbanScaleSize >= 4 ? 'disabled' : null);
    $('#kanbanScaleControl .btn[data-type="-"]').attr('disabled', window.kanbanScaleSize <= 1 ? 'disabled' : null);

    /* Make kanbanScaleControl works */
    $('#kanbanScaleControl').on('click', '.btn', function()
    {
        changeKanbanScaleSize(window.kanbanScaleSize + ($(this).data('type') === '+' ? 1 : -1));
    });

    /* Init first kanban */
    $('.kanban').each(function()
    {
        initKanban($(this));
    });

    $('.icon-chevron-double-up,.icon-chevron-double-down').on('click', function()
    {
        $(this).toggleClass('icon-chevron-double-up icon-chevron-double-down');
        $(this).parents('.region').find('.kanban').toggle();
        hideKanbanAction();
        resetRegionHeight($(this).hasClass('icon-chevron-double-up') ? 'open' : 'close');
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
            var vars  = $('.linkStoryByPlanButton').data('lane') != null ? '&extra=laneID='+ $('.linkStoryByPlanButton').data('lane') + ',columnID=' + $('.linkStoryByPlanButton').data('col') : '';
            var param = "&param=executionID=" + executionID + ",browseType=" + browseType + ",orderBy=id_asc,groupBy=" + groupBy;
            location.href = createLink('execution', 'importPlanStories', 'executionID=' + executionID + '&planID=' + planID + '&productID=0&fromMethod=kanban' + vars + param);
            $.closeModal();
        }
    });

    $('#product').change(function()
    {
        var product = $('#product').val();
        if(product)
        {
            var link = createLink('bug', 'batchCreate', 'productID=' + product + '&branch=&executionID=' + executionID, '', true);
            $('#batchCreateBugButton').attr('href', link);
        }
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

    /* Modify color's border color. */
    $(document).on('mouseout', '.color0', function()
    {
        $('.color0 .cardcolor').css('border', '1px solid #b0b0b0');
    });

    /* Modify default color's border color. */
    $(document).on('mouseover', '.color0', function()
    {
        $('.color0 .cardcolor').css('border', '1px solid #fff');
    });

    document.addEventListener('scroll', function()
    {
        hideKanbanAction();
    }, true);

    /* Init sortable */
    var sortType = '';
    var $cards   = null;
    $('#kanban').sortable(
    {
        selector: '.region, .kanban-board, .kanban-lane',
        trigger: '.region.sort > .region-header, .kanban-board.sort > .kanban-header > .kanban-group-header, .kanban-lane.sort > .kanban-lane-name',
        dropOnMouseleave: true,
        container: function($ele)
        {
            return $ele.parent();
        },
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
                $cards   = $ele.find('.kanban-item');

                return $ele.parent().children('.kanban-lane');
            }
        },
        before: function()
        {
            return !window.sortableDisabled;
        },
        start: function(e)
        {
            if(sortType == 'region')
            {
                showRegionIdList = '';
                $('.icon-chevron-double-up').each(function()
                {
                    showRegionIdList += $(this).attr('data-id') + ',';
                    $(this).attr('class', 'icon-chevron-double-down');
                });

                $('.region').find('.kanban').hide();
                hideKanbanAction();
            }
        },
        finish: function(e)
        {
            if(!e.changed) return;

            var url = '';
            var orders = [];

            if(sortType == 'region')
            {
                e.list.each(function(index, data)
                {
                  if(data.item.hasClass('region') && data.item.hasClass('sort')) orders.push(data.item.data('id'));
                });

                $('.region').each(function()
                {
                    if(showRegionIdList.includes($(this).attr('data-id')))
                    {
                        $(this).find('.icon-chevron-double-down').attr('class', 'icon-chevron-double-up');
                        $(this).find('.kanban').show();
                    }
                })

                url = createLink('kanban', 'sortRegion', 'regions=' + orders.join(','));
            }
            if(sortType == 'board')
            {
                var regionID = e.element.closest('.region').data('id');
                e.list.each(function(index, data)
                {
                  if(data.item.hasClass('kanban-board') && data.item.hasClass('sort') && regionID == data.item.parent().data().id) orders.push(data.item.data('id'));
                });

                url = createLink('kanban', 'sortGroup', 'region=' + regionID + '&groups=' + orders.join(','));
            }
            if(sortType == 'lane')
            {
                var groupID = e.element.closest('.kanban-board').data('id');
                e.list.each(function(index, data)
                {
                  if(data.item.hasClass('kanban-lane') && data.item.hasClass('sort') && groupID == data.item.data().lane.group) orders.push(data.item.data('id'));
                });

                var regionID = e.element.closest('.region').data('id');
                url          = createLink('kanban', 'sortLane', 'region=' + regionID + '&lanes=' + orders.join(','));
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
        },
        always: function(e)
        {
            if(sortType == 'lane') $cards.show();
        }
    });

    /* Ajax update kanban. */
    if(groupBy == 'default')
    {
        setInterval(function()
        {
            if(rdSearchValue == '')
            {
                $.get(createLink('execution', 'ajaxUpdateKanban', "executionID=" + executionID + "&entertime=" + entertime + "&browseType=" + browseType + "&groupBy=" + groupBy + '&from=RD&searchValue=' + rdSearchValue + '&orderBy=' + orderBy), function(data)
                {
                    if(lastUpdateData == '') lastUpdateData = data;
                    if(data && lastUpdateData !== data)
                    {
                        lastUpdateData = data;
                        kanbanData     = $.parseJSON(data);
                        for(var region in kanbanData)
                        {
                            updateRegion(region, kanbanData[region]);
                        }
                    }
                });
            }
        }, 10000);
    }

    if(groupBy != 'default')
    {
        $('.region').css('padding-bottom', '10px');
        $('.region').css('border', '0px');
    }

    resetRegionHeight('open');
});

/** Calculate column height */
function calcColHeight(col, lane, colCards, colHeight, kanban)
{
    var options = kanban.options;
    if(!options.displayCards) return colHeight;

    var displayCards = +(options.displayCards || 2);

    if (typeof displayCards !== 'number' || displayCards < 2) displayCards = 2;
    return (displayCards * (options.cardHeight + options.cardSpace) + options.cardSpace);
}

/**
 * Reset region height according to window height.
 *
 * @param  string fold
 * @access public
 * @return void
 */
function resetRegionHeight(fold)
{
    var laneCount = $('.kanban-lane').length;

    if(laneCount > 1 || $('.region').length > 0) return;

    var regionHeaderHeight = $('.region-header').outerHeight();
    if(fold == 'open')
    {
        var windowHeight  = $(window).height();
        var headerHeight  = $('#mainHeader').outerHeight();
        var mainPadding   = $('#main').css('padding-top');
        var menuHeight    = $('#mainMenu').height();
        var panelBorder   = $('.panel').css('border-top-width');
        var bodyPadding   = $('.panel-body').css('padding-top');
        var regionBorder  = $('.region').css('border-top-width');
        var regionPadding = $('.kanban').css('padding-bottom');
        var columnHeight  = $('.kanban-header').outerHeight();
        var height        = windowHeight - (parseInt(mainPadding) * 2) - menuHeight - (parseInt(bodyPadding) * 2) - headerHeight - (parseInt(panelBorder) * 2) - (parseInt(regionBorder) * 2);

        if(groupBy == 'default') $('.region').css('height', height);
        $('.kanban-lane').css('height', height - regionHeaderHeight - parseInt(regionPadding) - columnHeight);
    }
    else
    {
        $('.region').css('height', regionHeaderHeight);
    }
}

/**
 * Toggle RDSearchBox.
 *
 * @access public
 * @return void
 */
function toggleRDSearchBox()
{
    $('#rdSearchBox').toggle();

    if($('#rdSearchBox').css('display') == 'block')
    {
        $(".querybox-toggle").css("color", "#0c64eb");
    }
    else
    {
        $(".querybox-toggle").css("color", "#3c495c");
        $('#rdKanbanSearchInput').attr('value', '');
        searchCards('');
    }
}

/**
 * Search all cards.
 *
 * @param  string $value
 * @param  string order
 *
 * @access public
 * @return void
 */
function searchCards(value, order = '')
{
    rdSearchValue = value;
    orderBy       = order == '' ? orderBy : order;
    if(order != '') changeOrder = true;
    $.get(createLink('execution', 'ajaxUpdateKanban', "executionID=" + executionID + "&entertime=0&browseType=" + browseType + "&groupBy=" + groupBy + '&from=RD&searchValue=' + rdSearchValue + '&orderBy=' + orderBy), function(data)
    {
        lastUpdateData = data;
        kanbanData     = $.parseJSON(data);
        var hideAll    = true;
        $('#kanban').children('.region').children("div[id^='kanban']").each(function()
        {
            var regionID = $(this).attr('data-id');
            if(kanbanData != null) data = groupBy == 'default' ? kanbanData[regionID] : kanbanData[groupBy];

            if(rdSearchValue != '' && groupBy == 'default' && data.laneCount != 0) $(this).parent().show();
            if(rdSearchValue != '' && groupBy == 'default' && data.laneCount == 0) $(this).parent().hide();

            $(this).empty();
            hideAll = !updateRegion(regionID, data) && hideAll;
        });

        if(hideAll && rdSearchValue != '')
        {
            if($('.table-empty-tip').length == 0) $('#kanban').append('<div class="table-empty-tip"><p><span class="text-muted">' + kanbancardLang.empty + '</span></p></div>');
        }
        else
        {
            $('.table-empty-tip').remove();
        }
    });
}
