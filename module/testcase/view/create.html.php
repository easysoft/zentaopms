<?php
/**
 * The create view of case module of ZenTaoMS.
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
 * @package     case
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<script language='Javascript'>
function loadModuleMenu(productID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case');
    $('#moduleIdBox').load(link);
}

</script>
<div class='yui-doc3'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-1'> 
      <caption><?php echo $lang->case->create;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->case->labProductAndModule;?></th>
        <td class='a-left'>
          <?php echo html::select('productID', $products, $productID, "onchange=loadModuleMenu(this.value); class='select-2'");?>
          <span id='moduleIdBox'><?php echo html::select('moduleID', $moduleOptionMenu, $currentModuleID, 'class=select-3');?></span>
        </td>
      </tr>  
      <!--
      <tr>
        <th class='rowhead'><?php echo $lang->case->labStory;?></th>
        <td class='a-left'>
        </td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->case->labProjectAndTask;?></th>
        <td class='a-left'>
        </td>
      </tr>-->
      <tr>
        <th class='rowhead'><?php echo $lang->case->labTypeAndPri;?></th>
        <td class='a-left'>
          <?php echo html::select('type', (array)$lang->case->typeList, '', 'class=select-2');?>
          <?php echo html::select('pri', (array)$lang->case->priList, '', 'class=select-2');?>
        </td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->case->title;?></th>
        <td class='a-left'><input type='text' name='title' class='text-1' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->case->steps;?></th>
        <td class='a-left'><textarea name='steps' class='area-1' rows='8'></textarea></td>
      </tr>  
      <tr>
        <td colspan='2'>
          <input type='submit' class='button-s' />
          <input type='reset'  class='button-r' />
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/footer.html.php';?>
