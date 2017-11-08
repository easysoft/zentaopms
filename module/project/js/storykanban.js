$(function()
{
    var boardID  = '';
    var onlybody = config.requestType == 'GET' ? "&onlybody=yes" : "?onlybody=yes";
    $(".kanbanFrame").modalTrigger({type: 'iframe', width: '80%', afterShow:function(){ $('#ajaxModal').data('cancel-reload', true)}, afterHidden: function(){refresh()}});

    $.cookie('selfClose', 0, {expires:config.cookieLife, path:config.webRoot});

    var $kanban = $('#kanban');
    var $kanbanWrapper = $('#kanbanWrapper');

    initBoards();

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

    var lastOperation;

    function dropTo(id, from, to, type)
    {
        if(stageMap[type][from] && stageMap[type][from][to])
        {
            lastOperation = {id: id, from: from, to: to};
            $.post(createLink(type, stageMap[type][from][to], 'stage=' + to), {'storyIDList[]':[id]});
            return true;
        }
        return false;
    }

    function initBoards()
    {
        $('.col-droppable').append('<div class="board-shadow"></div>');

        var $boardTasks = $kanban.find('.board-story');
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
                    var $dargShadow = $('.drag-shadow.board-story');
                    for(var stage in stageMap['story'])
                    {
                        $dargShadow.removeClass('board-story-' + stage);
                    }
                    $dargShadow.addClass('board-story-' + e.target.data('id'));
                }
            },
            drop: function(e)
            {
                if(e.isNew && e.element.closest('tr').data('id') == e.target.closest('tr').data('id'))
                {
                    var result = dropTo(e.element.data('id'), e.element.closest('td').data('id'), e.target.data('id'), 'story');
                    if(result !== false)
                    {
                        for(var stage in stageMap['story'])
                        {
                            e.element.removeClass('board-story-' + stage);
                        }
                        e.element.addClass('board-story-' + e.target.data('id')).insertBefore(e.target.find('.board-shadow'));
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
                $(".kanbanFrame").modalTrigger(
                {
                    type: 'iframe', 
                    width: '80%', 
                    afterShow: function()
                    { 
                        $('#ajaxModal').data('cancel-reload', true)
                    }, 
                    afterHidden: function(){refresh()}
                });
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
