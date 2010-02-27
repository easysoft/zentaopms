<?php
/**
 * The edit view of task module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<form method='post' enctype='multipart/form-data' target='hiddenwin'>
<div class='yui-d0'>
  <div id='titlebar'>
    <div id='main'>TASK #<?php echo $task->id . $lang->colon . html::input('name', $task->name, 'class="text-1"');?></div>
    <div><?php echo html::submitButton();?></div>
  </div>
</div>

<div class='yui-d0 yui-t8'>
  <div class='yui-main'>
    <div class='yui-b'>
      <fieldset>
        <legend><?php echo $lang->task->desc;?></legend>
        <?php echo html::textarea('desc', $task->desc, "rows='4' class='area-1'");?>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->comment;?></legend>
        <?php echo html::textarea('comment', '',  "rows='5' class='area-1'");?>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->files;?></legend>
        <?php echo $this->fetch('file', 'buildform');?>
      </fieldset>
      <div class='a-center'><?php echo html::submitButton() . html::resetButton();?></div>
      <?php include '../../common/action.html.php';?>
    </div>
  </div>
  <div class='yui-b'>
    <fieldset>
      <legend><?php echo $lang->task->legendBasic;?></legend>
      <table class='table-1'> 
        <tr>
          <th class='rowhead w-p20'><?php echo $lang->task->project;?></th>
          <td><?php echo $project->name;?></td>
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->task->story;?></th>
          <td><?php echo html::select('story', $stories, $task->story, 'class=select-1');?> 
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->task->owner;?></th>
          <td><?php echo html::select('owner', $members, $task->owner, 'class=select-1');?> 
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->task->type;?></th>
          <td><?php echo html::select('type', $lang->task->typeList, $task->type, 'class=select-1');?></td>
        </tr>
        <tr>
          <th class='rowhead'><?php echo $lang->task->status;?></th>
          <td><?php echo html::select('status', (array)$lang->task->statusList, $task->status, 'class=select-1');?></td>
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->task->pri;?></th>
          <td><?php echo html::select('pri', $lang->task->priList, $task->pri, 'class=select-1');?> 
        </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend><?php echo $lang->task->legendEffort;?></legend>
      <table class='table-1'> 
        <tr>
          <th class='rowhead'><?php echo $lang->task->deadline;?></th>
          <td><?php echo html::input('deadline', $task->deadline, "class='text-1'");?></td>
        </tr>  
        <tr>
          <th class='rowhead w-p20'><?php echo $lang->task->estimate;?></th>
          <td><?php echo html::input('estimate', $task->estimate, "class='text-1'");?></td>
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->task->consumed;?></th>
          <td><input type='text' name='consumed' value='<?php echo $task->consumed;?>' class='text-1' /></td>
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->task->left;?></th>
          <td><input type='text' name='left' value='<?php echo $task->left;?>' class='text-1' /></td>
        </tr>
      </table>
    </fieldset>
  </div>
</div>
</form>
<?php include '../../common/footer.html.php';?>
