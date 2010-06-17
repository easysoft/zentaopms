<?php
/**
 * The edit view of productplan module of ZenTaoMS.
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
<?php include '../../common/view/datepicker.html.php';?>
<div class='yui-d0'>
  <form method='post' target='hiddenwin'>
    <table class='table-1'> 
      <caption><?php echo $lang->productplan->edit;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->productplan->product;?></th>
        <td><?php echo $product->name;?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->productplan->title;?></th>
        <td><?php echo html::input('title', $plan->title, 'class="text-3"');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->productplan->begin;?></th>
        <td><?php echo html::input('begin', $plan->begin, 'class="text-3 date"');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->productplan->end;?></th>
        <td><?php echo html::input('end', $plan->end, 'class="text-3 date"');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->productplan->desc;?></th>
        <td><?php echo html::textarea('desc', $plan->desc, "rows='5' class='area-1'");?></td>
      </tr>  
      <tr>
        <td colspan='2' class='a-center'>
          <?php 
          echo html::submitButton();
          echo html::resetButton();
          echo html::hidden('product', $product->id);
          ?>
        </td>
      </tr>
    </table>
  </form>
</div>  
<?php include '../../common/view/footer.html.php';?>
