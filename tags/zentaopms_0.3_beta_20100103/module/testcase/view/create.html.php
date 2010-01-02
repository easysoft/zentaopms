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
<style>#produc{width:245px} #story {width:90%}</style>
<script language='Javascript'>
/* 加载产品对应的模块和需求。*/
function loadAll(productID)
{
    loadModuleMenu(productID);
    loadStory(productID);
}

/* 加载模块。*/
function loadModuleMenu(productID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case');
    $('#moduleIdBox').load(link);
}

/* 加载需求列表。*/
function loadStory(productID)
{
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID);
    $('#storyIdBox').load(link);
}

</script>
<div class='yui-d0'>
  <form method='post' target='hiddenwin'>
    <table class='table-1'> 
      <caption><?php echo $lang->testcase->create;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->labProductAndModule;?></th>
        <td class='a-left'>
          <?php echo html::select('product', $products, $productID, "onchange=loadAll(this.value); class='select-2'");?>
          <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $currentModuleID);?></span>
        </td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->labStory;?></th>
        <td class='a-left'><span id='storyIdBox'><?php echo html::select('story', $stories, '', 'class=select-3');?></span></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->title;?></th>
        <td class='a-left'><input type='text' name='title' class='text-1' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->steps;?></th>
        <td class='a-left'><textarea name='steps' class='area-1' rows='8'></textarea></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->labTypeAndPri;?></th>
        <td class='a-left'>
          <?php echo html::select('type', (array)$lang->testcase->typeList, '', 'class=select-2');?>
          <?php echo html::select('pri', (array)$lang->testcase->priList, '', 'class=select-2');?>
        </td>
      </tr>  
      <tr>
        <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?> </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/footer.html.php';?>
