<?php
/**
 * The browse view file of task module of ZenTaoMS.
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
<?php include '../../common/view/header.html.php';?>
<div id='doc3'>
  <table align='center' class='table-4'>
    <caption><?php echo $lang->task->browse;?></caption>
    <tr>
      <th><?php echo $lang->task->id;?></th>
      <th><?php echo $lang->task->name;?></th>
      <th><?php echo $lang->task->owner;?></th>
    </tr>
    <?php foreach($tasks as $task):?>
    <tr>
      <td><?php echo $task->id;?></td>
      <td><?php echo $task->name;?></td>
      <td><?php echo $task->owner;?></td>
    </tr>
    <?php endforeach;?>
  </table>
  <?php 
  $vars['project'] = $project;
  $addLink = $this->createLink($this->moduleName, 'create', $vars);
  echo "<a href='$addLink'>{$lang->task->create}</a>";
  ?>
</div>  
<?php include '../../common/view/footer.html.php';?>
