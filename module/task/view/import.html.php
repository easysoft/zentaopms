<?php
/**
 * The import view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<table class='table-1 fixed tablesorter'>
  <thead>
  <tr class='colhead'>
    <th><?php echo $lang->task->id;?></th>
    <th><?php echo $lang->task->pri;?></th>
    <th><?php echo $lang->task->name;?></th>
    <th><?php echo $lang->task->assignedTo;?></th>
    <th><?php echo $lang->task->estimate;?></th>
    <th><?php echo $lang->task->consumed;?></th>
    <th><?php echo $lang->task->left;?></th>
    <th><?php echo $lang->task->type;?></th>
    <th><?php echo $lang->task->status;?></th>
    <th class='w-p30'><?php echo $lang->task->story;?></th>
    <th class='w-100px'><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($tasks as $task):?>
  <?php $class = $task->assignedTo == $app->user->account ? 'style=color:red' : '';?>
  <tr class='a-center'>
    <td><?php if(common::hasPriv('task', 'view')) echo html::a($this->createLink('task', 'view', "task=$task->id"), sprintf('%03d', $task->id)); else printf('%03d', $task->id);?></td>
    <td><?php echo $task->pri;?></td>
    <td class='a-left nobr'><?php echo $task->name;?></td>
    <td <?php echo $class;?>><?php echo $task->assignedToRealName;?></td>
    <td><?php echo $task->estimate;?></td>
    <td><?php echo $task->consumed;?></td>
    <td><?php echo $task->left;?></td>
    <td><?php echo $lang->task->typeList[$task->type];?></td>
    <td class=<?php echo $task->status;?> ><?php echo $lang->task->statusList->{$task->status};?></td>
    <td class='a-left nobr'>
      <?php 
      if($task->storyID)
      {
          if(common::hasPriv('story', 'view')) echo html::a($this->createLink('story', 'view', "storyid=$task->storyID"), $task->storyTitle);
          else echo $task->storyTitle;
      }
      ?>
    </td>
    <td>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
<?php include '../../common/view/footer.html.php';?>
