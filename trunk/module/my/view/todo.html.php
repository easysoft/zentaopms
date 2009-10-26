<?php
/**
 * The todo view file of dashboard module of ZenTaoMS.
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
<?php include './header.html.php';?>
<table class='table-1 tablesorter'>
  <thead>
  <tr>
    <th><?php echo $lang->todo->id;?></th>
    <th><?php echo $lang->todo->type;?></th>
    <th><?php echo $lang->todo->pri;?></th>
    <th><?php echo $lang->todo->name;?></th>
    <th><?php echo $lang->todo->begin;?></th>
    <th><?php echo $lang->todo->end;?></th>
    <!--<th><?php echo $lang->todo->desc;?></th>-->
    <th><?php echo $lang->todo->status;?></th>
    <th><?php echo $lang->action;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($todos as $todo):?>
  <tr class='a-center'>
    <td><?php echo $todo->id;?></td>
    <td><?php echo $lang->todo->typeList->{$todo->type};?></td>
    <td><?php echo $todo->pri;?></td>
    <td class='a-left'>
	  <?php 
	  if($todo->type == 'bug')    $link = $this->createLink('bug',  'view', "id={$todo->idvalue}");
	  if($todo->type == 'task')   $link = $this->createLink('task', 'edit', "id={$todo->idvalue}");
	  if($todo->type == 'custom') $link = $this->createLink('todo', 'edit', "id={$todo->id}");
	  echo html::a($link, $todo->name);
	  ?>
	</td>
    <td><?php echo $todo->begin;?></td>
    <td><?php echo $todo->end;?></td>
    <!--<td><?php echo $todo->desc;?></td>-->
	<td class='<?php echo $todo->status;?>'><?php echo $lang->todo->statusList->{$todo->status};?></td>
	<td>
	  <?php 
	  echo html::a($this->createLink('todo', 'mark',   "id=$todo->id&status=$todo->status"), $lang->todo->{'mark'.ucfirst($todo->status)}, 'hiddenwin');
	  echo html::a($this->createLink('todo', 'edit',   "id=$todo->id"), $lang->todo->edit);
	  echo html::a($this->createLink('todo', 'delete', "id=$todo->id"), $lang->todo->delete, 'hiddenwin');
	  ?>
	</td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
<?php include './footer.html.php';?>
