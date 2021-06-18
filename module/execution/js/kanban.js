$(function()
{
    var isFirefox = $.zui.browser.firefox;
    var adjustBoardsHeight = function()
    {
        var $cBoards = $('.c-boards');
        var viewHeight = $(window).height() - $('#header').height() - $('#footer').height() - 111;
        if ($cBoards.length === 1)
        {
            var $boardsWrapper = $cBoards.find('.boards-wrapper');
            $boardsWrapper.css('min-height', viewHeight);
            if($boardsWrapper.height() > $boardsWrapper.find('.boards').height())
            {
                $boardsWrapper.find('.boards').css(isFirefox ? 'height' : 'min-height', $boardsWrapper.height() - 1);
            }
            return
        }

        $cBoards.each(function()
        {
            var $theBoards = $(this);

            var $boardsWrapper = $theBoards.find('.boards-wrapper');
            var minHeight = Math.min($theBoards.prev().find('.board-story').outerHeight() + 4, viewHeight);
            $boardsWrapper.css({maxHeight: viewHeight, minHeight: minHeight});
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

    var kanbanModalTrigger = new $.zui.ModalTrigger({type: 'iframe', width: 800});
    var dropTo = function(id, from, to, type)
    {
        if(statusMap[type][from] && statusMap[type][from][to])
        {
            var method = statusMap[type][from][to];
            var link   = $.createLink(type, method, 'id=' + id + '&subStatus=' + to);
            if(method == 'ajaxChangeSubStatus')
            {
                $.getJSON(link, function(response)
                {
                    if(response.result == 'fail' && response.message)
                    {
                        bootAlert(response.message);
                        setTimeout(function(){location.reload();}, 1000);
                    }
                });
            }
            else
            {
                kanbanModalTrigger.show(
                {
                    url: link + onlybody,
                    shown:  function(){$('.modal-iframe').addClass('with-titlebar').data('cancel-reload', true)},
                    width: 900,
                    hidden: refresh
                });
            }
        }

        /* Keep the draged element stay in the new place. */
        return true;
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
            var $item   = $(e.element);
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
            hidden: refresh,
            width: $(this).is('.task-assignedTo,.bug-assignedTo') ? 800 : 1100
        });
        return false;
    });

    fixKanbanSide($kanban);
});

function fixKanbanSide($kanban)
{
    if($kanban.length == 0) return false;

    fixSideInit();
    $kanban.scroll(fixSide);//Fix kanban side when scrolling.

    var tableWidth, kanbanOffset, fixedSide, $fixedSide;
    function fixSide()
    {
        kanbanOffset = $kanban.offset().left;
        $fixedSide   = $kanban.parent().find('.fixedSide');
        if($fixedSide.length <= 0 && kanbanOffset < $kanban.scrollLeft())
        {
            var $th = $kanban.find('table thead tr th:first');

            tableWidth = $th.width();

            fixedSide  = "<table class='table table-bordered fixedSide'><thead><tr><th class='c-board c-side has-btn'>" + $th.html()+ '</th></tr></thead><tbody>';
            $kanban.find('table tbody tr').each(function()
            {
                var $td = $(this).find('td:first');
                fixedSide = fixedSide + "<tr><td class='c-side text-left'>" + $td.html() + '</td></tr>';
            });
            fixedSide = fixedSide + '</tbody></table>';

            $kanban.before(fixedSide);

            $('.fixedSide').width(tableWidth);
            $('.fixedSide').css('top', $kanban.offset());

            /* Reset height. */
            var index = 1;
            $('.fixedSide tbody tr').each(function()
            {
                var $td = $kanban.find('table tbody tr:nth-child(' + index + ') td:first');

                if($(this).find('td:first div:first').length == 0) $(this).find('td:first').html('<div></div>');
                $(this).find('td:first div:first').height($td.height());

                index++;
            })
        }
        if($fixedSide.length > 0 && kanbanOffset >= $kanban.scrollLeft()) $fixedSide.remove();
    }
    function fixSideInit()
    {
        $fixedSide = $kanban.parent().find('.fixedSide');
        if($fixedSide.length > 0) $fixedSide.remove();
        fixSide();
    }
}
