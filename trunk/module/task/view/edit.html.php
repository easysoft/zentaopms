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
<div class='yui-d0'>
  <form method='post' enctype='multipart/form-data' target='hiddenwin'>
    <table class='table-1'> 
      <caption><?php echo $header->title;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->task->project;?></th>
        <td><?php echo $project->name;?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->story;?></th>
        <td><?php echo html::select('story', $stories, $task->story, 'class=select-1');?> 
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->name;?></th>
        <td><input type='text' name='name' value='<?php echo $task->name;?>' class='text-1' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->desc;?></th>
        <td><textarea name='desc' rows='5' class='area-1'><?php echo $task->desc;?></textarea>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->files;?></th>
        <td class='a-left'><?php echo $this->fetch('file', 'buildform');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->owner;?></th>
        <td><?php echo html::select('owner', $members, $task->owner, 'class=select-3');?> 
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->estimate;?></th>
        <td><input type='text' name='estimate' value='<?php echo $task->estimate;?>' class='text-3' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->consumed;?></th>
        <td><input type='text' name='consumed' value='<?php echo $task->consumed;?>' class='text-3' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->left;?></th>
        <td><input type='text' name='left' value='<?php echo $task->left;?>' class='text-3' /></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->task->type;?></th>
        <td><?php echo html::select('type', $lang->task->typeList, $task->type, 'class=select-3');?></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->task->status;?></th>
        <td><?php echo html::select('status', (array)$lang->task->statusList, $task->status, 'class=select-3');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->pri;?></th>
        <td><?php echo html::select('pri', $lang->task->priList, $task->pri, 'class=select-3');?> 
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->comment;?></th>
        <td><textarea name='comment' rows='5' class='area-1'></textarea>
      </tr>  
      <tr>
        <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td>
      </tr>
    </table>
  </form>
</div>  
<?php include '../../common/footer.html.php';?>
