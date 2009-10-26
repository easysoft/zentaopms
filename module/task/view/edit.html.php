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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<div id='doc3'>
  <form method='post'>
    <table align='center' class='table-1 a-left'> 
      <caption><?php echo $header['title'];?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->task->project;?></th>
        <td><?php echo $project->name;?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->story;?></th>
        <td><?php echo html::select('storyID', $stories, $task->story, 'class=select-3');?> 
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->owner;?></th>
        <td><?php echo html::select('owner', $members, $task->owner, 'class=select-3');?> 
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->name;?></th>
        <td><input type='text' name='name' value='<?php echo $task->name;?>' class='text-3' /></td>
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
        <th class='rowhead'><?php echo $lang->task->status;?></th>
        <td><?php echo html::select('status', (array)$lang->task->statusList, $task->status, 'class=select-3');?></td>
      </tr>  

      <tr>
        <th class='rowhead'><?php echo $lang->task->desc;?></th>
        <td><textarea name='desc' rows='5' class='area-1'><?php echo $task->desc;?></textarea>
      </tr>  
      <tr>
        <td colspan='2' class='a-center'><input type='submit' name='submit' value='<?php echo $lang->save;?>' class='button-s' /></td>
      </tr>
    </table>
  </form>
</div>  
<?php include '../../common/footer.html.php';?>
