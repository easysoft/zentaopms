$(function()
{
    var $taskTree = $('#taskTree').tree(
    {
        name: 'taskTree',
        initialState: 'preserve'
    });

    var taskTree = $taskTree.data('zui.tree');

    var sortItems = function($items)
    {
        var items = $items.toArray();
        for(i = 0; i < items.length; i++)
        {
            for(j = 0; j < items.length - 1 - i; j++)
            {
                if($(items[j + 1]).data('id').toString() > $(items[j]).data('id').toString())
                {
                    var tmp = items[j + 1];
                    items[j + 1] = items[j];
                    items[j] = tmp;
                }
            }
        }
        return items;
    }

    // 根据列表展开树形列表
    var showTreeLevel = function(level)
    {
        $('.btn-tree-view').removeClass('btn-active-text');
        $('#taskTree li.item-product').removeClass('hidden');
        $('#taskTree li.item-module').removeClass('hidden');
        $('#taskTree li.item-story').removeClass('hidden');
        $('#taskTree li.item-task').removeClass('hidden');

        if(level === 'root')
        {
            $('[data-type=root]').addClass('btn-active-text');
            taskTree.collapse();
        }
        else if(level === 'all')
        {
            $('[data-type=all]').addClass('btn-active-text');
            taskTree.collapse();
            taskTree.expand($taskTree.find('li.has-list'), true);
        }
        else if(level === 'task')
        {
            $('[data-type=task]').addClass('btn-active-text');
            taskTree.collapse();
            taskTree.show($taskTree.find('li.item-task').parent().parent(), true);

            var $storyItems = $('#taskTree li.item-story');
            $storyItems.each(function()
            {
                var items = $(this).find('ul li:not(.hidden)').length;
                if(items == 0) $(this).addClass('hidden');
            });
            var $moduleItems = $('#taskTree li.item-module');
            moduleItems = sortItems($moduleItems);
            for(i = 0; i < moduleItems.length; i++)
            {
                var items = $(moduleItems[i]).find('ul li:not(.hidden)').length;
                if(items == 0) $(moduleItems[i]).addClass('hidden');
            }
            var $productItems = $('#taskTree li.item-product');
            $productItems.each(function()
            {
                var items = $(this).find('ul li:not(.hidden)').length;
                if(items == 0) $(this).addClass('hidden');
            });
        }
        else if(level === 'story')
        {
            $('[data-type=story]').addClass('btn-active-text');
            taskTree.collapse();
            taskTree.show($taskTree.find('li.item-story').parent().parent(), true);

            $('#taskTree li.item-task').addClass('hidden');
            var $moduleItems = $('#taskTree li.item-module');
            moduleItems = sortItems($moduleItems);
            for(i = 0; i < moduleItems.length; i++)
            {
                var items = $(moduleItems[i]).find('ul li:not(.hidden)').length;
                if(items == 0) $(moduleItems[i]).addClass('hidden');
            }
            var $productItems = $('#taskTree li.item-product');
            $productItems.each(function()
            {
                var items = $(this).find('ul li:not(.hidden)').length;
                if(items == 0) $(this).addClass('hidden');
            });
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

                var scrollTop = $(document).scrollTop() - 140;
                if(scrollTop < 0) scrollTop = 0;
                $itemContent.closest('.cell').css('margin-top', scrollTop);
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
