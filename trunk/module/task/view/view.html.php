<?php
/**
 * The view file of task module of ZenTaoMS.
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
<?php include '../../common/header.html.php';?>

<div class='yui-d0'>
  <div id='titlebar'>
    <div id='main'>TASK #<?php echo $task->id . $lang->colon . $task->name;?></div>
    <div>
    <?php
    if(common::hasPriv('task', 'edit')) echo html::a($this->createLink('task', 'edit', "taskID=$task->id"),  $lang->task->buttonEdit);
    if(common::hasPriv('project', 'task')) echo html::a($app->session->taskList,  $lang->task->buttonBackToList);
    ?>
    </div>
  </div>
</div>
</div>  

<div class='yui-d0'>

  <fieldset>
    <legend><?php echo $lang->task->legendBasic;?></legend>
    <table align='center' class='table-1 a-left'> 
      <tr>
        <th class='rowhead'><?php echo $lang->task->project;?></th>
        <td><?php echo $project->name;?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->story;?></th>
        <td><?php echo $task->storyTitle;?>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->owner;?></th>
        <td><?php echo $task->ownerRealName;?> 
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->estimate;?></th>
        <td><?php echo $task->estimate;?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->consumed;?></th>
        <td><?php echo $task->consumed;?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->left;?></th>
        <td><?php echo $task->left;?></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->task->status;?></th>
        <td><?php $lang->show($lang->task->statusList, $task->status);?></td>
      </tr>  
    </table>
  </fieldset>
  <fieldset>
    <legend><?php echo $lang->task->legendDesc;?></legend>
    <div><?php echo nl2br($task->desc);?></div>
  </fieldset>
  <fieldset>
    <legend><?php echo $lang->task->legendAction;?></legend>
    <div class='a-center' style='font-size:16px; font-weight:bold'>
      <?php
      if(common::hasPriv('task', 'edit')) echo html::a($this->createLink('task', 'edit', "taskID=$task->id"),  $lang->task->buttonEdit);
      if(common::hasPriv('project', 'task')) echo html::a($app->session->taskList,  $lang->task->buttonBackToList);
      ?>
    </div>
  </fieldset>
  <?php include '../../common/action.html.php';?>

</div>
<?php include '../../common/footer.html.php';?>
