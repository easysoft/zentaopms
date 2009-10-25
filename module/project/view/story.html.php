<?php
/**
 * The browse view file of story module of ZenTaoMS.
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
 * @package     story
 * @version     $Id: story.html.php 1448 2009-10-22 08:30:24Z wwccss $
 * @link        http://www.zentao.cn
 */
?>
<table align='center' class='table-1 tablesorter'>
  <thead>
  <tr>
    <th><?php echo $lang->story->id;?></th>
    <th><?php echo $lang->story->pri;?></th>
    <th><?php echo $lang->story->title;?></th>
    <th><?php echo $lang->story->spec;?></th>
    <th><?php echo $lang->story->product;?></th>
    <th><?php echo $lang->story->status;?></th>
    <th><?php echo $lang->action;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($stories as $story):?>
  <tr class='a-center'>
    <td class='a-right'><?php echo $story->id;?></td>
    <td><?php echo $story->pri;?></td>
    <td class='a-left'><?php echo $story->title;?></td>
    <td class='a-left'><?php echo $story->spec;?></td>
    <td><?php echo html::a($this->createLink('product', 'browse', "product=$story->product"), $products[$story->product], '_blank');?></td>
    <td class='<?php echo $story->status;?>'><?php $lang->show($lang->story->statusList, $story->status);?></td>
    <td>
      <?php if(common::hasPriv('task', 'create'))         echo html::a($this->createLink('task', 'create', "projectid=$project->id&storyid=$story->id"), $lang->task->create);?>
      <?php if(common::hasPriv('project', 'unlinkstory')) echo html::a($this->createLink('project', 'unlinkstory', "projectid=$project->id&storyid=$story->id"), $lang->project->unlinkStory, 'hiddenwin');?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
