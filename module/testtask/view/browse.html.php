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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     testtask
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'>
  <table class='table-1 colored tablesorter fixed'>
    <caption class='caption-tl'>
      <div class='f-left'><?php echo $lang->testtask->browse;?></div>
      <div class='f-right'><?php common::printLink('testtask', 'create', "product=$productID", $lang->testtask->create);?></div>
    </caption>
    <thead>
    <tr class='colhead'>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th><?php echo $lang->testtask->name;?></th>
      <th><?php echo $lang->testtask->project;?></th>
      <th><?php echo $lang->testtask->build;?></th>
      <th class='w-80px'><?php echo $lang->testtask->begin;?></th>
      <th class='w-80px'><?php echo $lang->testtask->end;?></th>
      <th class='w-50px'><?php echo $lang->statusAB;?></th>
      <th class='w-120px {sorter:false}'><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($tasks as $task):?>
    <tr class='a-center'>
      <td><?php echo html::a(inlink('view', "taskID=$task->id"), sprintf('%03d', $task->id));?></td>
      <td class='a-left nobr'><?php echo html::a(inlink('view', "taskID=$task->id"), $task->name);?></td>
      <td class='nobr'><?php echo $task->projectName?></td>
      <td class='nobr'><?php $task->build == 'trunk' ? print('Trunk') : print(html::a($this->createLink('build', 'view', "buildID=$task->build"), $task->buildName));?></td>
      <td><?php echo $task->begin?></td>
      <td><?php echo $task->end?></td>
      <td><?php echo $lang->testtask->statusList[$task->status];?></td>
      <td>
        <?php
        common::printLink('testtask', 'cases',    "taskID=$task->id", $lang->testtask->cases);
        common::printLink('testtask', 'linkcase', "taskID=$task->id", $lang->testtask->linkCaseAB);
        common::printLink('testtask', 'edit',     "taskID=$task->id", $lang->edit);
        common::printLink('testtask', 'delete',   "taskID=$task->id", $lang->delete, 'hiddenwin');
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>  
<?php include '../../common/view/footer.html.php';?>
