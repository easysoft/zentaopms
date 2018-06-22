$(function()
{
    var $taskTree = $('#taskTree').tree(
    {
        name: 'taskTree',
        initialState: 'preserve'
    });

    var taskTree = $taskTree.data('zui.tree');

    // 根据列表展开树形列表
    var showTreeLevel = function(level)
    {
        $('.btn-tree-view').removeClass('btn-active-text');

        if (level === 'root')
        {
            $('[data-type=root]').addClass('btn-active-text');
            taskTree.collapse();
        }
        else if (level === 'all')
        {
            $('[data-type=all]').addClass('btn-active-text');
            taskTree.collapse();
            taskTree.expand($taskTree.find('li.has-list'), true);
        }
        else if (level === 'task')
        {
            $('[data-type=task]').addClass('btn-active-text');
            taskTree.collapse();
            taskTree.show($taskTree.find('li.item-task').parent().parent(), true);
        }
        else if (level === 'story')
        {
            $('[data-type=story]').addClass('btn-active-text');
            taskTree.collapse();
            taskTree.show($taskTree.find('li.item-story').parent().parent(), true);
        }
        $('#main').toggleClass('tree-show-root', level === 'root');
    };

    $(document).on('click', '.btn-tree-view', function()
    {
        showTreeLevel($(this).data('type'));
        return false;
    });

    // 第一次访问时，展示所以节点
    if (!$.zui.store.get('zui.tree::taskTree#taskTree')) showTreeLevel('all');

    // 在右侧显示内容
    var $itemContent = $('#itemContent');
    var $mainContent = $('#mainContent');
    var isItemLoading = false, lastAjaxRequest;
    var showItem = function(url, loadingText)
    {
        $.zui.messager.hide();
        $mainContent.toggleClass('hide-side', !url);
        if (!url)
        {
            $taskTree.find('li.selected').removeClass('selected');
            isItemLoading = false;
            $.zui.store.set('project/tree/showItem', false);
            return;
        }
        if (lastAjaxRequest) lastAjaxRequest.abort();
        $itemContent.empty().addClass('loading').attr('data-loading', loadingText || '');
        isItemLoading = true;
        lastAjaxRequest = $.ajax(
        {
            url: url,
            success: function(data)
            {
                if (!isItemLoading) return;
                $itemContent.html(data).removeClass('loading');
                lastAjaxRequest = null;
                isItemLoading = false;
                $itemContent.find('.text-limit').textLimit();
                $itemContent.find('.histories').histories();
                $itemContent.find('.iframe').modalTrigger();
                $.zui.store.set('project/tree/showItem', url);
            },
            error: function()
            {
                $mainContent.addClass('hide-side');
                isItemLoading = false;
                $.zui.messager.danger(window.lang.timeout);
            }
        });
    };

    var stopPropagation = function(e) {e.stopPropagation();};
    $taskTree.on('click', '.tree-link', function(e)
    {
        var $link = $(this);
        showItem($link.attr('href'), $link.find('.title').text());
        $taskTree.find('li.selected').removeClass('selected');
        $link.closest('li').addClass('selected');
        e.preventDefault();
        stopPropagation(e);
    });

    $itemContent.on('click', stopPropagation);
    $taskTree.on('click', stopPropagation);

    $(document).on('click', function()
    {
        showItem(false);
    }).on('ready', function()
    {
        var lastUrl = $.zui.store.get('project/tree/showItem');
        if(lastUrl)
        {
            showItem(lastUrl);
            $taskTree.find('.tree-link[href="' + lastUrl + '"]').closest('li').addClass('selected');
        }
    });
});
