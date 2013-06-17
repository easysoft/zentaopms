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
<table class='cont-lt5'>
  <tr valign='top'>
    <td class='side'>
      <form method='post' target='hiddenwin' action='<?php echo $this->createLink('tree', 'updateOrder', "root={$root->id}&viewType=task");?>'>
        <table class='table-1'>
          <caption><?php echo $title;?></caption>
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
    </td>
    <td class='divider'></td>
    <td>
      <form method='post' target='hiddenwin' action='<?php echo $this->createLink('tree', 'manageChild', "root={$root->id}&viewType=task");?>'>
        <table align='center' class='table-1'>
          <?php $manageChild = 'manageTaskChild';?>
          <caption><?php echo $lang->tree->$manageChild;?></caption>
          <tr>
            <td width='10%'>
              <nobr>
              <?php
              echo html::a($this->createLink('tree', 'browsetask', "root={$root->id}&productID=$productID&viewType=task"), $root->name);
              echo $lang->arrow;
              foreach($parentModules as $module)
              {
                  echo html::a($this->createLink('tree', 'browsetask', "root={$root->id}&productID=$productID&moduleID=$module->id"), $module->name);
                  echo $lang->arrow;
              }
              ?>
              </nobr>
            </td>
            <td id='moduleBox'> 
              <?php
              if($allProject and $syncProject)
              {
                  echo html::select('allProject', $allProject, '', 'onchange=syncProject(this)');
                  echo html::select('projectModule', $projectModules, '');
                  echo html::commonButton($lang->tree->syncFromProject, "id='copyModule' onclick='syncModule($currentProject, \"task\")'");
              }
              echo '<br />';
              $maxOrder = 0;
              if(!$syncProject and !$productID)
              {
                  foreach($products as $id => $product)
                  {
                      echo '<span>' . html::input("products[id$id]", $product, 'class=text-3 style="margin-bottom:5px" disabled="true"') . '<br /></span>';
                  }
              }
              else
              {
                  echo '<div id="sonModule">';
                  foreach($sons as $sonModule)
                  {
                      if($sonModule->order > $maxOrder) $maxOrder = $sonModule->order;
                      $disabled = $sonModule->type == 'task' ? '' : 'disabled="true"';
                      echo '<span>' . html::input("modules[id$sonModule->id]", $sonModule->name, 'class=text-3 style="margin-bottom:5px" ' . $disabled) . '<br /></span>';
                  }
                  for($i = 0; $i < TREE::NEW_CHILD_COUNT ; $i ++) echo '<span>' . html::input("modules[]", '', 'class=text-3 style="margin-bottom:5px"') . '<br /></span>';
              }
              ?>
              </div>
            </td>
          </tr>
          <tr>
            <td></td>
            <td colspan='2'>
              <?php 
              if($productID)
              {
                  echo html::submitButton() . html::backButton();
                  echo html::hidden('parentModuleID', $currentModuleID);
                  echo html::hidden('maxOrder', $maxOrder);
              }
              ?>      
              <input type='hidden' value='<?php echo $currentModuleID;?>' name='parentModuleID' />
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
