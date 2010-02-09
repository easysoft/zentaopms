<?php
/**
 * The task view file of dashboard module of ZenTaoMS.
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
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/tablesorter.html.php';?>
<div class='yui-d0'>
  <table class='table-1 tablesorter fixed' id='tasktable'>
    <thead>
    <tr class='colhead'>
      <th><?php echo $lang->task->id;?></th>
      <th class='w-p30'><?php echo $lang->task->name;?></th>
      <th class='w-p10'><?php echo $lang->task->project;?></th>
      <th><?php echo $lang->task->pri;?></th>
      <th><?php echo $lang->task->estimate;?></th>
      <th><?php echo $lang->task->consumed;?></th>
      <th class='w-p30'><?php echo $lang->task->story;?></th>
      <th><?php echo $lang->task->status;?></th>
      <th><?php echo $lang->actions;?></th>
    </tr>
    </thead>   
    <tbody>
    <?php foreach($tasks as $task):?>
    <tr class='a-center'>
      <td><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), sprintf('%03d', $task->id));?></td>
      <td class='a-left nobr'><?php echo $task->name;?></td>
      <td class='nobr'><?php echo html::a($this->createLink('project', 'browse', "projectid=$task->projectID"), $task->projectName);?></th>
      <td><?php echo $task->pri;?></td>
      <td><?php echo $task->estimate;?></td>
      <td><?php echo $task->consumed;?></td>
      <td class='a-left nobr'><?php if($task->storyID) echo html::a($this->createLink('story', 'view', "storyID=$task->storyID"), $task->storyTitle);?></td>
      <td class='<?php echo $task->status;?>'><?php echo $lang->task->statusList->{$task->status};?></td>
      <td><?php echo html::a($this->createLink('task', 'edit', "taskID=$task->id"), $lang->task->edit, '_blank');?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table> 
</div>
<?php include '../../common/footer.html.php';?>
