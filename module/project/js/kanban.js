$(function()
{
    var boardID  = '';
    var onlybody = config.requestType == 'GET' ? "&onlybody=yes" : "?onlybody=yes";
    $('#printKanban').modalTrigger({type:'iframe', width: 400, url: createLink('project', 'printKanban', 'projectID=' + projectID), icon: 'print'});
    $(".kanbanFrame").modalTrigger({type: 'iframe', width: '80%', afterShow:function(){ $('#ajaxModal').data('cancel-reload', true)}, afterHidden: function(){refresh()}});

    $.cookie('selfClose', 0, {expires:config.cookieLife, path:config.webRoot});

    var $kanban = $('#kanban');
    var $kanbanWrapper = $('#kanbanWrapper');

    initBoards();

    var statusMap =
    {
        task:
        {
            wait   : {doing: 'start', done: 'finish', cancel: 'cancel'},
            doing  : {done: 'finish'},
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

    var lastOperation;

    function dropTo(id, from, to, type)
    {
        if(statusMap[type][from] && statusMap[type][from][to])
        {
            lastOperation = {id: id, from: from, to: to};
            $.modalTrigger({type: 'iframe', url: createLink(type, statusMap[type][from][to], 'id=' + id) + onlybody, afterShow:function(){ $('#ajaxModal').data('cancel-reload', true)}, afterHidden: function()
            {
                var selfClose = $.cookie('selfClose');
                $.cookie('selfClose', 0, {expires:config.cookieLife, path:config.webRoot});
                if(selfClose != 1 && lastOperation)
                {
                    $item = $('#' + type + '-' + lastOperation.id);
                    for(var status in statusMap[type])
                    {
                        $item.removeClass('board-' + type + '-' + status);
                    }
                    $item.addClass('board-' + type + '-' + lastOperation.from).insertBefore($item.closest('tr').find('.col-'+lastOperation.from + ' .board-shadow'));
                }
                else
                {
                    $.get(createLink(type, 'ajaxGetByID', 'id=' + id), function(data)
                    {
                        $('div#' + type + '-' + id).find('.' + type + '-assignedTo small').html(data.assignedTo);
                        if(type == 'task')
                        {
                            $('div#task-' + id).find('.task-left').html(data.left + 'h');
                            if(data.story)$('div.board-story[data-id="' + data.story + '"]').find('.story-stage').html(data.storyStage);
                        }
                    }, 'json');
                }
            }});
            return true;
        }
        return false;
    }

    function initBoards()
    {
        $('.col-droppable').append('<div class="board-shadow"></div>');

        var $boardTasks = $kanban.find('.board-task');
        $boardTasks.droppable(
        {
            target: '.col-droppable',
            flex: true,
            before: function(e)
            {
                if(e.element.find('.dropdown.open').length) return false;
            },
            start: function(e)
            {
                e.element.closest('td').addClass('drag-from').closest('tr').addClass('dragging');
                $kanban.addClass('dragging').find('.board-item-shadow').height(e.element.outerHeight());
            },
            drag: function(e)
            {
                if(e.isNew)
                {
                    var $dargShadow = $('.drag-shadow.board-task');
                    for(var status in statusMap['task'])
                    {
                        $dargShadow.removeClass('board-task-' + status);
                    }
                    $dargShadow.addClass('board-task-' + e.target.data('id'));
                }
            },
            drop: function(e)
            {
                if(e.isNew && e.element.closest('tr').data('id') == e.target.closest('tr').data('id'))
                {
                    var result = dropTo(e.element.data('id'), e.element.closest('td').data('id'), e.target.data('id'), 'task');
                    if(result !== false)
                    {
                        for(var status in statusMap['task'])
                        {
                            e.element.removeClass('board-task-' + status);
                        }
                        e.element.addClass('board-task-' + e.target.data('id')).insertBefore(e.target.find('.board-shadow'));
                    }
                }
            },
            finish: function(e)
            {
                $kanban.removeClass('dragging drop-in');
                $kanbanWrapper.find('tr.dragging').removeClass('dragging').find('.drop-in, .drag-from').removeClass('drop-in drag-from');
            }
        });

        var $boardBugs = $kanban.find('.board-bug');
        $boardBugs.droppable(
        {
            target: '.col-droppable',
            flex: true,
            start: function(e)
            {
                e.element.closest('td').addClass('drag-from').closest('tr').addClass('dragging');
                $kanban.addClass('dragging').find('.board-item-shadow').height(e.element.outerHeight());
            },
            drag: function(e)
            {
                if(e.isNew)
                {
                    var $dargShadow = $('.drag-shadow.board-bug');
                    for(var status in statusMap['bug'])
                    {
                        $dargShadow.removeClass('board-bug-' + status);
                    }
                    $dargShadow.addClass('board-bug-' + e.target.data('id'));
                }
            },
            drop: function(e)
            {
                if(e.isNew && e.element.closest('tr').data('id') == e.target.closest('tr').data('id'))
                {
                    var result = dropTo(e.element.data('id'), e.element.closest('td').data('id'), e.target.data('id'), 'bug');
                    if(result !== false)
                    {
                        for(var status in statusMap['bug'])
                        {
                            e.element.removeClass('board-bug-' + status);
                        }
                        e.element.addClass('board-bug-' + e.target.data('id')).insertBefore(e.target.find('.board-shadow'));
                    }
                }
            },
            finish: function(e)
            {
                $kanban.removeClass('dragging drop-in');
                $kanbanWrapper.find('tr.dragging').removeClass('dragging').find('.drop-in, .drag-from').removeClass('drop-in drag-from');
            }
        });
    }

    function refresh()
    {
        var selfClose = $.cookie('selfClose');
        $.cookie('selfClose', 0, {expires:config.cookieLife, path:config.webRoot});
        if(selfClose == 1)
        {
            $('#kanbanWrapper').wrap("<div id='tempDIV'></div>");
            $('#tempDIV').load(location.href + ' #kanbanWrapper', function()
            {
                $('#kanbanWrapper').unwrap();
                initBoards()
                $(".kanbanFrame").modalTrigger({type: 'iframe', width: '80%', afterShow:function(){ $('#ajaxModal').data('cancel-reload', true)}, afterHidden: function(){refresh()}});
            });
        }
    }

    var fixH = $("#kanbanHeader").offset().top;
    $(window).scroll(function()
    {
        var scroH = $(this).scrollTop();
        if(scroH>=fixH)
        {
            $("#kanbanHeader").addClass('affix');
            $("#kanbanHeader").width($('#kanbanWrapper').width());
        }
        else if(scroH<fixH)
        {
            $("#kanbanHeader").removeClass('affix');
            $("#kanbanHeader").css('width', '100%');
        }
    });

    $('#kanban').on('click', '.btn-info-toggle', function()
    {
          $btn = $(this);
          $btn.find('i').toggleClass('icon-angle-down').toggleClass('icon-angle-up');
          $btn.parents('.board').toggleClass('show-info');
    });
});
