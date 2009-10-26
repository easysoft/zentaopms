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
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<table align='center' class='table-1 tablesorter'>
  <thead>
  <tr>
    <th><?php echo $lang->task->id;?></th>
    <th><?php echo $lang->task->name;?></th>
    <th><?php echo $lang->task->owner;?></th>
    <th><?php echo $lang->task->estimate;?></th>
    <th><?php echo $lang->task->consumed;?></th>
    <th><?php echo $lang->task->left;?></th>
    <th><?php echo $lang->task->status;?></th>
    <th><?php echo $lang->task->story;?></th>
    <th><?php echo $lang->action;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($tasks as $task):?>
  <?php $class = $task->owner == $app->user->account ? 'style=color:red' : '';?>
  <tr class='a-center'>
    <td class='a-right'><?php echo $task->id;?></td>
    <td class='a-left'><?php echo $task->name;?></td>
    <td <?php echo $class;?>><?php echo $task->ownerRealName;?></td>
    <td><?php echo $task->estimate;?></td>
    <td><?php echo $task->consumed;?></td>
    <td><?php echo $task->left;?></td>
    <td class=<?php echo $task->status;?> ><?php echo $lang->task->statusList->{$task->status};?></td>
    <td class='a-left'><?php echo $task->storyTitle;?></td>
    <td>
      <?php if(common::hasPriv('task', 'edit'))   echo html::a($this->createLink('task', 'edit',   "taskid=$task->id"), $lang->task->edit);?>
      <?php if(common::hasPriv('task', 'delete')) echo html::a($this->createLink('task', 'delete', "projectID=$task->project&taskid=$task->id"), $lang->task->delete, 'hiddenwin');?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
