<?php
/**
 * The link story view of productplan module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'>
  <form method='post'>
    <table class='table-1 tablesorter a-center'> 
      <caption class='caption-tl'><?php echo $plan->title .$lang->colon . $lang->productplan->unlinkedStories;?></caption>
      <thead>
      <tr class='colhead'>
        <th class='w-id'><?php echo $lang->idAB;?></th>
        <th class='w-pri'><?php echo $lang->priAB;?></th>
        <th><?php echo $lang->story->plan;?></th>
        <th><?php echo $lang->story->title;?></th>
        <th><?php echo $lang->openedByAB;?></th>
        <th><?php echo $lang->assignedToAB;?></th>
        <th><?php echo $lang->story->estimateAB;?></th>
        <th><?php echo $lang->statusAB;?></th>
        <th class='w-40px {sorter: false}'><?php echo $lang->link;?></th>
      </tr>
      </thead>
      <tbody>
      <?php foreach($allStories as $story):?>
      <?php
      if(isset($planStories[$story->id])) continue;
      if($story->plan and helper::diffDate($plans[$story->plan], helper::today()) > 0) continue;
      ?>
      <tr>
        <td><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->id);?></td>
        <td><?php echo $story->pri;?></td>
        <td><?php echo $story->planTitle;?></td>
        <td class='a-left nobr'><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?></td>
        <td><?php echo $users[$story->openedBy];?></td>
        <td><?php echo $users[$story->assignedTo];?></td>
        <td><?php echo $story->estimate;?></td>
        <td><?php echo $lang->story->statusList[$story->status];?></td>
        <td><input type='checkbox' name='stories[]'  value='<?php echo $story->id;?>' /></td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <tfoot>
      <tr>
        <td colspan='9'><?php echo html::submitButton($lang->story->linkStory);?></td>
      </tr>
      </tfoot>
    </table>
  </form>

  <table class='table-1 tablesorter a-center'> 
    <caption class='caption-tl'><?php echo $plan->title .$lang->colon . $lang->productplan->linkedStories;?></caption>
    <thead>
    <tr class='colhead'>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th class='w-pri'><?php echo $lang->priAB;?></th>
      <th><?php echo $lang->story->title;?></th>
      <th><?php echo $lang->openedByAB;?></th>
      <th><?php echo $lang->assignedToAB;?></th>
      <th><?php echo $lang->story->estimateAB;?></th>
      <th><?php echo $lang->statusAB;?></th>
      <th><?php echo $lang->story->stageAB;?></th>
      <th class='w-p10 {sorter:false}'><?php echo $lang->actions?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($planStories as $story):?>
    <tr>
      <td><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->id);?></td>
      <td><?php echo $story->pri;?></td>
      <td class='a-left nobr'><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?></td>
      <td><?php echo $users[$story->openedBy];?></td>
      <td><?php echo $users[$story->assignedTo];?></td>
      <td><?php echo $story->estimate;?></td>
      <td><?php echo $lang->story->statusList[$story->status];?></td>
      <td><?php echo $lang->story->stageList[$story->stage];?></td>
      <td><?php common::printLink('productplan', 'unlinkStory', "story=$story->id", $lang->productplan->unlinkStory, 'hiddenwin');?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
