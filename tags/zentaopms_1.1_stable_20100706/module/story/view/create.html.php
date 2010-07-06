<?php
/**
 * The create view of story module of ZenTaoMS.
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
 * @package     story
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
?>
<?php include './header.html.php';?>
<style>#plan {width:245px}</style>
<div class='yui-d0'>
  <form method='post' enctype='multipart/form-data' target='hiddenwin'>
    <table align='center' class='table-1'> 
      <caption><?php echo $lang->story->create;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->story->product;?></th>
        <td>
          <?php echo html::select('product', $products, $product->id, "onchange=loadProduct(this.value); class='select-3'");?>
          <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID);?></span>
        </td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->story->plan;?></th>
        <td><span id='planIdBox'><?php echo html::select('plan', $plans, '', 'class=select-3');?></span></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->story->pri;?></th>
        <td><?php echo html::select('pri', (array)$lang->story->priList, '', 'class=select-3');?></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->story->estimate;?></th>
        <td><?php echo html::input('estimate', '', "class='text-3'");?></td>
      </tr> 
      <tr>
        <th class='rowhead'><?php echo $lang->story->reviewedBy;?></th>
        <td><?php echo html::select('assignedTo', $users, '', 'class=select-3') . html::checkbox('needNotReview', $lang->story->needNotReview, '', "id='needNotReview'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->story->title;?></th>
        <td><?php echo html::input('title', '', "class='text-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->story->spec;?></th>
        <td><?php echo html::textarea('spec', '', "rows='8' class='text-1'");?><br /><?php echo $lang->story->specTemplate;?></td>
      </tr>  
      <tr>
        <th class='rowhead'><nobr><?php echo $lang->story->keywords;?></nobr></th>
        <td><?php echo html::input('keywords', '', 'class="text-1"');?></td>
      </tr>
      <tr>
        <th class='rowhead'><nobr><?php echo $lang->story->mailto;?></nobr></th>
        <td><?php echo html::input('mailto', '', 'class="text-1"');?></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->story->legendAttatch;?></th>
        <td><?php echo $this->fetch('file', 'buildform');?></td>
      </tr>  
      <tr><td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td></tr>
    </table>
  </form>
</div>  
<?php include '../../common/view/footer.html.php';?>
