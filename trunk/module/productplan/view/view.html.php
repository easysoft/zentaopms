<?php
/**
 * The view of productplan module of ZenTaoMS.
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
<?php include '../../common/header.html.php';?>
<?php include '../../common/tablesorter.html.php';?>
<div class='yui-d0'>
  <table class='table-1'>
  <caption><?php echo $plan->title . $lang->colon . $lang->productplan->view;?></caption>
    <tr>
      <th class='rowhead w-100px'><?php echo $lang->productplan->id;?></th>
      <td><?php echo $plan->id;?></th>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->productplan->title;?></th>
      <td><?php echo $plan->title;?></th>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->productplan->begin;?></th>
      <td><?php echo $plan->begin;?></th>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->productplan->end;?></th>
      <td><?php echo $plan->end;?></th>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->productplan->desc;?></th>
      <td><?php echo nl2br($plan->desc);?></th>
    </tr>
  </table>
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
      <td><?php echo $story->id;?></td>
      <td><?php echo $story->pri;?></td>
      <td><?php echo html::a($this->createLink('product', 'browse', "productID=$story->product"), $products[$story->product], '_blank');?></td>
      <td class='a-left nobr'><?php echo $story->title;?></td>
      <td><?php common::printLink('productplan', 'unlinkStory', "story=$story->id", $lang->productplan->unlinkStory, 'hiddenwin');?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/footer.html.php';?>
