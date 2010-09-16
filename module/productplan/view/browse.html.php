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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     plan
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'>
  <table class='table-1 tablesorter fixed'>
    <caption class='caption-tr'>
      <div class='f-left'><?php echo $lang->productplan->browse;?></div>
      <div class='f-right'><?php common::printLink('productplan', 'create', "productID=$product->id", $lang->productplan->create);?></div>
    </caption>
    <thead>
    <tr class='colhead'>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th class='w-100px'><?php echo $lang->productplan->begin;?></th>
      <th class='w-100px'><?php echo $lang->productplan->end;?></th>
      <th><?php echo $lang->productplan->title;?></th>
      <th class='w-p50'><?php echo $lang->productplan->desc;?></th>
      <th class="w-130px {sorter: false}"><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($plans as $plan):?>
    <tr class='a-center'>
      <td><?php echo html::a(inlink('view', "id=$plan->id"), $plan->id);?></td>
      <td><?php echo $plan->begin;?></td>
      <td><?php echo $plan->end;?></td>
      <td class='a-left nobr'><?php echo html::a(inlink('view', "id=$plan->id"), $plan->title);?></td>
      <td class='a-left nobr'><?php echo nl2br($plan->desc);?></td>
      <td>
        <?php
        common::printLink('productplan', 'edit', "planID=$plan->id", $lang->edit);
        common::printLink('productplan', 'linkstory', "planID=$plan->id", $lang->productplan->linkStory);
        common::printLink('productplan', 'delete', "planID=$plan->id", $lang->delete, 'hiddenwin');
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>  
<?php include '../../common/view/footer.html.php';?>
