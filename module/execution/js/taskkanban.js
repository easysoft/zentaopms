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
 * Change view.
 *
 * @param  string $view
 * @access public
 * @return void
 */
function changeView(view)
{
    var link = createLink('execution', 'taskKanban', "executionID=" + executionID + '&type=' + view);
    location.href = link;
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
        var link = createLink('task', 'assignto', 'executionID=' + executionID + '&id=' + objectID + '&kanbanGroup=default&from=taskkanban', '', true);
    }
    if(objectType == 'story')
    {
        if(!priv.canAssignStory && !user) return $noPrivAndNoAssigned;
        var link = createLink('story', 'assignto', 'id=' + objectID + '&kanbanGroup=default&from=taskkanban', '', true);
    }
    if(objectType == 'bug')
    {
        if(!priv.canAssignBug && !user) return $noPrivAndNoAssigned;
        var link = createLink('bug', 'assignto', 'id=' + objectID + '&kanbanGroup=default&from=taskkanban', '', true);
    }

    if(!user) return objectStatus == 'closed' ? '' : $('<a class="avatar has-text ' + avatarSizeClass + ' avatar-circle iframe" title="' + noAssigned + '" style="background: #ccc" href="' + link + '"><i class="icon icon-person"></i></a>');

    if(typeof user === 'string') user = {account: user};
    if(!user.avatar && window.userList && window.userList[user.account]) user = window.userList[user.account];

    var $noPrivAvatar = $('<div class="avatar has-text ' + avatarSizeClass + ' avatar-circle" />').avatar({user: user});
    if(objectType == 'task'  && !priv.canAssignTask)  return $noPrivAvatar;
    if(objectType == 'story' && !priv.canAssignStory) return $noPrivAvatar;
    if(objectType == 'bug'   && !priv.canAssignBug)   return $noPrivAvatar;

    var realname = user.realname ? user.realname : user.account;
    var title = user.title ? user.title : realname;
    return objectStatus == 'closed' ? '' : $('<a class="avatar has-text ' + avatarSizeClass + ' avatar-circle iframe" title="' + title + '" href="' + link + '"/>').avatar({user: user});
}

/**
 * Render deadline
 * @param {String|Date} deadline Deadline
   @param {string}      status
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
    if($item.attr('data-scale-size') !== scaleSize) $item.empty().attr('data-scale-size', scaleSize);

    if(scaleSize <= 3)
    {
        var $title = $item.find('.title');
        if(!$title.length)
        {
            $title = $('<a class="title iframe" data-width="95%">' + (scaleSize <= 1 ? '<i class="icon icon-lightbulb text-muted"></i> ' : '') + '<span class="text"></span></a>')
                    .attr('href', $.createLink('execution', 'storyView', 'storyID=' + item.id, '', true));
            $title.appendTo($item);
        }
        var title = searchValue != '' ? "<span class='text'>" + item.title.replaceAll(searchValue, "<span class='text-danger'>" + searchValue + "</span>") + "</span>": "<span class='text'>" + item.title + "</span>";
        $title.attr('title', item.title).find('.text').replaceWith(title);
    }

    if(scaleSize <= 2)
    {
        var idHtml     = scaleSize <= 1 ? ('<span class="info info-id text-muted">#' + item.id + '</span>') : '';
        var priHtml    = '<span class="info info-pri' + (item.pri ? ' label-pri label-pri-' + item.pri : '') + '" title="' + item.pri + '">' + item.pri + '</span>';
        var hoursHtml  = (item.estimate && scaleSize <= 1) ? ('<span class="info info-estimate text-muted">' + item.estimate + hourUnit +'</span>') : '';
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
        if(!$actions.length && item.menus && item.menus.length)
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

    if($.cookie('isFullScreen') == 1) hideAllAction();
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
    if($item.attr('data-scale-size') !== scaleSize) $item.empty().attr('data-scale-size', scaleSize);

    if(scaleSize <= 3)
    {
        var $title = $item.find('.title');
        if(!$title.length)
        {
            $title = $('<a class="title iframe" data-width="95%">' + (scaleSize <= 1 ? '<i class="icon icon-bug text-muted"></i> ' : '') + '<span class="text"></span></a>')
                    .attr('href', $.createLink('bug', 'view', 'bugID=' + item.id, '', true));
            $title.appendTo($item);
        }
        var title = searchValue != '' ? "<span class='text'>" + item.title.replaceAll(searchValue, "<span class='text-danger'>" + searchValue + "</span>") + "</span>": "<span class='text'>" + item.title + "</span>";
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
        if(!$actions.length && item.menus && item.menus.length)
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

    if($.cookie('isFullScreen') == 1) hideAllAction();
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
    if($item.attr('data-scale-size') !== scaleSize)  $item.empty().attr('data-scale-size', scaleSize);

    if(scaleSize <= 3)
    {
        var $title = $item.find('.title');
        if(!$title.length)
        {
            $title = $('<a class="title iframe" data-width="95%">' + (scaleSize <= 1 ? '<i class="icon icon-checked text-muted"></i> ' : '') + '<span class="text"></span></a>').attr('href', $.createLink('task', 'view', 'taskID=' + item.id, '', true));
            $title.appendTo($item);
        }
        var name = searchValue != '' ? "<span class='text'>" + item.name.replaceAll(searchValue, "<span class='text-danger'>" + searchValue + "</span>") + "</span>": "<span class='text'>" + item.name + "</span>";
        $title.attr('title', item.name).find('.text').replaceWith(name);
    }

    if(scaleSize <= 2)
    {
        var priHtml    = '<span class="info info-pri' + (item.pri ? ' label-pri label-pri-' + item.pri : '') + '" title="' + item.pri + '">' + item.pri + '</span>';
        var hoursHtml  = scaleSize <= 1 && item.status != 'wait' ? ('<span class="info info-estimate text-muted">' + taskLang.leftAB + ' ' + item.left + 'h</span>') : ('<span class="info info-estimate text-muted">' + taskLang.estimateAB + ' ' + item.estimate + 'h</span>');
        var avatarHtml = '';
        if(item.assignedTo == '' && item.mode == 'multi') avatarHtml = renderUserAvatar({title: item.teamMembers, realname: teamWords}, 'task', item.id, '', col.type);
        else avatarHtml = renderUserAvatar(item.assignedTo, 'task', item.id, '', col.type);
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

    if(canBeChanged && scaleSize <= 1)
    {
        var $actions = $item.find('.actions');
        if(!$actions.length && item.menus && item.menus.length)
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

    if($.cookie('isFullScreen') == 1) hideAllAction();
    return $item;
}

/* Add column renderer */
addColumnRenderer('story', renderStoryItem);
addColumnRenderer('bug',   renderBugItem);
addColumnRenderer('task',  renderTaskItem);

