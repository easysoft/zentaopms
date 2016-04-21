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
          <button type='button' class='btn btn-sm tree-view-btn' data-type='<?php echo $name ?>' data-toggle='tooltip' title='<?php echo $btnLevel['text'] ?>'><i class='icon <?php echo $btnLevel['icon'] ?>'></i></button>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <div class='panel-body no-padding'>
      <ul id='projectTree'></ul>
    </div>
  </div>
</div>

<?php js::set('replaceID', 'taskList')?>
<script>
var projectID = <?php echo $projectID?>;
$('#project<?php echo $projectID;?>').addClass('active')
$('#treeTab').addClass('active');

$(function()
{
    var hoursFormat = '<?php echo $lang->project->hours  ?>';
    var viewLevel = '<?php echo $level ?>' || 'custom';
    var data = $.parseJSON('<?php echo json_encode($tree, JSON_HEX_QUOT | JSON_HEX_APOS);?>');
    var $tree = $('#projectTree');
    var statusMap = $.parseJSON('<?php echo json_encode($lang->task->statusList);?>');
    var selectCustomLevel = function() {$('.tree-view-btn.active').removeClass('active').filter('[data-type="custom"]').addClass('active');};
    $tree.tree(
    {
        initialState: !viewLevel || viewLevel === 'custom' ? 'preserve' : 'collapse',
        data: data,
        itemWrapper: true,
        actions:
        {
            add:
            {
                title: '<?php echo $lang->project->batchWBS ?>',
                template: '<a data-toggle="tooltip" href="javascript:;"><i class="icon icon-sitemap"></i>',
                templateInList: '<a href="javascript:;"><i class="icon icon-sitemap"></i> <?php echo $lang->project->batchWBS ?></a>',
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
            $li.closest('li').addClass('item-type-' + item.type);
            if(item.type === 'product')
            {
                $li.append($('<span><i class="icon icon-cube text-muted"></i> ' + item.title + '</span>')).addClass('tree-toggle');
            }
            else if(item.type === 'story')
            {
                $li.append('<span><i class="icon icon-lightbulb text-muted"></i> </span>').append($('<a>').attr({href: item.url}).text('#' + item.storyId + ' ' + item.title).css('color', item.color));
                if(item.children && item.children.length)
                {
                    if(item.tasksCount) $li.append(' <span class="label label-task-count label-badge">' + item.tasksCount + '</span>');
                }
            }
            else if(item.type === 'task')
            {
                $li.append('<span class="pri' + item.pri + '">' + item.pri + '</span> ').append($('<a>').attr({href: item.url}).text('#' + item.id + ' ' + item.title).css('color', item.color));
            }
            else if(item.type === 'tasks')
            {
                if(item.tasks && item.tasks.length)
                {
                    var $table = $('<table class="table table-fixed table-bordered table-condensed table-hover table-striped"><tbody></tbody></table>');
                    var $tbody = $table.find('tbody');
                    $.each(item.tasks, function(idx, task)
                    {
                        var $tr = $('<tr class="text-center"/>');
                        $tr.append($('<td width="30"/>').append('<span class="pri' + task.pri + '">' + (task.pri || '') + '</span>'));
                        $tr.append($('<td/>').addClass('text-left').append($('<a>').attr({href: task.url}).text('#' + task.id + ' ' + task.title).css('color', task.color)));
                        $tr.append($('<td width="90"/>').html(task.assignedTo ? ('<i class="icon icon-user text-muted"></i> ' + task.assignedTo) : ''));
                        $tr.append($('<td width="70"/>').addClass(task.storyChanged ? 'warning' : task.status).text(statusMap[task.status]));
                        $tr.append($('<td width="140"/>').text(hoursFormat.replace('%s', task.estimate).replace('%s', task.consumed).replace('%s', task.left)));
                        $tr.append($('<td width="130"/>').html(task.buttons));
                        $tbody.append($tr);
                    });
                    $li.append($table);
                }
            }
            else if(item.type === 'unlinkStory')
            {
                $li.append($('<span class="tree-item-title"><i class="icon icon-tasks text-muted"></i> ' + item.title + '</span>')).addClass('tree-toggle');
                if(item.tasksCount) $li.append(' <span class="label label-task-count label-badge">' + item.tasksCount + '</span>');
            }
            else
            {
                $li.addClass('tree-toggle').append($('<span class="tree-toggle"><i class="icon icon-bookmark-empty text-muted"></i> ' + (item.title || item.name) + '</span>'));
            }
        }
    });

    $('[data-toggle="tooltip"]').tooltip({container: 'body'});
    var $currentLevelBtn = $('.tree-view-btn[data-type="' + viewLevel + '"]').addClass('active');
    if(!$currentLevelBtn.length) selectCustomLevel();

    var tree = $tree.data('zui.tree');
    $(document).on('click', '.tree-view-btn', function()
    {
        $('.tree-view-btn.active').removeClass('active');
        var level = $(this).addClass('active').data('type');
        if(level === 'task') tree.expand();
        if(level === 'product') tree.collapse();
        else if(level === 'module')
        {
            tree.collapse();
            tree.show($('.item-type-product'));
        }
        else if(level === 'story')
        {
            tree.collapse();
            tree.show($('.item-type-module'));
        }
    });
});
</script>
<?php include '../../common/view/footer.html.php';?>
