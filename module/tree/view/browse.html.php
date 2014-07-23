<?php
/**
 * The browse view file of tree module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: browse.html.php 4796 2013-06-06 02:21:59Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<div id='titlebar'>
  <div class='heading'><i class='icon-cogs'></i> <?php echo $lang->tree->common;?>  </div>
</div>
<div class='row'>
  <div class='col-sm-6 col-md-4 col-lg-3'>
    <form class='form-condensed' method='post' target='hiddenwin' action='<?php echo $this->createLink('tree', 'updateOrder', "root={$root->id}&viewType=$viewType");?>'>
      <div class='panel'>
        <div class='panel-heading'>
          <i class='icon-cog'></i> <strong><?php echo $title;?></strong>
        </div>
        <div class='panel-body'>
          <div id='main'><?php echo $modules;?></div>
          <div class='text-center'>
            <?php if(common::hasPriv('tree', 'updateorder')) echo html::submitButton($lang->tree->updateOrder);?>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class='col-sm-6 col-md-8 col-lg-9'>
    <form class='form-condensed' method='post' target='hiddenwin' action='<?php echo $this->createLink('tree', 'manageChild', "root={$root->id}&viewType=$viewType");?>'>
      <div class='panel'>
        <div class='panel-heading'>
          <i class='icon-sitemap'></i> 
          <?php $manageChild = 'manage' . ucfirst($viewType) . 'Child';?>
          <?php echo strpos($viewType, 'doc') !== false ? $lang->doc->manageType : $lang->tree->$manageChild;?>
        </div>
        <div class='panel-body'>
          <table class='table table-form'>
            <tr>
              <td class='parentModule'>
                <nobr>
                <?php
                echo html::a($this->createLink('tree', 'browse', "root={$root->id}&viewType=$viewType"), $root->name);
                echo $lang->arrow;
                foreach($parentModules as $module)
                {
                    echo html::a($this->createLink('tree', 'browse', "root={$root->id}&viewType=$viewType&moduleID=$module->id"), $module->name);
                    echo $lang->arrow;
                }
                ?>
                </nobr>
              </td>
              <td id='moduleBox'> 
                <?php
                if($viewType == 'story')
                {
                    if($allProduct)
                    {
                        echo "<table class='copy w-p100'><tr>";
                        echo "<td class='w-260px'>" . html::select('allProduct', $allProduct, '', "class='form-control chosen' onchange=\"syncProductOrProject(this,'product')\"") . '</td>';
                        echo "<td class='w-200px'>" . html::select('productModule', $productModules, '', "class='form-control chosen'") . '</td>';
                        echo "<td class=''>" . html::commonButton($lang->tree->syncFromProduct, "id='copyModule' onclick='syncModule($currentProduct, \"story\")'") . '</td>';
                        echo '</tr></table>';
                    }
                }
                $maxOrder = 0;
                echo '<div id="sonModule">';
                foreach($sons as $sonModule)
                {
                    if($sonModule->order > $maxOrder) $maxOrder = $sonModule->order;
                    $disabled = $sonModule->type == $viewType ? '' : 'disabled="true"';
                    echo '<span>' . html::input("modules[id$sonModule->id]", $sonModule->name, 'class=form-control style="margin-bottom:5px" ' . $disabled) . '</span>';
                }
                for($i = 0; $i < TREE::NEW_CHILD_COUNT ; $i ++) echo '<span>' . html::input("modules[]", '', 'class=form-control style="margin-bottom:5px"') . '</span>';
                ?>
                </div>
              </td>
            </tr>
            <tr>
              <td></td>
              <td colspan='2'>
                <?php 
                echo html::submitButton() . html::backButton();
                echo html::hidden('parentModuleID', $currentModuleID);
                echo html::hidden('maxOrder', $maxOrder);
                ?>      
                <input type='hidden' value='<?php echo $currentModuleID;?>' name='parentModuleID' />
              </td>
            </tr>
          </table>
        </div>
      </div>
    </form>
  </div>
</div>
<?php 
if(strpos($viewType, 'doc') !== false) 
{
    include '../../doc/view/footer.html.php';
}
else
{
    include '../../common/view/footer.html.php';
}
?>
