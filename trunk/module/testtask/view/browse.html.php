<?php
/**
 * The browse view file of testtask module of ZenTaoMS.
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
 * @package     testtask
 * @version     $Id: browse.html.php 271 2010-01-09 03:37:02Z wwccss $
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/colorize.html.php';?>
<?php include '../../common/tablesorter.html.php';?>
<div class='yui-d0'>
  <table class='table-1 colored tablesorter'>
    <caption>
      <div class='f-left'><?php echo $lang->testtask->browse;?></div>
      <div class='f-right'><?php common::printLink('testtask', 'create', "product=$productID", $lang->testtask->create);?></div>
    </caption>
    <thead>
    <tr>
      <th><?php echo $lang->testtask->id;?></th>
      <th><?php echo $lang->testtask->product;?></th>
      <th><?php echo $lang->testtask->project;?></th>
      <th><?php echo $lang->testtask->build;?></th>
      <th><?php echo $lang->testtask->name;?></th>
      <th><?php echo $lang->testtask->begin;?></th>
      <th><?php echo $lang->testtask->end;?></th>
      <th><?php echo $lang->testtask->status;?></th>
      <th><?php echo $lang->action;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($tasks as $task):?>
    <tr class='a-center'>
      <td><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id"), sprintf('%03d', $task->id));?></td>
      <td><?php echo $task->productName?></td>
      <td><?php echo $task->projectName?></td>
      <td><?php echo $task->buildName?></td>
      <td width='50%' class='a-left'><?php echo $task->name;?></td>
      <td><?php echo $task->begin?></td>
      <td><?php echo $task->end?></td>
      <td><?php echo $lang->testtask->statusList[$task->status];?></td>
      <td>
        <?php
        common::printLink('testtask', 'edit',     "taskID=$task->id", $lang->edit);
        common::printLink('testtask', 'delete',   "taskID=$task->id", $lang->delete, 'hiddenwin');
        common::printLink('testtask', 'linkcase', "taskID=$task->id", $lang->testtask->linkCase);
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>  
<?php include '../../common/footer.html.php';?>
