<?php
/**
 * The story view file of dashboard module of ZenTaoMS.
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
<?php include '../../common/header.html.php';?>
<?php include '../../common/tablesorter.html.php';?>
<div class='yui-d0'>
  <table class='table-1 fixed tablesorter'>
    <thead>
      <tr class='colhead'>
        <th><?php echo $lang->story->id;?></th>
        <th><?php echo $lang->story->pri;?></th>
        <th class='w-100px'><?php echo $lang->story->product;?></th>
        <th class='w-p40'><?php echo $lang->story->title;?></th>
        <th><?php echo $lang->story->plan;?></th>
        <th><?php echo $lang->story->assignedTo;?></th>
        <th><?php echo $lang->story->openedBy;?></th>
        <th><?php echo $lang->story->estimate;?></th>
        <th><?php echo $lang->story->status;?></th>
        <th class='w-100px'><?php echo $lang->story->lastEditedDate;?></th>
        <th><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($stories as $key => $story):?>
        <tr class='a-center'>
        <td><?php if(!common::printLink('story', 'view', "id=$story->id", sprintf('%03d', $story->id))) printf('%03d', $story->id);?></td>
        <td><?php echo $story->pri;?></td>
        <td><?php echo $story->productTitle;?></td>
        <td class='a-left nobr'><?php echo $story->title;?></td>
        <td><?php echo $story->planTitle;?></td>
        <td><?php echo $users[$story->assignedTo];?></td>
        <td><?php echo $users[$story->openedBy];?></td>
        <td><?php echo $story->estimate;?></td>
        <td class='<?php echo $story->status;?>'><?php $statusList = (array)$lang->story->statusList; echo $statusList[$story->status];?></td>
        <td><?php echo substr($story->lastEditedDate, 5, 11);?></td>
        <td>
          <?php if(common::hasPriv('story', 'edit'))   echo html::a($this->createLink('story', 'edit',   "story={$story->id}"), $lang->edit);?>
          <?php if(common::hasPriv('story', 'delete')) echo html::a($this->createLink('story', 'delete', "story={$story->id}&confirm=no"), $lang->delete, 'hiddenwin');?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/footer.html.php';?>
