<?php
/**
 * The project tree view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     project
 * @version     $Id: tree.html.php 4894 2013-06-25 01:28:39Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include './taskheader.html.php';
?>

<div class='main'>
  <div class='panel'>
    <div class='panel-heading'>
      <i class='icon icon-folder-close-alt'></i> <strong><?php echo $project->name ?></strong>
      <div class='panel-actions pull-right'>
        <div class='btn-group'>
          <?php foreach ($lang->project->treeLevel as $name => $btnLevel):?>
          <?php if($name == 'all') continue;?>
          <button type='button' class='btn btn-sm tree-view-btn' data-type='<?php echo $name ?>'><?php echo $btnLevel ?></button>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <div class='panel-body'>
      <ul id='projectTree' class='tree-lines'></ul>
    </div>
  </div>
</div>

<script>
var projectID = <?php echo $projectID?>;
$('#project<?php echo $projectID;?>').addClass('active')
$('#treeTab').addClass('active');

function setModalInTree(tree)
{
    setModal4List('iframe', null, function()
    {
        $.cancelReloadCloseModal();
        $.getJSON('<?php echo inlink('tree', "projectID=$projectID&type=json") ?>', function(newData)
        {
            tree.reload(newData);
            setModalInTree(tree);
        });
    });
}

$(function()
{
    var hoursFormat = '<?php echo $lang->project->hours  ?>';
    var viewLevel = '<?php echo $level ?>' || 'custom';
    var data = $.parseJSON('<?php echo helper::jsonEncode4Parse($tree, JSON_HEX_QUOT | JSON_HEX_APOS);?>');
    var $tree = $('#projectTree');
    var statusMap = $.parseJSON('<?php echo helper::jsonEncode4Parse($lang->task->statusList);?>');
    var selectCustomLevel = function() {$('.tree-view-btn.active').removeClass('active').filter('[data-type="custom"]').addClass('active');};
    $tree.tree(
    {
        name: 'projectTasksTree',
        initialState: !viewLevel || viewLevel === 'custom' ? 'preserve' : 'collapse',
        data: data,
        itemWrapper: true,
        actions:
        {
            add:
            {
                title: '<?php echo $lang->project->batchWBS ?>',
                template: '<a data-toggle="tooltip" href="javascript:;"><i class="icon icon-sitemap"></i>',
                templateInList: false,
                linkTemplate: '<?php echo helper::createLink('tree', 'edit', "moduleID={0}&type=task"); ?>'
            },
        },
        action: function(event)
        {
            var action = event.action, $target = $(event.target), item = event.item;
            if(action.type === 'add')
            {
                window.open(item.taskCreateUrl);
            }
        },
        itemCreator: function($li, item)
        {
            $li.toggleClass('tree-toggle', item.type !== 'task' && item.type !== 'story').closest('li').addClass('item-type-' + item.type);
            var $liWrapper = $li.find('.tree-item-wrapper');
            if(item.type === 'product')
            {
                $liWrapper.append($('<span><i class="icon icon-cube text-muted"></i> ' + item.title + '</span>'));
            }
            else if(item.type === 'story')
            {
                $liWrapper.append('<span class="tree-item-id">' + item.storyId + ' </span><span class="label label-story"><?php echo $lang->story->common ?></span>').append($('<a>').attr({href: item.url}).text(item.title).css('color', item.color));
                if(item.children && item.children.length)
                {
                    if(item.tasksCount) $liWrapper.append(' <span class="label label-task-count label-badge">' + item.tasksCount + '</span>');
                }
            }
            else if(item.type === 'task')
            {
                $liWrapper.append('<span class="tree-item-id">' + item.id + ' </span>').append($('<a>').attr({href: item.url}).text(item.title).css('color', item.color));
                if(item.assignedTo) $liWrapper.append($('<span class="task-assignto"/>').html(item.assignedTo ? ('<i class="icon icon-user text-muted"></i> ' + item.assignedTo) : ''));
                var $info = $('<div class="task-info clearfix"/>');
                $info.append($('<div/>').addClass('status-' + item.status).text(statusMap[item.status]));
                $info.append($('<div/>').text(hoursFormat.replace('%s', item.estimate).replace('%s', item.consumed).replace('%s', item.left)));
                $info.append($('<div class="buttons"/>').html(item.buttons));
                $liWrapper.append($info);
            }
            else if(item.type === 'tasks')
            {
                if(item.tasks && item.tasks.length)
                {
                    var $table = $('<table class="table table-tasks table-fixed table-bordered table-condensed table-hover table-striped"><tbody></tbody></table>');
                    var $tbody = $table.find('tbody');
                    $.each(item.tasks, function(idx, task)
                    {
                        var $tr = $('<tr class="text-center"/>');
                        $tr.append($('<td/>').addClass('text-left').append($('<a>').attr({href: task.url}).text('#' + task.id + ' ' + task.title).css('color', task.color)));
                        $tr.append($('<td class="td-extra" width="30"/>').append('<span class="pri' + task.pri + '">' + (task.pri || '') + '</span>'));
                        $tr.append($('<td class="td-extra" width="90"/>').html(task.assignedTo ? ('<i class="icon icon-user text-muted"></i> ' + task.assignedTo) : ''));
                        $tr.append($('<td class="td-extra" width="70"/>').addClass(task.status).text(statusMap[task.status]));
                        $tr.append($('<td class="td-extra" width="140"/>').text(hoursFormat.replace('%s', task.estimate).replace('%s', task.consumed).replace('%s', task.left)));
                        $tr.append($('<td class="td-extra" width="150"/>').html(task.buttons));
                        $tbody.append($tr);
                    });
                    $liWrapper.append($table);
                }
            }
            else if(item.type === 'unlinkStory')
            {
                $li.append($('<span class="tree-item-title"><i class="icon icon-tasks text-muted"></i> ' + item.title + '</span>'));
                if(item.tasksCount) $li.append(' <span class="label label-task-count label-badge">' + item.tasksCount + '</span>');
            }
            else
            {
                if(item.type === 'module' && (!item.children || !item.children.length))
                {
                    $li.remove();
                }
                else
                {
                    $li.append($('<span class="tree-toggle"><i class="icon icon-bookmark-empty text-muted"></i> ' + (item.title || item.name) + '</span>'));
                }
            }
            return true;
        }
    });

    $('[data-toggle="tooltip"]').tooltip({container: 'body'});
    var $currentLevelBtn = $('.tree-view-btn[data-type="' + viewLevel + '"]').addClass('active');
    if(!$currentLevelBtn.length) selectCustomLevel();

    var tree = $tree.data('zui.tree');

    // Expand all nodes when user visit at first time of this day.
    if(!tree.store.time || tree.store.time < (new Date().getTime() - 24*40*60*1000))
    {
        tree.show($('.item-type-tasks, .item-type-task').parent().parent());
    }

    $(document).on('click', '.tree-view-btn', function()
    {
        var hasActive = $(this).hasClass('active');
        $('.tree-view-btn.active').removeClass('active');
        var level = $(this).addClass('active').data('type');
        if(level === 'task')
        {
            tree.collapse();
            tree.show($('.item-type-tasks, .item-type-task').parent().parent());
        }
        if(level === 'root')
        {
            tree.collapse();
            $(this).html(treeLevel.all);
            if(hasActive)
            {
                $(this).removeClass('active');
                $(this).html(treeLevel.root);
                tree.show($('.item-type-tasks, .item-type-task').parent().parent());
                tree.show($('.item-type-module').parent().parent());
                tree.show($('.item-type-story').parent().parent());
            }
        }
        else if(level === 'module')
        {
            tree.collapse();
            tree.show($('.item-type-module').parent().parent());
        }
        else if(level === 'story')
        {
            tree.collapse();
            tree.show($('.item-type-story').parent().parent());
        }
    });

    setModalInTree(tree);
});
</script>
<?php js::set('treeLevel', $lang->project->treeLevel);?>
<?php include '../../common/view/footer.html.php';?>
