$(function()
{
    var isFirefox = $.zui.browser.firefox;
    var adjustBoardsHeight = function()
    {
        var $cBoards = $('.c-boards');
        if ($cBoards.length === 1)
        {
            var $boardsWrapper = $cBoards.find('.boards-wrapper');
            $boardsWrapper.css('min-height', $(window).height() - $('#header').height() - $('#footer').height() - 111);
            if($boardsWrapper.height() > $boardsWrapper.find('.boards').height())
            {
                $boardsWrapper.find('.boards').css(isFirefox ? 'height' : 'min-height', $boardsWrapper.height() - 1);
            }
            return
        }
        $cBoards.each(function()
        {
            var $theBoards = $(this);
            var height = $(window).height() - $('#header').height() - $('#footer').height() - 111;
            var $boardsWrapper = $theBoards.find('.boards-wrapper');
            var minHeight = Math.min($theBoards.prev().outerHeight() - 1, height);
            $boardsWrapper.css({maxHeight: height, minHeight: minHeight});
            if($boardsWrapper.height() > $boardsWrapper.find('.boards').height())
            {
                var $boards = $boardsWrapper.find('.boards');
                $boards.css({maxHeight: $theBoards.height(), minHeight: minHeight});
                if ($boards.outerHeight() < minHeight) $boards.css('height', minHeight);
            }
        });
    };
    adjustBoardsHeight();

    var boardID  = '';
    var onlybody = config.requestType == 'GET' ? "&onlybody=yes" : "?onlybody=yes";
    $.cookie('selfClose', 0, {expires:config.cookieLife, path:config.webRoot});
    var $kanban = $('#kanban');

    var statusMap =
    {
        task:
        {
            wait   : {doing: 'start', done: 'finish', cancel: 'cancel'},
            doing  : {done: 'finish', pause: 'pause'},
            pause  : {doing: 'activate', done: 'finish', cancel: 'cancel'},
            done   : {doing: 'activate', closed: 'close'},
            cancel : {doing: 'activate', closed: 'close'},
            closed : {doing: 'activate'}
        },
        bug:
        {
            wait   : {done: 'resolve', cancel: 'resolve'},
            doing  : {},
            done   : {wait: 'activate', closed: 'close'},
            cancel : {wait: 'activate', closed: 'close'},
            closed : {wait: 'activate'}
        }
    };

    // Get scrollbar width
    var getScrollbarWidth = function ()
    {
        var outer = document.createElement("div");
        outer.style.visibility = "hidden";
        outer.style.width = "100px";
        outer.style.msOverflowStyle = "scrollbar"; // needed for WinJS apps

        document.body.appendChild(outer);

        var widthNoScroll = outer.offsetWidth;
        // force scrollbars
        outer.style.overflow = "scroll";

        // add innerdiv
        var inner = document.createElement("div");
        inner.style.width = "100%";
        outer.appendChild(inner);

        var widthWithScroll = inner.offsetWidth;

        // remove divs
        outer.parentNode.removeChild(outer);

        return widthNoScroll - widthWithScroll;
    };

    var scrollbarWidth = getScrollbarWidth();
    var fixBoardWidth = function()
    {
        var $table = $kanban.children('.table:first');
        var kanbanWidth = $table.width();
        var $cBoards = $table.find('thead>tr>th.c-board:not(.c-side)');
        var boardCount = $cBoards.length;
        var $cSide = $table.find('thead>tr>th.c-board.c-side');
        var totalWidth = kanbanWidth - scrollbarWidth - 1;
        if ($cSide.length) totalWidth = totalWidth - ($cSide.outerWidth() + 5);
        var cBoardWidth = Math.floor(totalWidth/boardCount);
        $cBoards.not(':last').width(cBoardWidth);
        if ($cSide.length) $cBoards.first().width(cBoardWidth + (isFirefox ? 0 : 5));
        $kanban.find('.boards > .board').width(cBoardWidth - (isFirefox ? 21 : 22));
    };
    fixBoardWidth();

    var updateUI = function()
    {
        fixBoardWidth();
        adjustBoardsHeight();
        $kanban.data('zui.table').updateFixUI();
    };

    $(window).on('resize', updateUI);

    var refresh = function(force)
    {
        var selfClose = $.cookie('selfClose');
        $.cookie('selfClose', 0, {expires:config.cookieLife, path:config.webRoot});
        if(selfClose == 1 || force)
        {
            $kanban.load(location.href + ' #kanban>*', updateUI);
        }
    };
    window.refreshKanban = refresh;

    var kanbanModalTrigger = new $.zui.ModalTrigger({type: 'iframe', width:800});
    var dropTo = function(id, from, to, type)
    {
        if(statusMap[type][from] && statusMap[type][from][to])
        {
            kanbanModalTrigger.show(
            {
                url: $.createLink(type, statusMap[type][from][to], 'id=' + id) + onlybody,
                shown:  function(){$('.modal-iframe').addClass('with-titlebar').data('cancel-reload', true)},
                hidden: refresh
            });
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
                var typeMap = statusMap[itemType];
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
        kanbanModalTrigger.show(
        {
            url: $link.attr('href'),
            shown:  function(){$('.modal-iframe').addClass('with-titlebar').data('cancel-reload', true)},
            hidden: refresh
        });
		return false;
    });

    $.extend({'closeModal':function(callback, location)
    {
        kanbanModalTrigger.close();
        if(callback && $.isFunction(callback)) callback();
    }});
});
