<?php
/**
 * The browse view file of plan module of ZenTaoMS.
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
 * @package     plan
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/tablesorter.html.php';?>
<div class='yui-d0'>
  <table class='table-1 tablesorter fixed'>
    <caption>
      <div class='f-left'><?php echo $lang->productPlan->browse;?></div>
      <div class='f-right'><?php common::printLink('productPlan', 'create', "productID=$product->id", $lang->productPlan->create);?></div>
    </caption>
    <thead>
    <tr>
      <th><?php echo $lang->productPlan->id;?></th>
      <th class='w-p50'><?php echo $lang->productPlan->title;?></th>
      <th><?php echo $lang->productPlan->desc;?></th>
      <th><?php echo $lang->productPlan->begin;?></th>
      <th><?php echo $lang->productPlan->end;?></th>
      <th class='w-p20'><?php echo $lang->action;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($plans as $plan):?>
    <tr class='a-center'>
      <td><?php echo $plan->id;?></td>
      <td class='a-left nobr'><?php echo $plan->title;?></td>
      <td><?php echo $plan->desc;?></td>
      <td><?php echo $plan->begin;?></td>
      <td><?php echo $plan->end;?></td>
      <td class='nobr'>
        <?php
        common::printLink('productplan', 'edit', "planID=$plan->id", $lang->productPlan->edit);
        common::printLink('productplan', 'linkstory', "planID=$plan->id", $lang->productPlan->linkStory);
        common::printLink('productplan', 'delete', "planID=$plan->id", $lang->productPlan->delete, 'hiddenwin');
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>  
<?php include '../../common/footer.html.php';?>
