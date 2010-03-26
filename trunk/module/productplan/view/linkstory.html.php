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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     productplan
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'>
  <form method='post'>
    <table align='center' class='table-1 tablesorter a-center'> 
      <caption><?php echo $plan->title .$lang->colon . $lang->productplan->unlinkedStories;?></caption>
      <thead>
      <tr>
        <th class='w-p5'><?php echo $lang->story->id;?></th>
        <th class='w-p5'><?php echo $lang->story->pri;?></th>
        <th class='w-p10'><?php echo $lang->story->product;?></th>
        <th><?php echo $lang->story->plan;?></th>
        <th><?php echo $lang->story->title;?></th>
        <th><?php echo $lang->story->status;?></th>
        <th class='w-p10'><?php echo $lang->story->linkStory;?></th>
      </tr>
      </thead>
      <tbody>
      <?php foreach($allStories as $story):?>
      <?php
      if(isset($planStories[$story->id])) continue;
      if(isset($story->plan) and helper::diffDate($plans[$story->plan]->end, helper::today()) > 0) continue;
      ?>
      <tr>
        <td><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->id);?></td>
        <td><?php echo $story->pri;?></td>
        <td><?php echo html::a($this->createLink('product', 'browse', "productID=$story->product"), $products[$story->product], '_blank');?></td>
        <td><?php echo $story->planTitle;?></td>
        <td class='a-left nobr'><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?></td>
        <td><?php echo $lang->story->statusList[$story->status];?></td>
        <td><input type='checkbox' name='stories[]'  value='<?php echo $story->id;?>' /></td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <tfoot>
      <tr>
        <td colspan='5'><?php echo html::submitButton();?></td>
      </tr>
      </tfoot>
    </table>
  </form>

  <table align='center' class='table-1 tablesorter a-center'> 
    <caption><?php echo $plan->title .$lang->colon . $lang->productplan->linkedStories;?></caption>
    <thead>
    <tr>
      <th class='w-p5'><?php echo $lang->story->id;?></th>
      <th class='w-p5'><?php echo $lang->story->pri;?></th>
      <th class='w-p10'><?php echo $lang->story->product;?></th>
      <th><?php echo $lang->story->title;?></th>
      <th class='w-p10'><?php echo $lang->actions?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($planStories as $story):?>
    <tr>
      <td><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->id);?></td>
      <td><?php echo $story->pri;?></td>
      <td><?php echo html::a($this->createLink('product', 'browse', "productID=$story->product"), $products[$story->product], '_blank');?></td>
      <td class='a-left nobr'><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?></td>
      <td><?php common::printLink('productplan', 'unlinkStory', "story=$story->id", $lang->productplan->unlinkStory, 'hiddenwin');?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
