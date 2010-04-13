<?php
/**
 * The bug view file of dashboard module of ZenTaoMS.
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
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'>
  <table class='table-1 fixed tablesorter'>
    <thead>
    <tr class='colhead'>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th class='w-severity'><?php echo $lang->bug->severityAB;?></th>
      <th class='w-pri'><?php echo $lang->priAB;?></th>
      <th class='w-type'><?php echo $lang->typeAB;?></th>
      <th><?php echo $lang->bug->title;?></th>
      <th class='w-user'><?php echo $lang->openedByAB;?></th>
      <th class='w-user'><?php echo $lang->assignedToAB;?></th>
      <th class='w-user'><?php echo $lang->bug->resolvedBy;?></th>
      <th class='w-resolution'><?php echo $lang->bug->resolutionAB;?></th>
      <th class='w-100px {sorter:false}'><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($bugs as $bug):?>
    <tr class='a-center'>
      <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->id, '_blank');?></td>
      <td><?php echo $lang->bug->severityList[$bug->severity]?></td>
      <td><?php echo $lang->bug->priList[$bug->pri]?></td>
      <td><?php echo $lang->bug->typeList[$bug->type]?></td>
      <td class='a-left nobr'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?></td>
      <td><?php echo $users[$bug->openedBy];?></td>
      <td><?php echo $users[$bug->assignedTo];?></td>
      <td><?php echo $users[$bug->resolvedBy];?></td>
      <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
      <td>
        <?php
        $params = "bugID=$bug->id";
        if(!($bug->status == 'active'   and common::printLink('bug', 'resolve', $params, $lang->bug->buttonResolve))) echo $lang->bug->buttonResolve . ' ';
        if(!($bug->status == 'resolved' and common::printLink('bug', 'close', $params, $lang->bug->buttonClose)))     echo $lang->bug->buttonClose . ' ';
        common::printLink('bug', 'edit', $params, $lang->bug->buttonEdit);
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
