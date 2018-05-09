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
    if (level === 'root')
    {
      taskTree.collapse();
    }
    else if (level === 'all')
    {
      taskTree.collapse();
      taskTree.expand($taskTree.find('li.has-list'), true);
    }
    else if (level === 'task')
    {
      taskTree.collapse();
      taskTree.show($taskTree.find('li.item-task').parent().parent(), true);
    }
    else if (level === 'story')
    {
      taskTree.collapse();
      taskTree.show($taskTree.find('li.item-story').parent().parent(), true);
    }
    $('#main').toggleClass('tree-show-root', level === 'root');
  };

  $(document).on('click', '.btn-tree-view', function()
  {
    showTreeLevel($(this).data('type'));
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
  });
});
