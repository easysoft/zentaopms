
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

    return newScaleSize;
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

    var items = [];
    if(privs.includes('setLane')) items.push({label: kanbanLang.setLane, icon: 'edit', url: createLink('kanban', 'setLane', 'laneID=' + lane.id + '&executionID=0&from=kanban'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '635px'}});
    if(privs.includes('deleteLane')) items.push({label: kanbanLang.deleteLane, icon: 'trash', url: createLink('kanban', 'deleteLane', 'lane=' + lane.id), attrs: {'target': 'hiddenwin'}});

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
    if(privs.includes('editCard')) items.push({label: kanbanLang.editCard, icon: 'edit', url: createLink('kanban', 'editCard', 'cardID=' + card.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal', 'data-width': '80%'}});
    if(privs.includes('archiveCard') && kanban.archived == '1') items.push({label: kanbanLang.archiveCard, icon: 'card-archive', url: createLink('kanban', 'archiveCard', 'cardID=' + card.id), attrs: {'target': 'hiddenwin'}});
    if(privs.includes('copyCard')) items.push({label: kanbanLang.copyCard, icon: 'copy', url: createLink('kanban', 'copyCard', 'cardID=' + card.id, '', 'true'), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    if(privs.includes('deleteCard')) items.push({label: kanbanLang.deleteCard, icon: 'trash', url: createLink('kanban', 'deleteCard', 'cardID=' + card.id), attrs: {'target': 'hiddenwin'}});
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
            moveCardItems.push({label: moveColumns[i].name, onClick: function(){moveCard(card.id, moveColumns[i].id, card.lane, card.kanban, card.region);}});
        }
        moveCardItems = moveCardItems.reverse();
        items.push({label: kanbanLang.moveCard, icon: 'move', items: moveCardItems});
    }
    if(privs.includes('setCardColor'))
    {
        var cardColoritems = [];
        if(!card.color) color = "#fff";
        for(let i = 0 ; i < colorList.length ; i ++ )
        {
            var attr   = card.color == colorList[i] ? '<i class="icon icon-check" style="margin-left: 10px"></i>' : '';
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
    if(privs.includes('splitColumn')) items.push({label: kanbanLang.splitColumn, icon: 'col-split', url: createLink('kanban', 'splitColumn', 'columnID=' + column.id, '', true), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    if(privs.includes('createColumn'))
    {
        items.push({label: kanbanLang.createColumnOnLeft, icon: 'col-add-left', url: createLink('kanban', 'createColumn', 'columnID=' + column.id + '&position=left'), className: 'iframe', attrs: {'data-toggle': 'modal'}});
        items.push({label: kanbanLang.createColumnOnRight, icon: 'col-add-right', url: createLink('kanban', 'createColumn', 'columnID=' + column.id + '&position=right'), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    }
    if(privs.includes('copyColumn')) items.push({label: kanbanLang.copyColumn, icon: 'copy', url: createLink('kanban', 'copyColumn', 'columnID=' + column.id), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    if(privs.includes('archiveColumn') && kanban.archived == '1' && column.$kanbanData.columns.length > 1) items.push({label: kanbanLang.archiveColumn, icon: 'card-archive', url: createLink('kanban', 'archiveColumn', 'columnID=' + column.id), attrs: {'target': 'hiddenwin'}});
    if(privs.includes('deleteColumn') && column.$kanbanData.columns.length > 1) items.push({label: kanbanLang.deleteColumn, icon: 'trash', url: createLink('kanban', 'deleteColumn', 'columnID=' + column.id), attrs: {'target': 'hiddenwin'}});

    var bounds = options.$trigger[0].getBoundingClientRect();
    items.$options = {x: bounds.right, y: bounds.top};
    return items;
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
 * Handle finish drop task
 */
function handleFinishDrop()
{
    $('.kanban').find('.can-drop-here').removeClass('can-drop-here');
}

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
        var $newCol = $(this);
        var newCol = $newCol.data();
        var $newLane = $newCol.closest('.kanban-lane');
        var newLane = $newLane.data('lane');

        $newCol.addClass('can-drop-here');
        return true;
    });
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
    var printMoreBtn = (columnPrivs.includes('setColumn') || columnPrivs.includes('setWIP') || columnPrivs.includes('createColumn') || columnPrivs.includes('copyColumn') || columnPrivs.includes('archiveColumn') || columnPrivs.includes('deleteColumn') || columnPrivs.includes('splitColumn'));

    /* Render more menu. */
    if(columnPrivs.includes('createCard') || printMoreBtn)
    {
        var addItemBtn = '';
        var moreAction = '';

        if(!$column.children('.actions').length) $column.append('<div class="actions"></div>');
        var $actions = $column.children('.actions');

        if(columnPrivs.includes('createCard') && column.parent != -1)
        {
            var cardUrl = createLink('kanban', 'createCard', 'kanbanID=' + kanbanID + '&regionID=' + regionID + '&groupID=' + groupID + '&laneID=' + laneID + '&columnID=' + columnID);
            addItemBtn  = ['<a data-contextmenu="columnCreate" data-toggle="modal" data-action="addItem" data-column="' + column.id + '" data-lane="' + laneID + '" href="' + cardUrl + '" class="text-primary iframe">', '<i class="icon icon-expand-alt"></i>', '</a>'].join('');
        }

        var moreAction = ' <button class="btn btn-link action"  title="' + kanbanLang.moreAction + '" data-contextmenu="column" data-column="' + column.id + '"><i class="icon icon-ellipsis-v"></i></button>';
        $actions.html(addItemBtn + moreAction);
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
    var canSet    = lane.actions.includes('setLane');
    var canSort   = lane.actions.includes('sortLane') && kanban.lanes.length > 1;
    var canDelete = lane.actions.includes('deleteLane');

    $lane.parent().toggleClass('sort', canSort);

    if(!$lane.children('.actions').length && (canSet || canDelete))
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
/**
 * The function for rendering kanban item
 */
function renderKanbanItem(item, $item)
{
    var $title       = $item.children('.title');
    var privs        = item.actions;
    var printMoreBtn = (privs.includes('editCard') || privs.includes('archiveCard') || privs.includes('copyCard') || privs.includes('deleteCard') || privs.includes('moveCard') || privs.includes('setCardColor'));
    if(!$title.length)
    {
        if(privs.includes('viewCard')) $title = $('<a class="title iframe" data-toggle="modal" data-width="80%"></a>').appendTo($item).attr('href', createLink('kanban', 'viewCard', 'cardID=' + item.id, '', true));
        if(!privs.includes('viewCard')) $title = $('<p class="title"></p>').appendTo($item);
    }

    $title.text(item.name).attr('title', item.name);

    if(printMoreBtn)
    {
        $(
        [
            '<div class="actions" title="' + kanbanLang.more + '">',
              '<a data-contextmenu="card" data-id="' + item.id + '">',
                '<i class="icon icon-ellipsis-v"></i>',
              '</a>',
            '</div>'
        ].join('')).appendTo($item);
    }

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
    if(item.estimate == 0) $info.children('.estimate').hide();

    $info.children('.pri')
        .attr('class', 'pri label-pri label-pri-' + item.pri)
        .text(item.pri);

    $item.css('background-color', item.color);
    $item.toggleClass('has-color', item.color != '#fff' && item.color != '');
    $item.find('.info > .label-light').css('background-color', item.color);

    var $time = $info.children('.time');
    if(item.end == '0000-00-00' && item.begin == '0000-00-00')
    {
        $time.hide();
    }
    else
    {
        var today = new Date();
        var begin = $.zui.createDate(item.begin);
        var end   = $.zui.createDate(item.end);
        var needRemind    = (begin.toLocaleDateString() == today.toLocaleDateString() || end.toLocaleDateString() == today.toLocaleDateString());
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
/* Define menu creators */
window.menuCreators =
{
    card: createCardMenu,
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
        fluidBoardWidth:   false,
        minColWidth:       300,
        maxColWidth:       300,
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
    });

    $kanban.on('click', '.action-cancel', hideKanbanAction);
    $kanban.on('scroll', function()
    {
        $.zui.ContextMenu.hide();
    });
}

/**
 * Init when page ready
 */
$(function()
{
    if($.cookie('isFullScreen') == 1) fullScreen();

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

    /* Mofidy dafault color's border color. */
    $(document).on('mouseout', '.color0', function()
    {
        $('.color0 .cardcolor').css('border', '1px solid #b0b0b0');
    });

    /* Mofidy dafault color's border color. */
    $(document).on('mouseover', '.color0', function()
    {
        $('.color0 .cardcolor').css('border', '1px solid #fff');
    });

    /* Init sortable */
    var sortType = '';
    var $cards   = null;
    $('#kanban').sortable(
    {
        selector: '.region, .kanban-board, .kanban-lane',
        trigger: '.region.sort > .region-header, .kanban-board.sort > .kanban-header > .kanban-group-header, .kanban-lane.sort > .kanban-lane-name',
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
            var url = '';
            var orders = [];
            e.list.each(function(index, data)
            {
                orders.push(data.item.data('id'));
            });

            if(sortType == 'region')
            {
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
        },
        always: function(e)
        {
            if(sortType == 'lane') $cards.show();
        }
    });
});
