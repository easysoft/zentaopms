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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<script language='Javascript'>
function loadModuleMenu(productID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=product');
    $('#moduleIdBox').load(link);
}
</script>
<div id='doc3'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-1'> 
      <caption><?php echo $lang->story->create;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->story->product;?></th>
        <td class='a-left'>
          <?php echo html::select('product', $products, $product->id, "onchange=loadModuleMenu(this.value); class='select-3'");?>
          <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID);?></span>
        </td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->story->plan;?></th>
        <td class='a-left'>
          <?php echo html::select('plan', $plans, '', 'class=select-3');?>
        </td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->story->pri;?></th>
        <td class='a-left'>
          <?php echo html::select('pri', (array)$lang->story->priList, '', 'class=select-3');?>
        </td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->story->assignedTo;?></th>
        <td class='a-left'>
          <?php echo html::select('assignedTo', $users, $app->user->account, 'class=select-3');?>
        </td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->story->estimate;?></th>
        <td class='a-left'><input type='text' name='estimate' id='estimate' class='text-3' /></td>
      </tr> 
      <tr>
        <th class='rowhead'><?php echo $lang->story->status;?></th>
        <td class='a-left'>
          <?php echo html::select('status', (array)$lang->story->statusList, '', 'class=select-3');?>
        </td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->story->title;?></th>
        <td class='a-left'><input type='text' name='title' class='text-1' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->story->spec;?></th>
        <td class='a-left'><textarea name='spec' rows='8' class='text-1'></textarea></td>
      </tr>  
      <tr>
        <td colspan='2'>
          <input type='submit' value='<?php echo $lang->save;?>'  class='button-s' />
          <input type='reset'  value='<?php echo $lang->reset;?>' class='button-r' />
        </td>
      </tr>
    </table>
  </form>
</div>  
<?php include '../../common/footer.html.php';?>
