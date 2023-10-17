/**
 * Load more.
 *
 * @param  string $type
 * @param  int    $regionID
 * @access public
 * @return void
 */
function loadMore(type, regionID)
{
    var method   = 'viewArchived' + type;
    var selector = '#archived' + type + 's';
    var link     = createLink('kanban', method, 'regionID=' + regionID);
    $(selector).load(link, function()
    {
        var windowHeight  = $(window).height();
        var affixedHeight = $('#regionTabs.affixed').height() + $('#kanbanContainer .kanban-affixed .kanban-cols').height();
        $(selector + ' .panel-body').css('height', windowHeight - affixedHeight);
        $(selector).css('top', affixedHeight);
        $(selector).animate({right: 0}, 500);
    });
}

/**
 * Hide kanban actions.
 *
 * @access public
 * @return void
 */
function hideAllAction()
{
    $('.actions').hide();
    $('.action').hide();
    $('.kanban-group-header').hide();
    $(".title").attr("disabled", true).css("pointer-events", "none");
    $('.kanban-col.kanban-header-col').css('padding', '0px 0px 0px 0px');
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
            hideAllAction();
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
    $('.region-actions > div > .action').show();
    $(".title").attr("disabled", false).css("pointer-events", "auto");
    if(!CRKanban && kanban.status == 'closed') return;
    $('#kanbanContainer').removeClass('fullscreen')
        .off('scroll', tryUpdateKanbanAffix);
    $('.actions').show();
    $('.action').show();
    $('.kanban-group-header').show();
    $('.kanban-col.kanban-header-col').css('padding', '0px 30px');
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

/**
 * Render header of a column.
 */
function renderHeaderCol($column, column, $header, kanbanData)
{
    /* Render group header. */
    var privs       = kanbanData.actions;
    var columnPrivs = $column.data().col.actions;

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

    var laneID       = column.$kanbanData.lanes[0].id ? column.$kanbanData.lanes[0].id : 0;
    var printMoreBtn = (columnPrivs.includes('setColumn') || columnPrivs.includes('setWIP') || columnPrivs.includes('createColumn') || columnPrivs.includes('copyColumn') || columnPrivs.includes('archiveColumn') || columnPrivs.includes('deleteColumn') || columnPrivs.includes('splitColumn'));

    /* Render more menu. */
    if(columnPrivs.includes('createCard') || printMoreBtn)
    {
        var addItemBtn = '';
        var moreAction = '';

        if(!$column.children('.actions').length) $column.append('<div class="actions"></div>');
        var $actions = $column.children('.actions');
        if(column.parent != -1)
        {
            addItemBtn  = ['<button data-contextmenu="columnCreate" data-action="addItem" data-column="' + column.id + '" data-lane="' + laneID + '" class="btn btn-link">', '<i class="icon icon-expand-alt text-primary"></i>', '</button>'].join('');
        }

        var moreAction = ' <button class="btn btn-link action"  title="' + kanbanLang.moreAction + '" data-contextmenu="column" data-column="' + column.id + '"><i class="icon icon-ellipsis-v"></i></button>';
        if(CRKanban || kanban.status != 'closed') $actions.html(addItemBtn + moreAction);

    }
    if(columnPrivs.includes('sortColumn'))
    {
        if($column.hasClass('kanban-header-parent-col'))
        {
            $column.children('.kanban-header-col').addClass('sort');
        }
        else
        {
          $column.addClass('sort');
        }
    }

    if(alignment == 'left')
    {
        if($column.hasClass('kanban-header-parent-col'))
        {
            $column.children('.kanban-header-col').addClass('left');
        }
        else
        {
            $column.addClass('left');
        }
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
        $count.parents('.title').parent('.kanban-header-col').css('background-color', 'transparent');
        $count.parents('.title').find('.text').css('max-width', $count.parents('.title').width() - 200);
        $count.css({'color': '#FF5D5D', 'opacity': '1'});
        if(!$count.parent().find('.error').length) $count.parent().find('.include-last').after("<span class='error text-grey'><icon class='icon icon-exclamation-sign' data-toggle='tooltip' data-original-title='" + kanbanLang.limitExceeded + "'></icon></span>");
        $count.parents('.title').find('.text-grey').css({'color': '#FF5D5D', 'opacity': '1'});
    }
    else
    {
        $count.parents('.title').parent('.kanban-header-col').css('background-color', 'transparent');
        $count.parents('.title').find('.text').css('max-width', $count.parents('.title').width() - 120);
        $count.css({'color': '#8b91a2', 'opacity': '0.5'});
        $count.parent().find('.error').remove();
        $count.parents('.title').find('.text-grey').css({'color': '#8b91a2', 'opacity': '0.5'});
    }
}

/**
 * Render lane name.
 *
 * @param  object $lane
 * @param  object lane
 * @param  object $kanban
 * @param  object columns
 * @param  object kanban
 * @access public
 * @return void
 */
function renderLaneName($lane, lane, $kanban, columns, kanban)
{
    var canEditLaneColor = lane.actions.includes('editLaneColor');
    var canEditLaneName  = lane.actions.includes('editLaneName');
    var canSort          = lane.actions.includes('sortLane') && kanban.lanes.length > 1;
    var canDelete        = lane.actions.includes('deleteLane');

    $lane.parent().toggleClass('sort', canSort);

    if(!$lane.children('.actions').length && (canEditLaneColor || canEditLaneName || canDelete) && (CRKanban || kanbanInfo.status != 'closed'))
    {
        $([
          '<div class="actions" title="' + kanbanLang.more + '">',
          '<a data-contextmenu="lane" data-lane="' + lane.id + '" data-kanban="' + kanban.id + '">',
          '<i class="icon icon-ellipsis-v"></i>',
          '</a>',
          '</div>'
        ].join('')).appendTo($lane);
    }
    if($.cookie('isFullScreen') == 1) hideAllAction();
}

/**
 * Render avatars of user.
 * @param {String|{account: string, avatar: string}} user User account or user object
 * @returns {string}
 */
function renderUsersAvatar(users, itemID, size)
{
    var avatarSizeClass = 'avatar-' + (size || 'md');

    if(users.length == 0 || (users.length == 1 && users[0] == ''))
    {
        return $('<div class="avatar has-text ' + avatarSizeClass + ' avatar-circle iframe" title="' + noAssigned + '" style="background: #ccc"><i class="icon icon-person"></i></div>');
    }

    var assignees = [];
    for(var user of users)
    {
        var $noPrivAndNoAssigned = $('<div class="avatar has-text ' + avatarSizeClass + ' avatar-circle" title="' + noAssigned + '" style="background: #ccc"><i class="icon icon-person"></i></div>');
        if(!priv.canAssignCard && !user)
        {
            assignees.push($noPrivAndNoAssigned);
            continue;
        }

        if(!user)
        {
            assignees.push($('<div class="avatar has-text ' + avatarSizeClass + ' avatar-circle iframe" title="' + noAssigned + '" style="background: #ccc"><i class="icon icon-person"></i></div>'));
            continue;
        }

        if(typeof user === 'string') user = {account: user};
        if(!user.avatar && window.userList && window.userList[user.account]) user = window.userList[user.account];
        if(!user.name && window.users && window.users[user.account]) user.name = window.users[user.account];

        assignees.push($('<div class="avatar has-text ' + avatarSizeClass + ' avatar-circle iframe"></div>').avatar({user: user}));
    }

    if(assignees.length > 3) assignees.splice(3, assignees.length - 3, '<span>...</span>');

    return assignees;
}


/**
 * The function for rendering kanban item
 */
function renderKanbanItem(item, $item)
{
    var whiteStyle = null;
    if(item.color == '#2a5f29' || item.color == '#b10b0b' || item.color == '#cfa227') whiteStyle = 'style="color:#FFFFFF"';

    var privs        = item.actions;
    var printMoreBtn = (privs.includes('editCard') || privs.includes('archiveCard') || privs.includes('copyCard') || privs.includes('deleteCard') || privs.includes('moveCard') || privs.includes('setCardColor'));
    var $actions     = $item.children('.actions');
    var $title       = $item.children('.title');
    if(printMoreBtn && !$actions.length && (CRKanban || kanban.status != 'closed'))
    {
        $(
        [
            '<div class="actions" title="' + kanbanLang.more + '">',
              '<a data-contextmenu="card"' + whiteStyle + 'data-id="' + item.id + '">',
                '<i class="icon icon-ellipsis-v"></i>',
              '</a>',
            '</div>'
        ].join('')).appendTo($item);
    }

    if(item.fromType == 'execution')
    {
        renderExecutionItem(item, $item);
    }
    else if(item.fromType == 'release')
    {
        renderReleaseItem(item, $item);
    }
    else if(item.fromType == 'build')
    {
        renderBuildItem(item, $item);
    }
    else if(item.fromType == 'productplan')
    {
        renderProductplanItem(item, $item);
    }
    else if(item.fromType == 'ticket')
    {
        renderTicketItem(item, $item);
    }
    else
    {
        if(!$title.length)
        {
            if(privs.includes('viewCard')) $title = $('<a class="title iframe" data-toggle="modal" data-width="80%"></a>').appendTo($item).attr('href', createLink('kanban', 'viewCard', 'cardID=' + item.id, '', true));
            if(!privs.includes('viewCard')) $title = $('<p class="title"></p>').appendTo($item);
        }
        $title.text(item.name).attr('title', item.name);

        var $info = $item.children('.info');
        if(!$info.length) $info = $(
        [
            '<div class="info">',
                '<span class="pri"></span>',
                '<span class="estimate label label-light"></span>',
                '<span class="time label label-light"></span>',
                '<div class="user"></div>',
            '</div>'
        ].join('')).appendTo($item);

        $item.data('card', item);

        $info.children('.estimate').text(item.estimate + kanbancardLang.lblHour);
        item.estimate > 0 ? $info.children('.estimate').show() : $info.children('.estimate').hide();

        $info.children('.pri')
            .attr('class', 'pri' + (item.pri ? ' label-pri label-pri-' + item.pri : ''))
            .text(item.pri);

        var $time = $info.children('.time');
        if((item.end == '0000-00-00' && item.begin == '0000-00-00') || (item.end == '' && item.begin == ''))
        {
            $time.hide();
        }
        else
        {
            var today      = new Date();
            var begin      = $.zui.createDate(item.begin);
            var end        = $.zui.createDate(item.end);
            var needRemind = (begin.toLocaleDateString() == today.toLocaleDateString() || end.toLocaleDateString() == today.toLocaleDateString());
            if(item.end == '0000-00-00' && item.begin != '0000-00-00')
            {
                $time.text($.zui.formatDate(begin, 'MM/dd') + ' ' + kanbancardLang.beginAB).attr('title', $.zui.formatDate(begin, 'yyyy/MM/dd') + ' ' +kanbancardLang.beginAB).show();
            }
            else if(item.begin == '0000-00-00' && item.end != '0000-00-00')
            {
                $time.text($.zui.formatDate(end, 'MM/dd') + ' ' + kanbancardLang.deadlineAB).attr('title', $.zui.formatDate(end, 'yyyy/MM/dd') + ' ' + kanbancardLang.deadlineAB).show();
            }
            else if(item.begin != '0000-00-00' && item.end != '0000-00-00')
            {
                $time.text($.zui.formatDate(begin, 'MM/dd') + ' ' +  kanbancardLang.to + ' ' + $.zui.formatDate(end, 'MM/dd')).attr('title', $.zui.formatDate(begin, 'yyyy/MM/dd') + kanbancardLang.to + $.zui.formatDate(end, 'yyyy/MM/dd')).show();
            }

            if(!$item.hasClass('has-color') && needRemind) $time.css('background-color', 'rgba(210, 50, 61, 0.3)');
            if($item.hasClass('has-color') && needRemind)  $time.css('background-color', 'rgba(255, 255, 255, 0.3)');
            if(!needRemind) $time.css('background-color', 'rgba(0, 0, 0, 0.15)');
        }

        /* Display avatars of assignedTo. */
        var assignedTo = item.assignedTo.split(',');
        var $user = $info.children('.user');
        var title = [];
        for(i = 0; i < assignedTo.length; i++) title.push(users[assignedTo[i]]);
        $user.html(renderUsersAvatar(assignedTo, item.id)).attr('title', title);
    }

    if(kanban.performable == 1 && (item.fromType == '' || item.fromType == 'execution'))
    {
        var $progress = $item.children('.progress-box');
        if(!$progress.length) $progress = $('<div class="progress-box"><div class="progress"><div class="progress-bar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: ' + item.progress + '%;"></div></div><div class="progress-number">' + item.progress + '%</div></div>').appendTo($item);
        $progress.find('.progress-bar').css('width', item.progress + '%');
        $progress.find('.progress-number').html(item.progress + '%');
    }
    $item.css('background-color', item.color);
    $item.toggleClass('has-color', item.color != '#fff' && item.color != '');
    $item.find('.info > .label-light').css('background-color', item.color);

    $title = item.fromType == 'execution' ? $item.children('.header').children('.executionName').children('.title') : $item.children('.title');
    if(!$title.children('.label-finish').length) $title.prepend('<div class="label label-finish">' + kanbanLang.finished + '</div>');
    var name = item.title ? item.title : item.name;
    if(kanban.performable == 1 && item.status == 'done' )
    {
        var finishColor = '#2a5f29';
        if(item.color == '#2a5f29') finishColor  = '#FFFFFF';
        $title.css('color', finishColor);
        $title.children('.label-finish').show();
        if(item.color == '#2a5f29')
        {
            $item.children('.label-finish').css({'background-color':'#FFFFFF','color':'#2a5f29'});
        }
        else
        {
            $item.children('.label-finish').css({'background-color':'','color':'#FFFFFF'});
        }
    }
    else
    {
        $title.css('color', '');
        $title.children('.label-finish').hide();
    }
    if($.cookie('isFullScreen') == 1) hideAllAction();
}

/**
 * Render execution item.
 *
 * @param  object item
 * @param  object $item
 * @access public
 * @return void
 */
function renderExecutionItem(item, $item)
{
    /* Output header information. */
    var privs = item.actions;
    if(privs.includes('sortCard')) $item.parent().addClass('sort');

    var $header = $item.children('.header');
    if(!$header.length) $header = $(
    [
        '<div class="header">',
        '</div>'
    ].join('')).appendTo($item);

    var $titleBox = $header.children('.executionName');
    if(!$titleBox.length) $titleBox = $(
    [
        '<div class="executionName">',
        '</div>'
    ].join('')).appendTo($header);

    /* Print execution name. */
    var $title = $titleBox.children('.title');
    var name   = item.name ? item.name : item.title;
    var title  = item.title ? item.title : name;
    if(!$title.length)
    {
        var icon = mode == 'ALM' ? 'run' : 'project';
        var viewMethod = item.execType == 'kanban' ? 'kanban' : 'view';
        if(privs.includes('viewExecution') && item.deleted == '0' && item.children == '0') $title = $('<a class="title"><i class="icon icon-' + icon + '"></i>' + name + '</a>').appendTo($titleBox).attr('href', createLink('execution', viewMethod, 'executionID=' + item.fromID));
        if(!privs.includes('viewExecution') || item.deleted == '1' || item.children != '0') $title = $('<div class="title"><i class="icon icon-' + icon + '"></i>' + name + '</div>').appendTo($titleBox);
    }
    if(!$title.children('i').length)
    {
        $title.append('<i class="icon icon-run"></i>' + item.title);
    }
    $title.attr('title', title);

    if(item.delay)
    {
        $delayed = $titleBox.children('.delayed');
        if(!$delayed.length)
        {
            $('<span class="delayed label label-danger label-badge">' + executionLang.delayed + '</span>').appendTo($titleBox);
        }
    }

    $item.data('card', item);

    var $info = $item.children('.execInfo');
    if(!$info.length) $info = $(
    [
        '<div class="execInfo">',
        '</div>'
    ].join('')).appendTo($item);

    var $statusBox = $info.children('.execStatus');
    if(!$statusBox.length)
    {
        if(item.deleted == '0')
        {
            $statusBox = $('<span class="execStatus label label-' + item.objectStatus + '">' + executionLang.statusList[item.objectStatus] + '</span>').appendTo($info);
        }
        else
        {
            $statusBox = $('<span class="execStatus label label-deleted">' + executionLang.deleted + '</span>').appendTo($info);
        }
    }

    /* Display deadline of execution. */
    var $date     = $info.children('.date');
    var end       = $.zui.createDate(item.end);
    var today     = new Date();
    var labelType = end.toLocaleDateString() == today.toLocaleDateString() ? 'danger' : 'wait';
    if(!$date.length) $date = $('<span class="date label label-' + labelType + '"></span>').appendTo($info);

    $date.text($.zui.formatDate(end, 'MM-dd') + ' ' + kanbancardLang.deadlineAB).attr('title', $.zui.formatDate(end, 'yyyy-MM-dd') + ' ' + kanbancardLang.deadlineAB).show();

    /* Display avatars of PM. */
    var $user = $info.children('.user');
    var user  = [item.PM];
    if(users[item.PM])
    {
        if(!$user.length) $user = $('<div class="user"></div>').appendTo($info);
        $user.html(renderUsersAvatar(user, item.id)).attr('title', users[item.PM]);
    }
}

/**
 * Render plan item.
 *
 * @param  object item
 * @param  object $item
 * @access public
 * @return void
 */
function renderReleaseItem(item, $item)
{
    var privs = item.actions;
    if(privs.includes('sortCard')) $item.parent().addClass('sort');

    /* Print name. */
    var $title = $item.children('.releaseTitle');
    var name   = item.title ? item.title : item.name;
    if(!$title.length)
    {
        if(privs.includes('viewRelease') && item.deleted == '0') $title = $('<a class="releaseTitle title"><i class="icon icon-publish"></i>' + name + '</a>').appendTo($item).attr('href', createLink('release', 'view', 'releaseID=' + item.fromID));
        if(!privs.includes('viewRelease') || item.deleted == '1') $title = $('<div class="releaseTitle"><i class="icon icon-publish"></i>' + name + '</div>').appendTo($item);
    }
    if(!$title.children('i').length)
    {
        $title.prepend('<i class="icon icon-publish"></i>');
    }
    $title.attr('title', name);

    var $info = $item.children('.releaseInfo');
    if(!$info.length) $info = $(
    [
        '<div class="releaseInfo">',
        '</div>'
    ].join('')).appendTo($item);

    var $statusBox = $info.children('.releaseStatus');
    if(!$statusBox.length)
    {
        if(item.deleted == '0')
        {
            $statusBox = $('<span class="releaseStatus label label-' + item.objectStatus + '">' + releaseLang.statusList[item.objectStatus] + '</span>').appendTo($info);
        }
        else
        {
            $statusBox = $('<span class="releaseStatus label label-deleted">' + releaseLang.deleted + '</span>').appendTo($info);
        }
    }

    /* Display release date. */
    var $date       = $info.children('.date');
    var releaseDate = $.zui.createDate(item.date);
    if(!$date.length) $date = $('<span class="date label label-wait"></span>').appendTo($info);

    $date.text($.zui.formatDate(releaseDate, 'yyyy-MM-dd')).attr('title', $.zui.formatDate(releaseDate, 'yyyy-MM-dd')).show();

    /* Display avatars of creator. */
    var $user = $info.children('.user');
    var user  = [item.createdBy];
    if(users[item.createdBy])
    {
        if(!$user.length) $user = $('<div class="user"></div>').appendTo($info);
        $user.html(renderUsersAvatar(user, item.id)).attr('title', users[item.createdBy]);
    }
}

/**
 * Render product plan item.
 *
 * @param  object item
 * @param  object $item
 * @access public
 * @return void
 */
function renderProductplanItem(item, $item)
{
    var privs = item.actions;
    if(privs.includes('sortCard')) $item.parent().addClass('sort');

    /* Print name. */
    var $title = $item.children('.productplanTitle');
    var name   = item.title ? item.title : item.name;
    if(!$title.length)
    {
        if(privs.includes('viewPlan') && item.deleted == '0') $title = $('<a class="productplanTitle title"><i class="icon icon-delay"></i>' + name + '</a>').appendTo($item).attr('href', createLink('productplan', 'view', 'productplanID=' + item.fromID));
        if(!privs.includes('viewPlan') || item.deleted == '1') $title = $('<div class="productplanTitle"><i class="icon icon-delay"></i>' + name + '</div>').appendTo($item);
    }
    if(!$title.children('i').length)
    {
        $title.append('<i class="icon icon-delay"></i>' + name);
    }
    $title.attr('title', item.title);

    var $info = $item.children('.productplanInfo');
    if(!$info.length) $info = $(
    [
        '<div class="productplanInfo">',
        '</div>'
    ].join('')).appendTo($item);

    var $descBox = $info.children('.productplanDesc');
    if(!$descBox.length)
    {
        $descBox = $('<div class="productplanDesc cardDesc" title="' + item.desc + '">' + item.desc + '</div>').appendTo($info);
    }

    var $statusBox = $info.children('.productplanStatus');
    if(!$statusBox.length)
    {
        if(item.deleted == '0')
        {
            $statusBox = $('<span class="productplanStatus label label-' + item.objectStatus + '">' + productplanLang.statusList[item.objectStatus] + '</span>').appendTo($info);
        }
        else
        {
            $statusBox = $('<span class="productplanStatus label label-deleted">' + productplanLang.deleted + '</span>').appendTo($info);
        }
    }

    /* Display date of product plan. */
    var $date      = $info.children('.date');
    var begin      = $.zui.createDate(item.begin);
    var end        = $.zui.createDate(item.end);
    var today      = new Date();
    var labelType  = (item.begin <= $.zui.formatDate(today, 'yyyy-MM-dd') && item.end >= $.zui.formatDate(today, 'yyyy-MM-dd')) ? 'danger' : 'wait';
    var labelTitle = $.zui.formatDate(begin, 'MM-dd') + ' ' + productplanLang.to + ' ' + $.zui.formatDate(end, 'MM-dd');

    if((item.begin == '2030-01-01' || item.end == '2030-01-01'))
    {
        labelType  = 'future';
        labelTitle = productplanLang.future;
    }
    if(!$date.length) $date = $('<span class="date label label-' + labelType + '"></span>').appendTo($info);
    $date.text(labelTitle).attr('title', labelTitle).show();

    /* Display avatars of creator. */
    var $user = $info.children('.user');
    var user  = [item.createdBy];
    if(users[item.createdBy])
    {
        if(!$user.length) $user = $('<div class="user"></div>').appendTo($info);
        $user.html(renderUsersAvatar(user, item.id)).attr('title', users[item.createdBy]);
    }
}

/**
 * Render build item.
 *
 * @param  object item
 * @param  object $item
 * @access public
 * @return void
 */
function renderBuildItem(item, $item)
{
    var privs = item.actions;
    if(privs.includes('sortCard')) $item.parent().addClass('sort');

    /* Print name. */
    var $title = $item.children('.buildTitle');
    var name   = item.title ? item.title : item.name;
    if(!$title.length)
    {
        if(privs.includes('viewBuild') && item.deleted == '0') $title = $('<a class="buildTitle title" data-app="project"><i class="icon icon-ver"></i>' + name + '</a>').appendTo($item).attr('href', createLink('build', 'view', 'buildID=' + item.fromID));
        if(!privs.includes('viewBuild') || item.deleted == '1') $title = $('<div class="buildTitle title"><i class="icon icon-ver"></i>' + name + '</div>').appendTo($item);
    }
    if(!$title.children('i').length)
    {
        $title.append('<i class="icon icon-ver"></i>' + item.title);
    }
    $title.attr('title', name);

    var $descBox = $item.children('.buildDesc');
    if(!$descBox.length)
    {
        $descBox = $('<div class="buildDesc cardDesc" title="' + item.desc + '">' + item.desc + '</div>').appendTo($item);
    }

    var $info = $item.children('.info');
    if(!$info.length) $info = $(
    [
        '<div class="info">',
        '</div>'
    ].join('')).appendTo($item);

    var $statusBox = $info.children('.buildStatus');
    if(!$statusBox.length && item.deleted == '1')
    {
        $statusBox = $('<span class="buildStatus label label-deleted">' + productplanLang.deleted + '</span>').appendTo($info);
    }

    /* Display build date. */
    var $date     = $info.children('.date');
    var buildDate = $.zui.createDate(item.date);
    if(!$date.length) $date = $('<span class="date label label-wait"></span>').appendTo($info);

    $date.text($.zui.formatDate(buildDate, 'yyyy-MM-dd')).attr('title', $.zui.formatDate(buildDate, 'yyyy-MM-dd')).show();

    /* Display avatars of creator. */
    var $user = $info.children('.user');
    var user  = [item.builder];
    if(users[item.builder])
    {
        if(!$user.length) $user = $('<div class="user"></div>').appendTo($info);
        $user.html(renderUsersAvatar(user, item.id)).attr('title', users[item.builder]);
    }
}

/**
 * Render execution item.
 *
 * @param  object item
 * @param  object $item
 * @access public
 * @return void
 */
function renderTicketItem(item, $item)
{
    /* Output header information. */
    var privs = item.actions;
    if(privs.includes('sortCard')) $item.parent().addClass('sort');

    var $header = $item.children('.header');
    if(!$header.length) $header = $(
    [
        '<div class="header">',
        '</div>'
    ].join('')).appendTo($item);

    var $titleBox = $header.children('.ticketTitle');
    if(!$titleBox.length) $titleBox = $(
    [
        '<div class="ticketTitle">',
        '</div>'
    ].join('')).appendTo($header);

    /* Print ticket name. */
    var $title = $titleBox.children('.title');
    var name   = item.title ? item.title : item.name;
    if(!$title.length)
    {
        var icon = 'ticket';
        if(privs.includes('viewTicket') && item.deleted == '0') $title = $('<a class="title"><i class="icon icon-' + icon + '"></i>' + name + '</a>').appendTo($titleBox).attr('href', createLink('ticket', 'view', 'ticketID=' + item.fromID));
        if(!privs.includes('viewTicket') || item.deleted == '1') $title = $('<div class="title"><i class="icon icon-' + icon + '"></i>' + name + '</div>').appendTo($titleBox);
    }
    $title.attr('title', name);
    $item.data('card', item);

    var $info = $item.children('.info');
    if(!$info.length) $info = $(
    [
        '<div class="info">',
        '</div>'
    ].join('')).appendTo($item);

    var $statusBox = $info.children('.execStatus');
    if(!$statusBox.length)
    {
        if(item.deleted == '0')
        {
            $statusBox = $('<span class="execStatus label label-' + item.objectStatus + '">' + ticketLang.statusList[item.objectStatus] + '</span>').appendTo($info);
        }
        else
        {
            $statusBox = $('<span class="execStatus label label-deleted">' + ticketLang.deleted + '</span>').appendTo($info);
        }
    }

    /* Display deadline of execution. */
    if(item.deadline != '0000-00-00')
    {
        var $date     = $info.children('.date');
        var deadline  = $.zui.createDate(item.deadline);
        var today     = new Date();
        var labelType = deadline.toLocaleDateString() == today.toLocaleDateString() ? 'danger' : 'wait';
        if(!$date.length) $date = $('<span class="date label label-' + labelType + '"></span>').appendTo($info);

        $date.text($.zui.formatDate(deadline, 'MM-dd') + ' ' + kanbancardLang.deadlineAB).attr('title', $.zui.formatDate(deadline, 'yyyy-MM-dd') + ' ' + kanbancardLang.deadlineAB).show();
    }

    /* Display avatars of ticket assignedTo. */
    var $user = $info.children('.user');
    var user  = [item.assignedTo];
    if(users[item.assignedTo])
    {
        if(!$user.length) $user = $('<div class="user"></div>').appendTo($info);
        $user.html(renderUsersAvatar(user, item.id)).attr('title', users[item.assignedTo]);
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
 * Move a card.
 *
 * @param  int    $cardID
 * @param  int    $fromColID
 * @param  int    $toColID
 * @param  int    $fromLaneID
 * @param  int    $toLaneID
 * @param  int    $kanbanID
 * @param  int    $regionID
 * @access public
 * @return string
 */
function moveCard(cardID, fromColID, toColID, fromLaneID, toLaneID, kanbanID, regionID)
{
    if(!cardID) return false;
    var url = createLink('kanban', 'moveCard', 'cardID=' + cardID + '&fromColID='+ fromColID + '&toColID=' + toColID + '&fromLaneID='+ fromLaneID + '&toLaneID=' + toLaneID + '&kanbanID=' + kanbanID);
    return $.ajax(
    {
        method:   'post',
        dataType: 'json',
        url:       url,
        async:     false,
        success: function(data)
        {
            regions = data;
            updateRegion(regionID, data[regionID]);

            /* Disable related operations in full screen mode. */
            if($.cookie('isFullScreen') == 1)
            {
                $('.actions').hide();
                $('.action').hide();
                $('.kanban-group-header').hide();
                $(".title").attr("disabled", true).css("pointer-events", "none");
            }
            setToolTip();
        },
        error: function(xhr, status, error)
        {
            showErrorMessager(error || lang.timeout);
        }
    });
}

/**
 * Set a card's color.
 *
 * @param int     $cardID
 * @param string  $color
 * @param int     $kanbanID
 * @param int     $regionID
 * @access public
 * @return string
 */
function setCardColor(cardID, color, kanbanID, regionID)
{
    if(!cardID) return false;
    color = color.replace('#', '');
    var url = createLink('kanban', 'setCardColor', 'cardID=' + cardID + '&color=' + color + '&kanbanID=' + kanbanID);
    return $.ajax(
    {
        method:   'post',
        dataType: 'json',
        url:       url,
        success: function(data)
        {
            updateRegion(regionID, data[regionID]);
        },
        error: function(xhr, status, error)
        {
            showErrorMessager(error || lang.timeout);
        }
    });
}

/**
 * Finish a card.
 *
 * @param  int $cardID
 * @param  int $kanbanID
 * @param  int $regionID
 * @access public
 * @return void
 */
function finishCard(cardID, kanbanID, regionID)
{
    if(!cardID) return false;
    var url = createLink('kanban', 'finishCard', 'cardID=' + cardID + '&kanbanID=' + kanbanID);
    return $.ajax(
    {
        method:   'post',
        dataType: 'json',
        url:       url,
        success: function(data)
        {
            updateRegion(regionID, data[regionID]);
        }
    });
}

/**
 * Update a region.
 *
 * @param  int      regionID
 * @param  array    regionData
 * @access public
 * @return boolean
 */
function updateRegion(regionID, regionData)
{
    if(typeof(regionData) == 'undefined') regionData = [];
    if(!regionID) return false;

    var $region = $('#kanban'+ regionID).kanban();

    if(!$region.length) return false;
    regions[regionID] = regionData ? regionData : regions[regionID];

    $region.data('zui.kanban').render(regions[regionID].groups);
    resetRegionHeight('open');
    return true;
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
    $('#regionNavTabs li[data-id="' + regionID + '"]').attr('title', name);
    $('#regionNavTabs li[data-id="' + regionID + '"]').find('a > span').text(name);
    initRegionTabs();
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
    var modalUrl = createLink('kanban', 'createCard', 'kanbanID=' + kanbanID + '&regionID=' + regionID + '&groupID=' + groupID + '&columnID=' + columnID);
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
    var maxHeight = '500px';
    if(laneCount < 2)
    {
        var windowHeight = $(window).height();
        var marginTop    = $('#main').css('margin-top');
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
    //var status = task.status;
    var $col   = $task.closest('.kanban-col');
    var col    = $col.data();
    var lane   = $col.closest('.kanban-lane').data('lane');
    var allStatusCanChange = statusChangeMap[status];

    hideKanbanAction();

    return $root.find('.kanban-lane-col:not([data-type="EMPTY"],[data-type=""])').filter(function()
    {
        if($.cookie('isFullScreen') == 1 || (!CRKanban && kanbanInfo.status == 'closed')) return false;
        var $newCol = $(this);
        var newCol = $newCol.data();
        var $newLane = $newCol.closest('.kanban-lane');
        var newLane = $newLane.data('lane');

        $newCol.addClass('can-drop-here');
        return true;
    });
}

/**
 * Handle drop card
 * @param {Object} event Drop event object
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
    var regionID = $card.closest('.region').data('id');
    var kanbanID = $card.closest('#kanban').data('id');

    if(!oldCol || !newCol || !newLane || !oldLane) return false;
    if(oldCol.id === newCol.id && newLane.id === oldLane.id) return false;

    var cardID = $card.data().id;
    moveCard(cardID, oldCol.id, newCol.id, oldLane.id, newLane.id, kanbanID, regionID);
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
    var $table = $('#splitTable');
    var columnCount = $table.find('.child-column').length;
    if(columnCount > 2 && columnCount < 10)
    {
        $table.find('.btn-plus').show();
        $table.find('.btn-close').show();
    }
    else if(columnCount <= 2)
    {
        $table.find('.btn-close').hide();
    }
    else if(columnCount >= 10)
    {
        $table.find('.btn-plus').hide();
    }
}

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

/**
 * Create card menu.
 *
 * @param  object $options
 * @access public
 * @return array
 */
function createCardMenu(options)
{
    var card  = options.$trigger.closest('.kanban-item').data('item');
    var privs = card.actions;
    if(!privs.length) return [];

    var items = [];
    if(privs.includes('editCard') && card.fromType == '') items.push({label: kanbanLang.editCard, icon: 'edit', url: createLink('kanban', 'editCard', 'cardID=' + card.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(privs.includes('deleteCard')) items.push({label: card.fromType == '' ? kanbanLang.deleteCard : kanbanLang.removeCard, icon: card.fromType == '' ? 'trash' : 'unlink', url: createLink('kanban', 'deleteCard', 'cardID=' + card.id), attrs: {'target': 'hiddenwin'}});
    if(kanban.performable == 1 && card.fromType == '')
    {
        if(card.status == 'done')
        {
            items.push({label: kanbanLang.activateCard, icon: 'magic', url: createLink('kanban', 'activateCard', 'cardID=' + card.id + '&kanbanID=' + card.kanban, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
        }
        else
        {
            items.push({label: kanbanLang.finishCard, icon: 'checked', onClick: function(){finishCard(card.id, card.kanban, card.region);}});
        }
    }
    if(privs.includes('archiveCard') && kanban.archived == '1') items.push({label: kanbanLang.archiveCard, icon: 'card-archive', url: createLink('kanban', 'archiveCard', 'cardID=' + card.id), attrs: {'target': 'hiddenwin'}, onClick:function(){$('#archivedCards').replaceWith("<div id='archivedCards'></div>");}});

    var editCardAction    = (privs.includes('editCard') && card.fromType == '') ? true : false;
    var deleteCardAction  = privs.includes('deleteCard');
    var archiveCardAction = (privs.includes('archiveCard') && kanban.archived == '1') ? true : false;

    var performable  = kanban.performable == 1 ? true : false;

    var moveCardAction     = privs.includes('moveCard');
    var setCardColorAction = privs.includes('setCardColor');

    var basicActions = (editCardAction || deleteCardAction || archiveCardAction) ? true : false;
    var otherActions = (moveCardAction || setCardColorAction) ? true : false;

    if((performable || basicActions) && otherActions)
    {
        items.push({type: 'divider'});
    }

    if(privs.includes('moveCard'))
    {
        var moveCardItems = [];
        var moveColumns   = [];
        var parentColumns = [];
        var regionGroups   = regions[options.$trigger.closest('.region').data('id')].groups;
        for(let i = 0; i < regionGroups.length ; i ++ )
        {
            if(regionGroups[i].id == options.$trigger.closest('.kanban-board').data('id'))
            {
                moveColumns = regionGroups[i].columns;
                break;
            }
        }
        for(let i = moveColumns.length-1 ; i >= 0 ; i -- )
        {
            if(moveColumns[i].parent > 0) parentColumns.push(moveColumns[i].parent);
            if(moveColumns[i].id == card.column || $.inArray(moveColumns[i].id, parentColumns) >= 0) continue;
            moveCardItems.push({label: moveColumns[i].name, onClick: function(){moveCard(card.id, card.column, moveColumns[i].id, card.lane, card.lane, card.kanban, card.region);}});
        }
        moveCardItems = moveCardItems.reverse();
        items.push({label: kanbanLang.moveCard, icon: 'move', items: moveCardItems});
    }

    if(privs.includes('setCardColor'))
    {
        var cardColoritems = [];
        if(!card.color) card.color = "#fff";
        for(let i = 0 ; i < colorList.length ; i ++ )
        {
            var attr   = card.color == colorList[i] ? '<i class="icon icon-check" style="margin-left: 5px"></i>' : '';
            var border = i == 0 ? 'border:1px solid #b0b0b0;' : '';
            cardColoritems.push({label: "<div class='cardcolor' style='background:" + colorList[i] + ";" + border + "'></div>" + colorListLang[colorList[i]]  + attr ,
                onClick: function(){setCardColor(card.id, colorList[i], card.kanban, card.region);}, html: true, attrs: {id: 'cardcolormenu'}, className: 'color' + i});
        };
        items.push({label: kanbanLang.cardColor, icon: 'color', items: cardColoritems});
    }

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
    if(privs.includes('setColumn')) items.push({label: kanbanLang.editColumn, icon: 'edit', url: createLink('kanban', 'setColumn', 'columnID=' + column.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    if(privs.includes('setWIP')) items.push({label: kanbanLang.setWIP, icon: 'alert', url: createLink('kanban', 'setWIP', 'columnID=' + column.id), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width' : '500px'}});

    var basicActions  = (privs.includes('setColumn') || privs.includes('setWIP')) ? true : false;
    var columnActions = (privs.includes('splitColumn') || privs.includes('createColumn') || privs.includes('copyColumn')) ? true : false;

    if(basicActions && columnActions)
    {
        items.push({type: 'divider'});
    }

    if(privs.includes('splitColumn')) items.push({label: kanbanLang.splitColumn, icon: 'col-split', url: createLink('kanban', 'splitColumn', 'columnID=' + column.id, '', true), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    if(privs.includes('createColumn'))
    {
        items.push({label: kanbanLang.createColumnOnLeft, icon: 'col-add-left', url: createLink('kanban', 'createColumn', 'columnID=' + column.id + '&position=left'), className: 'iframe', attrs: {'data-toggle': 'modal'}});
        items.push({label: kanbanLang.createColumnOnRight, icon: 'col-add-right', url: createLink('kanban', 'createColumn', 'columnID=' + column.id + '&position=right'), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    }
    if(privs.includes('copyColumn')) items.push({label: kanbanLang.copyColumn, icon: 'copy', url: createLink('kanban', 'copyColumn', 'columnID=' + column.id), className: 'iframe', attrs: {'data-toggle': 'modal'}});

    var otherActions = ((privs.includes('archiveColumn') && kanban.archived == '1') || privs.includes('deleteColumn')) ? true : false;
    if(columnActions && otherActions) items.push({type: 'divider'});

    if(privs.includes('archiveColumn') && kanban.archived == '1') items.push({label: kanbanLang.archiveColumn, icon: 'card-archive', url: createLink('kanban', 'archiveColumn', 'columnID=' + column.id), attrs: {'target': 'hiddenwin'}, onClick:function(){$('#archivedColumns').replaceWith("<div id='archivedColumns'></div>");}});
    if(privs.includes('deleteColumn')) items.push({label: kanbanLang.deleteColumn, icon: 'trash', url: createLink('kanban', 'deleteColumn', 'columnID=' + column.id), attrs: {'target': 'hiddenwin'}});

    var bounds = options.$trigger[0].getBoundingClientRect();
    items.$options = {x: bounds.right, y: bounds.top};
    return items;
}

/**
 * Create create menu for column.
 *
 * @param  object $options
 * @access public
 * @return object
 */
function createColumnCreateMenu(options)
{
    var col      = options.$trigger.closest('.kanban-col').data('col');
    var privs    = col.actions;
    var items    = [];
    var regionID = col.region;
    var groupID  = col.group;
    var laneID   = col.$kanbanData.lanes[0].id ? col.$kanbanData.lanes[0].id : 0;
    var columnID = col.id;

    if(privs.includes('createCard')) items.push({label: kanbanLang.createCard, url: $.createLink('kanban', 'createCard', 'kanbanID=' + kanbanID + '&regionID=' + regionID + '&groupID=' + groupID + '&columnID=' + columnID, '', true), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    if(privs.includes('batchCreateCard')) items.push({label: kanbanLang.batchCreateCard, url: $.createLink('kanban', 'batchCreateCard', 'kanbanID=' + kanbanID + '&regionID=' + regionID + '&groupID=' + groupID + '&laneID=' + laneID + '&columnID=' + columnID), attrs: {'data-width': '80%'}});
    if(kanban.object.indexOf('cards') != -1) items.push({label: kanbanLang.importCard, url: $.createLink('kanban', 'importCard', 'kanbanID=' + kanbanID + '&regionID=' + regionID + '&groupID=' + groupID + '&columnID=' + columnID), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    if(kanban.object && kanban.object != 'cards' && vision != 'lite') items.push({className: 'parentDivider'});
    if(kanban.object && kanban.object != 'cards' && vision != 'lite') items.push({label: kanbanLang.importAB, className: 'import'});

    return items;
}

/** Calculate column height */
function calcColHeight(col, lane, colCards, colHeight, kanban)
{
    if(!isMultiLanes) return 0;

    var options = kanban.options;

    var fontSize        = 13;
    var moreLabelHeight = 20;
    var laneNameHeight  = lane.name.length * fontSize;

    if(!options.displayCards) return laneNameHeight > colHeight ? laneNameHeight + 2 * moreLabelHeight : colHeight;
    var displayCards = +(options.displayCards || 2);

    if (typeof displayCards !== 'number' || displayCards < 2) displayCards = 2;
    return (displayCards * (options.cardHeight + options.cardSpace) + options.cardSpace);
}

/** Handle sort cards */
function handleSortCards(event)
{
    var newLaneID = event.element.closest('.kanban-lane').data('id');
    var newColID  = event.element.closest('.kanban-col').data('id');
    var cards     = event.element.closest('.kanban-lane-items').data('cards');
    var orders    = cards.map(function(card){return card.id});
    var fromID    = String(event.element.data('id'));
    var toID      = String(event.target.data('id'));

    var url = createLink('kanban', 'sortCard', 'kanbanID=' + kanbanID + '&laneID=' + newLaneID + '&columnID=' + newColID + '&cards=' + orders.join(','));
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
    });
}

/* Define menu creators */
window.menuCreators =
{
    card: createCardMenu,
    lane: createLaneMenu,
    column: createColumnMenu,
    columnCreate: createColumnCreateMenu
};

/**
 * init Kanban
 */
function initKanban($kanban)
{
    var id         = $kanban.data('id');
    var region     = regions[id];
    var cardHeight = kanbanInfo.performable == 1 ? 87 : 60;
    var droppable  = priv['canMoveCard'] ? {target: findDropColumns, finish: handleFinishDrop} : false;

    $kanban.kanban(
    {
        data:                  region.groups,
        maxColHeight:          510,
        calcColHeight:         calcColHeight,
        fluidBoardWidth:       fluidBoard,
        minColWidth:           typeof window.minColWidth === 'number' ? window.minColWidth : defaultMinColWidth,
        maxColWidth:           typeof window.maxColWidth === 'number' ? window.maxColWidth : defaultMaxColWidth,
        cardHeight:            cardHeight,
        displayCards:          typeof window.displayCards === 'number' ? window.displayCards : 2,
        createColumnText:      kanbanLang.createColumn,
        addItemText:           '',
        itemRender:            renderKanbanItem,
        onAction:              handleKanbanAction,
        onRenderKanban:        adjustAddBtnPosition,
        onRenderLaneName:      renderLaneName,
        onRenderHeaderCol:     renderHeaderCol,
        onRenderCount:         renderCount,
        showCount:             kanban.showWIP == '1' ? true : false,
        sortable:              handleSortCards,
        virtualize:            true,
        virtualRenderOptions:  {container: $(window).add($('#kanbanContainer'))},
        virtualCardList:       true,
        droppable:             droppable,
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
    $(document).find('main#main').css('padding-top', '16px');

    window.isMultiLanes = laneCount > 1;

    $.cookie('isFullScreen', 0);

    /* Init first kanban */
    $('.kanban').each(function()
    {
        initKanban($(this));
    });

    if(navigator.userAgent.toLowerCase().indexOf("qqbrowser") > -1) $('.region .kanban-header-col > .actions').css('top', '30%');

    $('.icon-angle-top,.icon-angle-down').on('click', function()
    {
        $(this).toggleClass('icon-angle-top icon-angle-down');
        $(this).parents('.region').find('.kanban').toggle();
        hideKanbanAction();
        resetRegionHeight($(this).hasClass('icon-angle-top') ? 'open' : 'close');
    });

    $('#regionTabs, .region-header').on('click', '.action', hideKanbanAction);
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

        $('.parentDivider').parent().addClass('divider');
        $('.import').parent().addClass('dropdown-submenu top');

        var regionID = $(this).closest('.kanban').data('id');
        var groupID  = $(this).closest('.kanban-board').data('id');
        var columnID = $(this).closest('.kanban-col').data('id');

        /* The submenu of import. */
        var importPlanLink      = kanban.object.indexOf('plans') != -1 ? "<li><a class='iframe' data-toggle='modal'' href='" + createLink('kanban', 'importPlan', 'kanbanID=' + kanban.id + '&regionID=' + regionID + '&groupID=' + groupID + '&columnID=' + columnID) + "'>" + kanbanLang.importPlan + '</a></li>' : '';
        var importReleaseLink   = kanban.object.indexOf('releases') != -1 ? "<li><a class='iframe' data-toggle='modal'' href='" + createLink('kanban', 'importRelease', 'kanbanID=' + kanban.id + '&regionID=' + regionID + '&groupID=' + groupID + '&columnID=' + columnID) + "'>" + kanbanLang.importRelease + '</a></li>' : '';
        var importExecutionLink = kanban.object.indexOf('executions') != -1 ? "<li><a class='iframe' data-toggle='modal' href='" + createLink('kanban', 'importExecution', 'kanbanID=' + kanban.id + '&regionID=' + regionID + '&groupID=' + groupID + '&columnID=' + columnID) + "'>" + kanbanLang.importExecution + '</a></li>' : '';
        var importBuildLink     = kanban.object.indexOf('builds') != -1 ? "<li><a class='iframe' data-toggle='modal' href='" + createLink('kanban', 'importBuild', 'kanbanID=' + kanban.id + '&regionID=' + regionID + '&groupID=' + groupID + '&columnID=' + columnID) + "'>" + kanbanLang.importBuild + '</a></li>' : '';
        var importTicketLink    = kanban.object.indexOf('tickets') != -1 ? "<li><a class='iframe' data-toggle='modal' href='" + createLink('kanban', 'importTicket', 'kanbanID=' + kanban.id + '&regionID=' + regionID + '&groupID=' + groupID + '&columnID=' + columnID) + "'>" + kanbanLang.importTicket + '</a></li>' : '';
        var importSubmenu       = '<ul class="dropdown-menu">' + importPlanLink + importReleaseLink + importExecutionLink + importBuildLink + importTicketLink + '</ul>';
        $('.import').parent().append(importSubmenu);

    });

    /* Adjust the add button position on window resize */
    $(window).on('resize', function(a)
    {
        adjustAddBtnPosition();
        initRegionTabs();
    });

    if(displayCards == 0) resetLaneHeight();

    /* Hide contextmenu when page scroll */
    $(window).on('scroll', function()
    {
        $.zui.ContextMenu.hide();
        if($('#regionTabs').length > 0) updateRegionTabAffixState();
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

    /* Modify default color's border color. */
    $(document).on('mouseout', '.color0', function()
    {
        $('.color0 .cardcolor').css('border', '1px solid #b0b0b0');
    });

    /* Modify default color's border color. */
    $(document).on('mouseover', '.color0', function()
    {
        $('.color0 .cardcolor').css('border', '1px solid #fff');
    });

    /* Init sortable */
    initSortable();

    resetRegionHeight('open');
    if(!CRKanban && kanbanInfo.status == 'closed') $('.kanban-col.kanban-header-col').css('padding', '0px 0px 0px 0px');

    setToolTip();

    distance    = 0;
    radiusWidth = 10;
    initRegionTabs();

    $('.leftBtn').click(function()
    {
        if($(this).hasClass('disabled')) return;
        swipeRegionNavTabs($('#regionNavTabs').find('ul'), 'left');
    });

    $('.rightBtn').click(function()
    {
        if($(this).hasClass('disabled')) return;
        swipeRegionNavTabs($('#regionNavTabs').find('ul'), 'right');
    });

    if(Object.values(regions).length <= 1) $('#kanbanBox').removeClass('hidden');
});

/**
 * Init sortable.
 *
 * @access public
 * @return void
 */
function initSortable()
{
    var sortType  = '';
    var $cards    = null;
    if(!CRKanban && kanbanInfo.status == 'closed') return;
    $('#kanban').sortable(
    {
        selector: '.region, .kanban-board, .kanban-lane, .kanban-col',
        trigger: '.region.sort > .region-header, .kanban-board.sort > .kanban-header > .kanban-group-header, .kanban-lane.sort > .kanban-lane-name, .kanban-header-col.sort',
        dropOnMouseleave: true,
        container: function($ele)
        {
            return $ele.parent();
        },
        targetSelector: function($ele)
        {
            var $parent = $ele.parent();
            /* Sort regions. */
            if($ele.hasClass('region'))
            {
                sortType = 'region';
                return $parent.children('.region');
            }

            /* Sort boards. */
            if($ele.hasClass('kanban-board'))
            {
                sortType = 'board';
                return $parent.children('.kanban-board');
            }

            /* Sort lanes. */
            if($ele.hasClass('kanban-lane'))
            {
                sortType = 'lane';
                $cards   = $ele.find('.kanban-item');

                return $parent.children('.kanban-lane');
            }

            /* Sort columns. */
            if($ele.hasClass('kanban-col'))
            {
                sortType = 'column';
                return $parent.children('.kanban-col');
            }
        },
        before: function() {
            return !window.sortableDisabled;
        },
        start: function(e)
        {
            if(sortType == 'region')
            {
                showRegionIdList = '';
                $('.icon-angle-top').each(function()
                {
                    showRegionIdList += $(this).attr('data-id') + ',';
                    $(this).attr('class', 'icon-angle-down');
                });

                $('.region').find('.kanban').hide();
                hideKanbanAction();
            }
            else if(sortType === 'column')
            {
                e.element.closest('.kanban-board').addClass('kanban-cols-sorting');
            }
            $('#kanban').attr('data-sort-by', sortType);
        },
        finish: function(e)
        {
            if(!e.changed) return;

            var url      = '';
            var orders   = [];
            var regionID = '';

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
                        $(this).find('.icon-angle-down').attr('class', 'icon-angle-top');
                        $(this).find('.kanban').show();
                    }
                })

                url = createLink('kanban', 'sortRegion', 'regions=' + orders.join(','));
            }
            else if(sortType == 'board')
            {
                regionID = e.element.closest('.region').data('id');
                e.list.each(function(index, data)
                {
                  if(data.item.hasClass('kanban-board') && data.item.hasClass('sort') && regionID == data.item.parent().data().id) orders.push(data.item.data('id'));
                });

                url = createLink('kanban', 'sortGroup', 'region=' + regionID + '&groups=' + orders.join(','));
            }
            else if(sortType == 'lane')
            {
                var groupID = e.element.closest('.kanban-board').data('id');
                e.list.each(function(index, data)
                {
                  if(data.item.hasClass('kanban-lane') && data.item.hasClass('sort') && groupID == data.item.data().lane.group) orders.push(data.item.data('id'));
                });

                regionID = e.element.closest('.region').data('id');
                url      = createLink('kanban', 'sortLane', 'region=' + regionID + '&lanes=' + orders.join(','));
            }
            else if(sortType == 'column')
            {
                var groupID = e.element.closest('.kanban-board').data('id');
                e.list.each(function(index, data)
                {
                  if(data.item.hasClass('kanban-col') && (data.item.hasClass('sort') || data.item.children().hasClass('sort')) && groupID == data.item.data().col.group) orders.push(data.item.data('id'));
                });

                regionID = e.element.closest('.region').data('id');
                url      = createLink('kanban', 'sortColumn', 'region=' + regionID + '&kanbanID=' + kanban.id + '&columns=' + orders.join(','));
            }
            if(!url) return true;

            $.getJSON(url, function(response)
            {
                if(response.result == 'fail')
                {
                    if(typeof response.message === 'string' && response.message.length)
                    {
                        bootbox.alert(response.message);
                    }
                    setTimeout(function(){return location.reload()}, 3000);
                }
                else if (sortType == 'column')
                {
                    updateRegion(regionID, response[regionID]);
                }
            });
        },
        always: function(e)
        {
            if(sortType == 'lane') $cards.show();
            $('#kanban').find('.kanban-cols-sorting').removeClass('kanban-cols-sorting');
        }
    });
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
    var regionCount = 0;
    if($.isEmptyObject(regions)) return false;
    for(var i in regions)
    {
        regionCount += 1;
        if(regionCount > 1) return false;
    }

    var regionID   = Object.keys(regions)[0];
    var region     = regions[regionID].groups;
    var groupCount = 0;

    if($.isEmptyObject(region)) return false;
    for(var j in region)
    {
        groupCount += 1;
        if(groupCount > 1) return false;
    }

    var group     = region[0];
    var laneCount = 0;

    if($.isEmptyObject(group.lanes)) return false;
    for(var h in group.lanes)
    {
        laneCount += 1;
        if(laneCount > 1) return false;
    }

    var regionHeaderHeight = $('.region-header').outerHeight();
    if(fold == 'open')
    {
        var windowHeight  = $(window).height();
        var headerHeight  = $('#mainHeader').outerHeight();
        var mainPadding   = $('#main').css('padding-top');
        var panelBorder   = $('.panel').css('border-top-width');
        var bodyPadding   = $('.panel-body').css('padding-top');
        var height        = windowHeight - (parseInt(mainPadding) * 2) - (parseInt(bodyPadding) * 2) - headerHeight - (parseInt(panelBorder) * 2);
        var regionPadding = $('.kanban').css('padding-bottom');
        var columnHeight  = $('.kanban-header').outerHeight();

        $('.region').css('min-height', height);
        $('.kanban-lane').css('height', height - regionHeaderHeight - parseInt(regionPadding) - columnHeight);
    }
    else
    {
        $('.region').css('height', regionHeaderHeight);
    }
}

$(document).on('click', '.dropdown-menu', function()
{
    $.zui.ContextMenu.hide();
});

/**
 * Alerts for exceeding the limit.
 *
 * @access public
 * @return void
 */
function setToolTip()
{
    $('[data-toggle="tooltip"]').tooltip({container: 'body'});
}

/**
 * Update kanban affix state for all boards in page.
 *
 * @access public
 * @return void
 */
function updateRegionTabAffixState()
{
    var $kanbanContainer = $('#kanbanContainer');
    var kanbanContainer  = $kanbanContainer[0].getBoundingClientRect();
    var $regionTabs      = $('#regionTabs');
    var regionTabs       = $regionTabs[0].getBoundingClientRect();
    if(regionTabs.top <= 0 && !$regionTabs.hasClass('affixed'))
    {
        $regionTabs.addClass('affixed');
        if( kanbanContainer.top > 0) $regionTabs.find('#region-tab-actions').addClass('hidden');
    }
    else if($regionTabs.hasClass('affixed') && kanbanContainer.top > 44)
    {
        $regionTabs.removeClass('affixed');
        $regionTabs.find('#region-tab-actions').removeClass('hidden');
    }

    initRegionTabs();
}

/**
 * Swipe region navigation tabs.
 *
 * @param  object $object
 * @param  string $direction
 * @access public
 * @return bool
 */
function swipeRegionNavTabs($object, direction)
{
    var $regionNavTabs = $('#regionNavTabs');
    var offsetWidth    = $regionNavTabs[0].offsetWidth;
    var objectWidth    = $object[0].offsetWidth;

    $object.find('li').each(function()
    {
        /* Get the offset of the item. */
        var $item      = $(this);
        var itemLeft   = $item[0].offsetLeft;
        var itemWidth  = $item[0].offsetWidth;
        var itemOffset = itemLeft + itemWidth;
        var radius     = $item.hasClass('active') ? radiusWidth : 0;

        /* Calculate the offset after sliding. */
        if(direction == 'left' && (itemOffset + distance + radius) >= -5)
        {
            /* If you swipe left, the distance is equal to the item's left. */
            distance = - itemLeft + radius - ($item.prev().hasClass('active') ? radiusWidth : 0);
            /* tab overlap width */
            distance += 20;
            if($item.prev().length == 0)
            {
                distance = 0;
                $('.leftBtn').addClass('disabled');
            }
            $object[0].style.transform = 'translateX(' + distance + 'px)';

            /* If the width of regionNavTabs plus offsetWidth is less than the width of object, change rightBtn to clickable. */
            if(offsetWidth - distance < objectWidth) $('.rightBtn').removeClass('disabled');
            return false;
        }

        var nextRadius = $item.next().hasClass('active') ? radiusWidth : 0;
        if(direction == 'right' && itemOffset > (offsetWidth - distance + nextRadius + radiusWidth * 2))
        {
            /* If you swipe right, the distance is equal to the left distance of item plus the width of item minus the width of the regionNavTabs. */
            distance = offsetWidth - itemOffset - radius + nextRadius - radiusWidth * 2;
            distance += 20;
            if($item.next().length == 0)
            {
                distance = - objectWidth + offsetWidth - radiusWidth * 2;
                $('.rightBtn').addClass('disabled');
            }
            $object[0].style.transform = 'translateX(' + distance + 'px)';

            /* If distance is less than 0, change leftBtn to clickable. */
            if(distance < 0) $('.leftBtn').removeClass('disabled');
            return false;
        }
    });
}

/**
 * Init region tabs.
 *
 * @access public
 * @return void
 */
function initRegionTabs()
{
    var $regionNavTabs = $('#regionNavTabs');
    if($regionNavTabs.length == 0) return;

    /* Set the width of regionTab. */
    $('#regionTabs').width($('#kanban').outerWidth());

    var regionTabsWidth  = $regionNavTabs[0].offsetWidth;
    var regionTabULWidth = $regionNavTabs.find('ul')[0].offsetWidth;
    var $acitiveItem     = $('#regionNavTabs > ul > li.active');
    var acitiveItemWidth = $acitiveItem[0].offsetWidth;
    var acitiveItemLeft  = $acitiveItem[0].offsetLeft;

    /* Print left and right button. */
    if(regionTabULWidth > regionTabsWidth) $('.leftBtn, .rightBtn').removeClass('hidden');

    if(acitiveItemLeft + distance < 0)
    {
        distance = - acitiveItemLeft;
        distance += 20;
        if($acitiveItem.prev().length == 0)
        {
            distance = 0;
            $('.leftBtn').addClass('disabled');
        }
        $('#regionNavTabs > ul')[0].style.transform = 'translateX(' + distance + 'px)';
    }
    else if(acitiveItemWidth + acitiveItemLeft + distance + radiusWidth * 2 > regionTabsWidth && acitiveItemLeft != 0 && acitiveItemWidth != 0)
    {
        distance = regionTabsWidth - acitiveItemWidth - acitiveItemLeft + (distance != 0 ? - radiusWidth * 2 : radiusWidth);
        distance += 20;
        if($acitiveItem.next().length == 0)
        {
            distance = regionTabsWidth - acitiveItemWidth - acitiveItemLeft - radiusWidth * 2;
            $('.rightBtn').addClass('disabled');
        }
        $('#regionNavTabs > ul')[0].style.transform = 'translateX(' + distance + 'px)';
    }

    if(distance < 0) $('#regionTabs').find('.leftBtn').removeClass('disabled');
    if($acitiveItem.next().length != 0 && regionTabULWidth > regionTabsWidth) $('#regionTabs').find('.rightBtn').removeClass('disabled');
    $('#kanbanBox').removeClass('hidden');
}

$('[data-tab]').on('shown.zui.tab', function(e)
{
    /* Init vars. */
    var $current       = $(e.target);
    var $prev          = $(e.relatedTarget);
    var $regionActions = $('#region-tab-actions');
    var $regions       = $('.region');
    var contentID      = $current.attr('href');
    var regionID       = $current.parent().attr('data-id');
    var hasActions     = $regionActions.hasClass('active');

    /* Highlight the currently selected tab. */
    $current.addClass('btn-active-text');
    $current.parent().addClass('active');
    $prev.removeClass('btn-active-text');
    $prev.parent().removeClass('active');

    /* Dynamic display and hidden of regions. */
    if(contentID == 'all')
    {
        $regions.addClass('active').removeClass('notAll');
        if(hasActions) $regionActions.removeClass('active');
        $regions.each(function()
        {
            var isFold = $(this).find('.region-header i').hasClass('icon-angle-down');
            if(isFold) $(this).find('.kanban').css('display', 'none');
        });
    }
    else
    {
        var $currentRegion = $(contentID);
        $regions.removeClass('active');
        $currentRegion.addClass('active notAll');
        $currentRegion.find('.kanban').css('display', 'block');
        if(!hasActions) $regionActions.addClass('active');

        /* Replace the link of the region actions button with the ID of the current region. */
        $regionActions.find('li').each(function()
        {
            if($(this).hasClass('editRegion')) $(this).find('a').attr('href', createLink('kanban', 'editRegion', 'regionID=' + regionID, '', true));
            if($(this).hasClass('createLane')) $(this).find('a').attr('href', createLink('kanban', 'createLane', 'kanbanID=' + kanbanID + '&regionID=' + regionID, '', true));
            if($(this).hasClass('deleteRegion')) $(this).find('a').attr('href', createLink('kanban', 'deleteRegion', 'regionID=' + regionID));
            if($(this).hasClass('archivedCard')) $(this).find('a').attr('href', "javascript:loadMore(\"Card\", " + regionID + ')');
            if($(this).hasClass('archivedColumn')) $(this).find('a').attr('href', "javascript:loadMore(\"Column\", " + regionID + ')');
        });
    }
     window.scrollTo(0, 0);

    /* To manually refresh stay under the current tab, save the ID of the current region. */
    var url = createLink('kanban', 'ajaxSaveRegionID', 'regionID=' + regionID);
    $.get(url);
});