/**
 * Render column count
 * @param {JQuery} $count Kanban count element
 * @param {number} count  Column cards count
 * @param {number} col    Column object
 * @param {Object} kanban Kanban intance
 */
function renderColumnCount($count, count, col)
{
    if(groupBy == 'story' && col.type == 'story')
    {
        var orderButton = '<a class="btn btn-link action storyColumn ' + (changeOrder ? 'text-primary' : '') + '" type="button" data-toggle="dropdown">'
            + "<i class='icon icon-swap'></i>"
            + '</a>'
            + '<ul class="dropdown-menu">';
        for(var order in kanbanLang.orderList) orderButton += '<li class="' + (order == orderBy ? 'active' : '') + '"><a href="###" onclick="searchCards(searchValue, \'' + order + '\')">' + kanbanLang.orderList[order] + '</a></li>';
        orderButton += '</ul>';

        $count.parent().next().html(orderButton);
        $count.parent().next().addClass('createButton');
        $count.hide();
        return;
    }

    var text     = count + '/' + (col.limit < 0 ? '<i class="icon icon-infinite"></i>' : col.limit);
    var limitTip = '';
    if(col.limit >= 0 && count > col.limit)
    {
        limitTip = 'data-original-title="' + kanbanLang.limitExceeded + '"';
    }
    $count.html(text + '<i class="icon icon-arrow-up" data-toggle="tooltip" ' + limitTip + '"></i>');

    if(col.limit != -1 && col.limit < count)
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
 * Render header column
 * @param {JQuery} $col    Header column element
 * @param {Object} col     Header column object
 * @param {JQuery} $header Header element
 * @param {Object} kanban  Kanban object
 */
function renderHeaderCol($col, col, $header, kanban)
{
    if(col.asParent) $col = $col.children('.kanban-header-col');
    if($col.children('.actions').context != undefined || (groupBy == 'story' && col.type == 'story')) return;

    var $actions = $('<div class="actions createButton" />');
    var printStoryButton =  printTaskButton = printBugButton = false;
    if(priv.canCreateStory || priv.canBatchCreateStory || priv.canLinkStory || priv.canLinkStoryByPlan) printStoryButton = true;
    if(priv.canCreateTask  || priv.canBatchCreateTask) printTaskButton = true;
    if(priv.canCreateBug   || priv.canBatchCreateBug)  printBugButton  = true;

    if(col.type === 'backlog' || col.type === 'wait' || col.type == 'unconfirmed')
    {
        var tips = productID ? '' : 'onclick="tips()"';
        $actions.append([
                '<a data-contextmenu="columnCreate" data-type="' + col.type + '" data-kanban="' + kanban.id + '" data-parent="' + (col.parentType || '') +  '" class="text-primary"' + ((col.laneType !== 'task') ? tips : '') + '>',
                '<i class="icon icon-expand-alt"></i>',
                '</a>'
        ].join(''));
    }

    if(priv.canSetWIP || priv.canEditName)
    {
        $actions.append([
                '<a data-contextmenu="column" title="' + kanbanLang.moreAction + '" data-type="' + col.type + '" data-kanban="' + kanban.id + '" data-parent="' + (col.parentType || '') +  '">',
                '<i class="icon icon-ellipsis-v"></i>',
                '</a>'
        ].join(''));
    }

    $actions.appendTo($col);
}

/**
 * Render lane name
 * @param {JQuery} $name    Name element
 * @param {Object} lane     Lane object
 * @param {JQuery} $kanban  $kanban element
 * @param {Object} columns  Kanban columns
 * @param {Object} kanban   Kanban object
 */
function renderLaneName($name, lane, $kanban, columns, kanban)
{
    if(groupBy == 'story')
    {
        $name.hide();
        return;
    }
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
 * @param {string} kanbanID Kanban id
 * @param {Object} data     Kanban data
 */
function updateKanban(kanbanID, data)
{
    var $kanban = $('#kanban-' + kanbanID);
    if(!$kanban.length) return;

    if(data == null)
    {
        $kanban.hide();
        return false;
    }
    $kanban.show();

    $kanban.data('zui.kanban').render(data);
    resetKanbanHeight();
    return true;
}

/**
 * Create kanban in page
 * @param {string} kanbanID Kanban id
 * @param {Object} data     Kanban data
 * @param {Object} options  Kanban options
 */
function createKanban(kanbanID, data, options)
{
    var $kanban = $('#kanban-' + kanbanID);
    if($kanban.length) return updateKanban(kanbanID, data);

    $kanban = $('<div id="kanban-' + kanbanID + '" data-id="' + kanbanID + '"></div>');
    $('#kanbans').append($kanban);
    $kanban.kanban($.extend({data: data, calcColHeight: calcColHeight, displayCards: typeof window.displayCards === 'number' ? window.displayCards : 2}, options));
}

/**
 * Hide all actions.
 *
 * @access public
 * @return void
 */
function hideAllAction()
{
    $('.actions').hide();
    $(".title, .avatar.iframe").attr("disabled", true).css("pointer-events", "none");
}

/**
 * Display the kanban in full screen.
 *
 * @access public
 * @return void
 */
function fullScreen()
{
    $('#kanbans .kanban-header').addClass('headerTop');
    var element       = document.getElementById('kanbanContainer');
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullScreen;
    if(requestMethod)
    {
        var afterEnterFullscreen = function()
        {
            $('#kanbanContainer').addClass('scrollbar-hover');
            hideAllAction();
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
    $('#kanbans .kanban-header').removeClass('headerTop');
    $('#kanbanContainer').removeClass('scrollbar-hover');
    $('.actions').show();
    $(".title, .avatar.iframe").attr("disabled", false).css("pointer-events", "auto");
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
            backlog: ['ready'],
            ready: ['backlog'],
            tested: ['verified'],
            verified: ['tested', 'released'],
            released: ['verified', 'closed'],
            closed: ['released'],
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

    $.zui.ContextMenu.hide();

    if(!kanbanRules) return $root.find('.kanban-lane-col:not([data-type="' + col.type + '"])');

    var colRules = kanbanRules[col.type];
    var lane     = $col.closest('.kanban-lane').data('lane');
    return $root.find('.kanban-lane-col').filter(function()
    {
        if(!colRules) return false;
        if(colRules === true) return true;
        if($.cookie('isFullScreen') == 1) return false;

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
 * Change card's type by changing column.
 *
 * @param  int    $cardID
 * @param  int    $fromColID
 * @param  int    $toColID
 * @param  int    $fromLaneID
 * @param  int    $toLaneID
 * @param  string $cardType
 * @param  string $fromColType
 * @param  string $toColType
 * @access public
 * @return void
 */
function changeCardColType(cardID, fromColID, toColID, fromLaneID, toLaneID, cardType, fromColType, toColType)
{
    var objectID   = cardID;
    var showIframe = false;
    var moveCard   = false;

    /* Task lane. */
    if(cardType == 'task')
    {
        if(toColType == 'developed')
        {
            if((fromColType == 'developing' || fromColType == 'wait') && priv.canFinishTask)
            {
                var link   = createLink('task', 'finish', 'taskID=' + objectID + '&extra=from=' + 'taskkanban', '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'pause')
        {
            if(fromColType == 'developing' && priv.canPauseTask)
            {
                var link = createLink('task', 'pause', 'taskID=' + objectID + '&extra=from=' + 'taskkanban', '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'developing')
        {
            if((fromColType == 'canceled' || fromColType == 'closed' || fromColType == 'developed') && priv.canActivateTask)
            {
                var link = createLink('task', 'activate', 'taskID=' + objectID + '&extra=from=' + 'taskkanban', '', true);
                showIframe = true;
            }
            if(fromColType == 'pause' && priv.canActivateTask)
            {
                var link = createLink('task', 'restart', 'taskID=' + objectID + '&from=' + 'taskkanban', '', true);
                showIframe = true;
            }
            if(fromColType == 'wait' && priv.canStartTask)
            {
                var link = createLink('task', 'start', 'taskID=' + objectID + '&extra=from=' + 'taskkanban', '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'canceled')
        {
            if((fromColType == 'developing' || fromColType == 'wait' || fromColType == 'pause') && priv.canCancelTask)
            {
                var link = createLink('task', 'cancel', 'taskID=' + objectID + '&extra=from=' + 'taskkanban', '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'closed')
        {
            if((fromColType == 'developed' || fromColType == 'canceled') && priv.canCloseTask)
            {
                var link = createLink('task', 'close', 'taskID=' + objectID + '&extra=from=' + 'taskkanban', '', true);
                showIframe = true;
            }
        }
    }

    /* Bug lane. */
    if(cardType == 'bug')
    {
        if(toColType == 'confirmed')
        {
            if(fromColType == 'unconfirmed' && priv.canConfirmBug)
            {
                var link = createLink('bug', 'confirmBug', 'bugID=' + objectID + '&extra=&from=taskkanban', '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'fixing')
        {
            if(fromColType == 'confirmed' || fromColType == 'unconfirmed') moveCard = true;
            if((fromColType == 'closed' || fromColType == 'fixed' || fromColType == 'testing' || fromColType == 'tested') && priv.canActivateBug)
            {
                var link = createLink('bug', 'activate', 'bugID=' + objectID + '&extra=&from=taskkanban', '', true);
                showIframe = true;
            }
        }
        else if(toColType == 'fixed')
        {
            if(fromColType == 'fixing' || fromColType == 'confirmed' || fromColType == 'unconfirmed')
            {
                var link = createLink('bug', 'resolve', 'bugID=' + objectID + '&extra=&from=taskkanban', '', true);
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
                var link = createLink('bug', 'close', 'bugID=' + objectID + '&extra=&from=taskkanban', '', true);
                showIframe = true;
            }
        }

        if(moveCard)
        {
            var link  = createLink('kanban', 'ajaxMoveCard', 'cardID=' + objectID + '&fromColID=' + fromColID + '&toColID=' + toColID + '&fromLaneID=' + fromLaneID + '&toLaneID=' + toLaneID + '&execitionID=' + executionID + '&browseType=' + browseType + '&groupBy=' + groupBy);
            $.get(link, function(data)
            {
                if(data)
                {
                    kanbanGroup = $.parseJSON(data);
                    if(groupBy == 'default')
                    {
                        updateKanban('bug', kanbanGroup.bug);
                    }
                    else
                    {
                        updateKanban(browseType, kanbanGroup[groupBy]);
                    }
                }
            })
        }
    }

    /* Story lane. */
    if(cardType == 'story')
    {
        if(toColType == 'closed' && priv.canCloseStory)
        {
            var link = createLink('story', 'close', 'storyID=' + objectID + '&from=taskkanban', '', true);
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
                            ajaxMoveCard(objectID, fromColID, toColID, fromLaneID, toLaneID);
                        }
                    }
                });
            }
            else
            {
                ajaxMoveCard(objectID, fromColID, toColID, fromLaneID, toLaneID);
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
 * @access public
 * @return void
 */
function ajaxMoveCard(objectID, fromColID, toColID, fromLaneID, toLaneID)
{
    var link = createLink('kanban', 'ajaxMoveCard', 'cardID=' + objectID + '&fromColID=' + fromColID + '&toColID=' + toColID + '&fromLaneID=' + fromLaneID + '&toLaneID=' + toLaneID + '&execitionID=' + executionID + '&browseType=' + browseType + '&groupBy=' + groupBy);
    $.get(link, function(data)
    {
        if(data)
        {
            kanbanGroup = $.parseJSON(data);
            if(groupBy == 'default')
            {
                updateKanban('story', kanbanGroup.story);
            }
            else
            {
                updateKanban(browseType, kanbanGroup[groupBy]);
            }
        }
    });
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
    if(!event.target) return;

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

    changeCardColType(cardID, oldCol.id, newCol.id, oldLane.id, newLane.id, cardType, fromColType, toColType);
}

var kanbanActionHandlers =
{
    dropItem: handleDropTask
};

/**
 * Handle kanban action.
 *
 * @param  string $action
 * @param  object $element
 * @param  object $event
 * @param  object $kanban
 * @access public
 * @return void
 */
function handleKanbanAction(action, $element, event, kanban)
{
    $('.kanban').attr('data-action-enabled', action);
    var handler = kanbanActionHandlers[action];
    if(handler) handler($element, event, kanban);
}

/**
 * Handle finish drop task
 * @param {Object} event Event object
 * @returns {void}
 */
function handleFinishDrop(event)
{
    $('#kanbans').find('.can-drop-here').removeClass('can-drop-here');
}

/**
 * Create column menu
 * @returns {Object[]}
 */
function createColumnMenu(options)
{
    var $col     = options.$trigger.closest('.kanban-col');
    var col      = $col.data('col');
    var kanbanID = options.kanban;

	var items = [];
	if(priv.canEditName) items.push({label: executionLang.editName, url: $.createLink('kanban', 'setColumn', 'col=' + col.id + '&executionID=' + executionID + '&from=execution'), className: 'iframe', attrs: {'data-width': '500px'}})
	if(priv.canSetWIP) items.push({label: executionLang.setWIP, url: $.createLink('kanban', 'setWIP', 'col=' + col.id + '&executionID=' + executionID + '&from=execution'), className: 'iframe', attrs: {'data-width': '500px'}})
	//if(priv.canSortCards) items.push({label: executionLang.sortColumn, items: ['按ID倒序', '按ID顺序'], className: 'iframe', onClick: handleSortColCards})
    return items;
}

/**
 * Create column create button menu
 * @returns {Object[]}
 */
function createColumnCreateMenu(options)
{
    var $col  = options.$trigger.closest('.kanban-col');
    var col   = $col.data('col');
    var items = [];

    if(col.laneType == 'story')
    {
        if(priv.canCreateStory) items.push({label: storyLang.create, url: $.createLink('story', 'create', 'productID=' + productID + '&branch=0&moduleID=0&storyID=0&objectID=' + executionID, '', true), className: 'iframe', attrs: {'data-width': '80%'}});
        if(priv.canBatchCreateStory) items.push({label: executionLang.batchCreateStory, url: $.createLink('story', 'batchcreate', 'productID=' + productID + '&branch=0&moduleID=0&storyID=0&executionID=' + executionID, '', true), className: 'iframe', attrs: {'data-width': '90%'}});
        if(priv.canLinkStory) items.push({label: executionLang.linkStory, url: $.createLink('execution', 'linkStory', 'executionID=' + executionID, '', true), className: 'iframe', attrs: {'data-width': '90%'}});
        if(priv.canLinkStoryByPlan) items.push({label: executionLang.linkStoryByPlan, url: '#linkStoryByPlan', 'attrs' : {'data-toggle': 'modal'}});
    }
    else if(col.laneType == 'bug')
    {
        if(priv.canCreateBug) items.push({label: bugLang.create, url: $.createLink('bug', 'create', 'productID=0&moduleID=0&extra=executionID=' + executionID, '', true), className: 'iframe', attrs: {'data-width': '80%'}});
        if(priv.canBatchCreateBug)
        {
            if(productNum > 1) items.push({label: bugLang.batchCreate, url: '#batchCreateBug', 'attrs' : {'data-toggle': 'modal'}});
            else items.push({label: bugLang.batchCreate, url: $.createLink('bug', 'batchcreate', 'productID=' + productID + '&moduleID=0&executionID=' + executionID, '', true), className: 'iframe', attrs: {'data-width': '90%'}});
        }
    }
    else
    {
        if(priv.canCreateTask) items.push({label: taskLang.create, url: $.createLink('task', 'create', 'executionID=' + executionID, '', true), className: 'iframe', attrs: {'data-width': '80%'}});
        if(priv.canBatchCreateTask) items.push({label: taskLang.batchCreate, url: $.createLink('task', 'batchcreate', 'executionID=' + executionID, '', true), className: 'iframe', attrs: {'data-width': '90%'}});
        if(priv.canImportBug && canImportBug) items.push({label: executionLang.importBug, url: $.createLink('execution', 'importBug', 'executionID=' + executionID, '', true), className: 'iframe', attrs: {'data-width': '90%'}});
    }
    return items;
}

/**
 * Create lane menu
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
    if(priv.canSetLane)  items.push({label: kanbanLang.setLane, icon: 'edit', url: $.createLink('kanban', 'setLane', 'lane=' + lane.laneID + '&executionID=' + executionID + '&from=execution'), className: 'iframe'});

    var bounds = options.$trigger[0].getBoundingClientRect();
    items.$options = {x: bounds.right, y: bounds.top};
    return items;
}

/**
 * Create story menu
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

        if(this.icon == 'unlink' || this.icon == 'trash') item = {label: this.label, icon: this.icon, url: this.url, attrs: {'target': 'hiddenwin'}};
        items.push(item);
    });

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
    $.each(bug.menus, function()
    {
        var item = {label: this.label, icon: this.icon, url: this.url, attrs: {'data-toggle': 'modal', 'data-type': 'iframe'}};
        if(this.size) item.attrs['data-width'] = this.size;

        if(this.icon == 'trash') item = {label: this.label, icon: this.icon, url: this.url, attrs: {'target': 'hiddenwin'}};
        items.push(item);
    });

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
    $.each(task.menus, function()
    {
        var item = {label: this.label, icon: this.icon, url: this.url, attrs: {'data-toggle': 'modal', 'data-type': 'iframe'}};
        if(this.size) item.attrs['data-width'] = this.size;

        if(this.icon == 'trash') item = {label: this.label, icon: this.icon, url: this.url, attrs: {'target': 'hiddenwin'}};
        items.push(item);
    });

    return items;
}

/** Resize kanban container size */
function resizeKanbanContainer()
{
    var $container = $('#kanbanContainer');
    var maxHeight = window.innerHeight - 98 - 15;
    if($.cookie('isFullScreen') == 1) maxHeight = window.innerHeight - 15;
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

/** Get card height */
function getCardHeight()
{
    return [59, 59, 62, 62, 47][window.kanbanScaleSize];
}

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

    $('#kanbans').children('.kanban').each(function()
    {
        var kanban = $(this).data('zui.kanban');
        if(!kanban) return;
        kanban.setOptions({cardsPerRow: newScaleSize, cardHeight: getCardHeight()});
    });

    resetKanbanHeight();
    return newScaleSize;
}

/** Affix kanban board header */
window.affixKanbanHeader = function($kanbanBoard, affixed)
{
    var $header = $kanbanBoard.children('.kanban-header');
    var $headerCols = $header.children('.kanban-header-cols');
    var headerStyle = {width: '', left: 0};
    var headerColsStyle = {width: '', marginLeft: ''};
    if(affixed)
    {
        var $kanban = $('#kanbanContainer');
        var kanbanBounding = $kanban[0].getBoundingClientRect();
        var kanbanBoardBounding = $kanbanBoard[0].getBoundingClientRect();
        var laneNameWidth = +$headerCols.css('left').replace('px', '');
        headerStyle.width = kanbanBounding.width;
        headerStyle.left = kanbanBounding.left;
        headerColsStyle.width = kanbanBoardBounding.width - laneNameWidth;
        headerColsStyle.marginLeft = kanbanBoardBounding.left - kanbanBounding.left;
    }
    $header.css(headerStyle);
    $headerCols.css(headerColsStyle);
    $kanbanBoard.toggleClass('kanban-affixed', !!affixed);
    $kanbanBoard.css('padding-top', affixed ? $header.outerHeight() : '');
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
    if(groupBy != 'default' || searchValue != '') return;
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
            $.get(createLink('execution', 'ajaxUpdateKanban', "executionID=" + executionID + "&entertime=0&browseType=" + browseType + "&groupBy=" + groupBy + '&from=execution' + '&searchValue=' + searchValue + '&orderBy=' + orderBy), function(data)
            {
                if(data && lastUpdateData !== data)
                {
                    lastUpdateData = data;
                    kanbanGroup    = $.parseJSON(data);
                    var kanbanLane = '';
                    for(var i in kanbanList)
                    {
                        if(kanbanList[i] == 'story') kanbanLane = kanbanGroup.story;
                        if(kanbanList[i] == 'bug')   kanbanLane = kanbanGroup.bug;
                        if(kanbanList[i] == 'task')  kanbanLane = kanbanGroup.task;

                        if(browseType == kanbanList[i] || browseType == 'all') updateKanban(kanbanList[i], kanbanLane);
                    }
                }
            });
        }
    });
}

/* Example code: */
$(function()
{
    $.cookie('isFullScreen', 0);

    window.kanbanScaleSize = +$.zui.store.get('executionKanbanScaleSize', 1);
    $('#kanbanScaleSize').text(window.kanbanScaleSize);
    $('#kanbanScaleControl .btn[data-type="+"]').attr('disabled', window.kanbanScaleSize >= 4 ? 'disabled' : null);
    $('#kanbanScaleControl .btn[data-type="-"]').attr('disabled', window.kanbanScaleSize <= 1 ? 'disabled' : null);

    changeOrder = false;
    /* Common options */　
    var commonOptions =
    {
        maxColHeight:         'auto',
        minColWidth:          typeof window.minColWidth === 'number' ? window.minColWidth : defaultMinColWidth,
        maxColWidth:          typeof window.maxColWidth === 'number' ? window.maxColWidth : defaultMaxColWidth,
        cardHeight:           getCardHeight(),
        showCount:            true,
        showZeroCount:        true,
        fluidBoardWidth:      fluidBoard,
        cardsPerRow:          window.kanbanScaleSize,
        virtualize:           true,
        onAction:             handleKanbanAction,
        virtualRenderOptions: {container: '#kanbanContainer>.panel-body,#kanbanContainer'},
        virtualCardList:      true,
        onRenderHeaderCol: renderHeaderCol,
        onRenderLaneName:  renderLaneName,
        onRenderCount:     renderColumnCount,
        sortable:          handleSortCards,
    };
    if(canBeChanged) commonOptions.droppable = {target: findDropColumns, finish: handleFinishDrop};

    /* Create kanban */
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
        /* Create kanban by group. */
        createKanban(browseType, kanbanGroup[groupBy], commonOptions);
    }

    /* Init iframe modals */
    $(document).on('click', '#kanbans .iframe,.contextmenu-menu .iframe', function(event)
    {
        var $link = $(this);
        if($link.data('zui.modaltrigger')) return;
        $link.modalTrigger({show: true});
        event.preventDefault();
    });

    /* Init contextmenu */
    $('#kanbans').on('click', '[data-contextmenu]', function(event)
    {
        var $trigger    = $(this);
        var menuType    = $trigger.data('contextmenu');

        var menuCreator = window.menuCreators[menuType];
        if(!menuCreator) return;

        var options = $.extend({event: event, $trigger: $trigger}, $trigger.data());
        var items   = menuCreator(options);
        if(!items || !items.length) return;

        $.zui.ContextMenu.show(items, items.$options || {event: event});
    });

    /* Make kanbanScaleControl works */
    $('#kanbanScaleControl').on('click', '.btn', function()
    {
        changeKanbanScaleSize(window.kanbanScaleSize + ($(this).data('type') === '+' ? 1 : -1));
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
            var param = "&param=executionID=" + executionID + ",browseType=" + browseType + ",orderBy=id_asc,groupBy=" + groupBy;
            location.href = createLink('execution', 'importPlanStories', 'executionID=' + executionID + '&planID=' + planID + '&productID=0&fromMethod=taskKanban&extra=' + param);
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

    document.addEventListener('scroll', function()
    {
        $('.storyColumn').parent().removeClass('open');
    }, true);

    $('#type_chosen .chosen-single span').prepend('<i class="icon-kanban"></i>');
    $('#group_chosen .chosen-single span').prepend(kanbanLang.laneGroup + ': ');

    /* Ajax update kanban. */
    lastUpdateData = '';
    setInterval(function()
    {
        $.get(createLink('execution', 'ajaxUpdateKanban', "executionID=" + executionID + "&entertime=" + entertime + "&browseType=" + browseType + "&groupBy=" + groupBy + '&from=execution&searchValue=' + searchValue + '&orderBy=' + orderBy), function(data)
        {
            if(lastUpdateData == '') lastUpdateData = data;
            if(data && lastUpdateData !== data)
            {
                lastUpdateData = data;
                kanbanGroup = $.parseJSON(data);
                if(groupBy == 'default')
                {
                    var kanbanLane = '';
                    for(var i in kanbanList)
                    {
                        if(kanbanList[i] == 'story') kanbanLane = kanbanGroup.story;
                        if(kanbanList[i] == 'bug')   kanbanLane = kanbanGroup.bug;
                        if(kanbanList[i] == 'task')  kanbanLane = kanbanGroup.task;

                        if(browseType == kanbanList[i] || browseType == 'all') updateKanban(kanbanList[i], kanbanLane);
                    }
                }
                else
                {
                    updateKanban(browseType, kanbanGroup[groupBy]);
                }
            }
        });
    }, 10000);
    resetKanbanHeight();
    var kanbanMinColWidth = typeof window.minColWidth === 'number' ? window.minColWidth : defaultMinColWidth;
    if(kanbanMinColWidth < 190)
    {
        var miniColWidth = kanbanMinColWidth * 0.2;
        $('.kanban-header-col>.title>span:not(.text)').hide();
        $('.kanban-header-col>.title > span.text').css('max-width', miniColWidth + 'px');
    }
    $('[data-toggle="tooltip"]').tooltip();
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

    var link = createLink('execution', 'taskKanban', "executionID=" + executionID + '&type=' + type);
    location.href = link;
});

$('.c-group').change(function()
{
    $('.c-group').show();

    var type  = $('#type').val();
    var group = $('#group').val();
    var link  = createLink('execution', 'taskKanban', 'executionID=' + executionID + '&type=' + type + '&orderBy=order_asc' + '&groupBy=' + group);
    location.href = link;
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

/* Hide contextmenu when page scroll */
$('.panel-body').scroll(function()
{
    $.zui.ContextMenu.hide();
});

/**
 * Reset kanban height according to window height.
 *
 * @access public
 * @return void
 */
function resetKanbanHeight()
{
    var laneCount = $('.kanban-lane').length;

    if(laneCount > 1) return;

    var windowHeight = $(window).height();
    var headerHeight = $('#mainHeader').outerHeight();
    var mainPadding  = $('#main').css('padding-top');
    var menuHeight   = $('#mainMenu').height();
    var panelBorder  = $('.panel').css('border-top-width');
    var bodyPadding  = $('.panel-body').css('padding-top');
    var columnHeight = $('.kanban-header').outerHeight();
    var height       = windowHeight - headerHeight - (parseInt(mainPadding) * 2) - menuHeight - (parseInt(panelBorder) * 2) - (parseInt(bodyPadding) * 2) - columnHeight;

    $('.kanban-lane').css('height', height -2);
}

$(document).on('click', '.dropdown-menu', function()
{
    $.zui.ContextMenu.hide();
});

/**
 * Toggle kanban search box.
 *
 * @access public
 * @return void
 */
function toggleSearchBox()
{
    $('#searchBox').toggle();

    if($('#searchBox').css('display') == 'block')
    {
        $(".querybox-toggle").css("color", "#0c64eb");
    }
    else
    {
        $(".querybox-toggle").css("color", "#3c495c");
        $('#taskKanbanSearchInput').attr('value', '');
        searchCards('');
    }
}

/**
 * Search kanban cards.
 *
 * @param  string value
 * @param  string order
 *
 * @access public
 * @return void
 */
function searchCards(value, order = '')
{
    searchValue = value;
    orderBy     = order == '' ? orderBy : order;
    if(order != '') changeOrder = true;
    $.get(createLink('execution', 'ajaxUpdateKanban', "executionID=" + executionID + "&entertime=0&browseType=" + browseType + "&groupBy=" + groupBy + '&from=execution&searchValue=' + value + '&orderBy=' + orderBy), function(data)
    {
        lastUpdateData = data;
        var kanbanData = $.parseJSON(data);
        var hideAll    = true;
        if(groupBy == 'default')
        {
            var kanbanLane = '';
            for(var i in kanbanList)
            {
                if(kanbanList[i] == 'story') kanbanLane = kanbanData.story;
                if(kanbanList[i] == 'bug')   kanbanLane = kanbanData.bug;
                if(kanbanList[i] == 'task')  kanbanLane = kanbanData.task;

                if(browseType == kanbanList[i] || browseType == 'all') hideAll = !updateKanban(kanbanList[i], kanbanLane) && hideAll;
            }
        }
        else
        {
            hideAll = !updateKanban(browseType, kanbanData[groupBy]) && hideAll;
        }

        if(hideAll)
        {
            $("#emptyBox").removeClass('hidden');
            $("#kanbanContainer .panel-body").addClass('hidden');
        }
        else
        {
            $("#emptyBox").addClass('hidden');
            $("#kanbanContainer .panel-body").removeClass('hidden');
        }
    });
}
