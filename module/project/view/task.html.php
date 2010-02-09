<?php
/**
 * The task view file of project module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/colorize.html.php';?>
<div class="yui-d0">
  <div id='featurebar'>
    <div class='f-left'>
    <?php include './project.html.php';?>
    </div>
    <div class='f-right'>
      <?php
      common::printLink('task', 'create', "project=$project->id", $lang->task->create);
      //common::printLink('task', 'import', "project=$project->id", $lang->task->import);
      ?>
    </div>
  </div>
  <table class='table-1 fixed colored'>
    <?php $vars = "projectID=$project->id&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage"; ?>
    <thead>
    <tr class='colhead'>
      <th><?php common::printOrderLink('id',       $orderBy, $vars, $lang->task->id);?></th>
      <th><?php common::printOrderLink('pri',      $orderBy, $vars, $lang->task->pri);?></th>
      <th class='w-p30'><?php common::printOrderLink('name',     $orderBy, $vars, $lang->task->name);?></th>
      <th><?php common::printOrderLink('owner',    $orderBy, $vars, $lang->task->owner);?></th>
      <th><?php common::printOrderLink('estimate', $orderBy, $vars, $lang->task->estimate);?></th>
      <th><?php common::printOrderLink('consumed', $orderBy, $vars, $lang->task->consumed);?></th>
      <th><?php common::printOrderLink('`left`',   $orderBy, $vars, $lang->task->left);?></th>
      <th><?php common::printOrderLink('type',     $orderBy, $vars, $lang->task->type);?></th>
      <th><?php common::printOrderLink('status',   $orderBy, $vars, $lang->task->status);?></th>
      <th class='w-p30'><?php common::printOrderLink('story',    $orderBy, $vars, $lang->task->story);?></th>
      <th class='w-100px'><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($tasks as $task):?>
    <?php $class = $task->owner == $app->user->account ? 'style=color:red' : '';?>
    <tr class='a-center'>
      <td><?php if(common::hasPriv('task', 'view')) echo html::a($this->createLink('task', 'view', "task=$task->id"), sprintf('%03d', $task->id)); else printf('%03d', $task->id);?></td>
      <td><?php echo $task->pri;?></td>
      <td class='a-left nobr'><?php echo $task->name;?></td>
      <td <?php echo $class;?>><?php echo $task->ownerRealName;?></td>
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
        <?php if(common::hasPriv('task', 'edit'))   echo html::a($this->createLink('task', 'edit',   "taskid=$task->id"), $lang->edit);?>
        <?php if(common::hasPriv('task', 'delete')) echo html::a($this->createLink('task', 'delete', "projectID=$task->project&taskid=$task->id"), $lang->delete, 'hiddenwin');?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <div class='a-right'><?php echo $pager;?></div>
</div>  
<?php include '../../common/footer.html.php';?>
