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
  <tr class='colhead'>
    <th><?php echo $lang->bug->id;?></th>
    <th><?php echo $lang->bug->severity;?></th>
    <th><?php echo $lang->bug->title;?></th>
    <th><?php echo $lang->bug->openedBy;?></th>
    <th><?php echo $lang->bug->assignedTo;?></th>
    <th><?php echo $lang->bug->resolvedBy;?></th>
    <th><?php echo $lang->bug->resolution;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($bugs as $bug):?>
  <tr class='a-center'>
    <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->id, '_blank');?></td>
    <td><?php echo $bug->severity?></td>
    <td width='50%' class='a-left'><?php echo $bug->title;?></td>
    <td><?php echo $bug->openedBy;?></td>
    <td><?php echo $bug->assignedTo;?></td>
    <td><?php echo $bug->resolvedBy;?></td>
    <td><?php echo $bug->resolution;?></td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
<?php include './footer.html.php';?>
