<?php
/**
 * The browse view file of tree module of ZenTaoMS.
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
 * @package     tree
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<script language='javascript'>
$(document).ready(function()
{
    $("a.iframe").colorbox({width:480, height:320, iframe:true, transition:'none'});
});
</script>
<div class="yui-d0 yui-t3">
  <div class="yui-main">
    <div class="yui-b">
    <form method='post' target='hiddenwin' action='<?php echo $this->createLink('tree', 'manageChild', "product={$product->id}&viewType=$viewType");?>'>
      <table align='center' class='table-1'>
        <caption><?php echo $lang->tree->manageChild;?></caption>
        <tr>
          <td width='10%'>
            <nobr>
            <?php
            echo html::a($this->createLink('tree', 'browse', "product={$product->id}&viewType=$viewType"), $product->name);
            echo $lang->arrow;
            foreach($parentModules as $module)
            {
                echo html::a($this->createLink('tree', 'browse', "product={$product->id}&viewType=$viewType&moduleID=$module->id"), $module->name);
                echo $lang->arrow;
            }
            ?>
            </nobr>
          </td>
          <td> 
            <?php
            $maxOrder = 0;
            foreach($sons as $sonModule)
            {
                if($sonModule->order > $maxOrder) $maxOrder = $sonModule->order;
                echo html::input("modules[id$sonModule->id]", $sonModule->name, 'style="margin-bottom:5px"') . '<br />';
            }
            for($i = 0; $i < TREE::NEW_CHILD_COUNT ; $i ++) echo html::input("modules[]", '', 'style="margin-bottom:5px"') . '<br />';
           ?>
          </td>
        </tr>
        <tr>
          <td class='a-center' colspan='2'>
            <?php 
            echo html::submitButton() . html::resetButton();
            echo html::hidden('parentModuleID', $currentModuleID);
            echo html::hidden('maxOrder', $maxOrder);
            ?>      
            <input type='hidden' value='<?php echo $currentModuleID;?>' name='parentModuleID' />
          </td>
        </tr>
      </table>
      </form>
    </div>
  </div>

  <div class="yui-b">
    <form method='post' target='hiddenwin' action='<?php echo $this->createLink('tree', 'updateOrder', "product={$product->id}&viewType=$viewType");?>'>
    <table class='table-1'>
      <caption><?php echo $header->title;?></caption>
      <tr>
        <td>
          <div id='main'><?php echo $modules;?></div>
          <div class='a-center'>
            <?php if(common::hasPriv('tree', 'updateorder')) echo html::submitButton($lang->tree->updateOrder);?>
          </div>
        </td>
      </tr>
    </table>
    </form>
  </div>

</div>  
<?php include '../../common/view/footer.html.php';?>
