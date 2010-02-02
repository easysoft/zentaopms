<?php
/**
 * The bug view file of project module of ZenTaoMS.
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
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/colorize.html.php';?>
<div class='yui-d0'>
  <div id='featurebar'>
    <div class='f-right'><?php common::printLink('bug', 'create', "productID=0&extra=projectID=$project->id", $lang->bug->create);?></div>
  </div>
</div>
<div class='yui-d0'>
  <table class='table-1 fixed colored'>
    <thead>
    <tr class='colhead'>
      <th><?php echo $lang->bug->id;?></th>
      <th><?php echo $lang->bug->severity;?></th>
      <th class='w-p50'><?php echo $lang->bug->title;?></th>
      <th><?php echo $lang->bug->openedBy;?></th>
      <th><?php echo $lang->bug->assignedTo;?></th>
      <th><?php echo $lang->bug->resolvedBy;?></th>
      <th><?php echo $lang->bug->resolution;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($bugs as $bug):?>
    <tr class='a-center'>
      <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), sprintf('%03d', $bug->id));?></td>
      <td><?php echo $bug->severity?></td>
      <td class='a-left nobr'><?php echo $bug->title;?></td>
      <td><?php echo $users[$bug->openedBy];?></td>
      <td><?php echo $users[$bug->assignedTo];?></td>
      <td><?php echo $users[$bug->resolvedBy];?></td>
      <td><?php echo $bug->resolution;?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <div class='a-right'><?php echo $pager;?></div>
</div>  
<?php include '../../common/footer.html.php';?>
