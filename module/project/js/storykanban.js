$(function()
{
    var isFirefox = $.zui.browser.firefox;
    $.cookie('selfClose', 0, {expires:config.cookieLife, path:config.webRoot});
    var $kanban = $('#kanban');

    var stageMap =
    {
        story:
        {
            projected   : {verified: 'batchChangeStage'},
            developing  : {verified: 'batchChangeStage'},
            developed   : {verified: 'batchChangeStage'},
            testing     : {verified: 'batchChangeStage'},
            tested      : {verified: 'batchChangeStage'},
        }
    };

    var fixBoardWidth = function()
    {
        var $table = $kanban.find('.table');
        var kanbanWidth = $table.width();
        var $cBoards = $table.find('thead>tr>th.c-board:not(.c-side)');
        var boardCount = $cBoards.length;
        if (boardCount <= 1) {
            return;
        }
        var $cSide = $table.find('thead>tr>th.c-board.c-side');
        var totalWidth = kanbanWidth;
        if ($cSide.length) totalWidth = totalWidth - ($cSide.outerWidth() + 5);
        var cBoardWidth = Math.floor(totalWidth/boardCount);

        $cBoards.not(':last').css('width', cBoardWidth);
        $cBoards.last().css('width', totalWidth - cBoardWidth * (boardCount - 1));
        var $boards = $kanban.find('.boards > .board');
        $boards.not(':last').css('width', cBoardWidth);
        $boards.last().css('width', totalWidth - cBoardWidth * (boardCount - 1));
    };
    fixBoardWidth();
    $(window).on('resize', fixBoardWidth);

    var refresh = function()
    {
        var selfClose = $.cookie('selfClose');
        $.cookie('selfClose', 0, {expires:config.cookieLife, path:config.webRoot});
        if(selfClose == 1) $kanban.load(location.href + ' #kanban', fixBoardWidth);
    };

    var dropTo = function(id, from, to, type)
    {
        if(stageMap[type][from] && stageMap[type][from][to])
        {
            $.post(createLink(type, stageMap[type][from][to], 'stage=' + to), {'storyIdList[]':[id]}, function()
            {
                refresh();
            });
            return true;
        }
        return false;
    };

    $kanban.droppable(
    {
        selector: '.board-item:not(.disabled)',
        target: function($ele)
        {
            var itemType = $ele.data('type');
            var $board = $ele.closest('.board');
            var type = $board.data('type');
            return $board.siblings('.board').filter(function()
            {
                var typeMap = stageMap[itemType];
                var actionMap = typeMap && typeMap[type];
                return !!actionMap && actionMap[$(this).data('type')];
            });
        },
        start: function(e)
        {
            $kanban.addClass('dragging');
            e.targets.addClass('can-drop-in');
            var $item = $(e.element).addClass('dragging');
            $item.closest('.boards').addClass('dragging');
        },
        drag: function(e)
        {
            var $item = $(e.element);
            var $target = $(e.target);
            var $holder = $target.find('.board-drag-holder');
            if (!$holder.length) $holder = $('<div class="board-drag-holder"></div>').appendTo($target);
            $kanban.find('.c-board.dragging').removeClass('dragging');
            $kanban.find('.c-board.s-' + $target.data('type')).addClass('dragging');
            $holder.height($item.outerHeight());
        },
        drop: function(e)
        {
            var result = dropTo(e.element.data('id'), e.element.closest('.board').data('type'), e.target.data('type'), e.element.data('type'));
            if(result !== false)
            {
                e.element.insertBefore(e.target.find('.board-drag-holder'));
            }
        },
        finish: function()
        {
            $kanban.removeClass('dragging').find('.can-drop-in').removeClass('can-drop-in');
            $kanban.find('.dragging').removeClass('dragging');
        }
    });

    $kanban.on('click', '.kanbaniframe', function(e)
    {
        var $link = $(this);
        new $.zui.ModalTrigger($.extend(
        {
            type: 'iframe',
            url: $link.attr('href'),
            width: '80%',
        }, $link.data())).show(
        {
            shown:  function(){$('.modal-iframe').data('cancel-reload', true)},
            hidden: refresh
        });
		return false;
    });
});
