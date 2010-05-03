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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'>
  <table class='table-1 tablesorter' id='tasktable'>
    <thead>
    <tr class='colhead'>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th class='w-pri'><?php echo $lang->priAB;?></th>
      <th><?php echo $lang->task->project;?></th>
      <th><?php echo $lang->task->name;?></th>
      <th class='w-hour'><?php echo $lang->task->estimateAB;?></th>
      <th class='w-hour'><?php echo $lang->task->consumedAB;?></th>
      <th class='w-hour'><?php echo $lang->task->leftAB;?></th>
      <th class='w-date'><?php echo $lang->task->deadlineAB;?></th>
      <th class='w-status'><?php echo $lang->statusAB;?></th>
    </tr>
    </thead>   
    <tbody>
    <?php foreach($tasks as $task):?>
    <tr class='a-center'>
      <td><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), sprintf('%03d', $task->id));?></td>
      <td><?php echo $lang->task->priList[$task->pri];?></td>
      <td class='nobr'><?php echo html::a($this->createLink('project', 'browse', "projectid=$task->projectID"), $task->projectName);?></th>
      <td class='a-left nobr'><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name);?></td>
      <td><?php echo $task->estimate;?></td>
      <td><?php echo $task->consumed;?></td>
      <td><?php echo $task->left;?></td>
      <td class=<?php if(isset($task->delay)) echo 'delayed';?>><?php if(substr($task->deadline, 0, 4) > 0) echo $task->deadline;?></td>
      <td class='<?php echo $task->status;?>'><?php echo $lang->task->statusList[$task->status];?></td>
    <?php endforeach;?>
    </tbody>
  </table> 
</div>
<?php include '../../common/view/footer.html.php';?>
