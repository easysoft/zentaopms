<?php
/**
 * The manage prjmanageproducts view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->project->manageProducts;?></span></span>
  </div>
</div>
<div id='mainContent'>
  <div class='cell'>
    <form class='main-form form-ajax' method='post' id='productsBox' enctype='multipart/form-data'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->project->linkedProducts;?></div>
        <div class='detail-content row'>
          <?php $i = 0;?>
          <?php foreach($allProducts as $productID => $productName):?>
          <?php if(isset($linkedProducts[$productID])):?>
          <?php foreach($linkedBranches[$productID] as $branchID):?>
          <?php $isDisabled = '';?>
          <?php if($branchID == BRANCH_MAIN and isset($unmodifiableMainBranches[$productID])) $isDisabled = "disabled='disabled'";?>
          <?php if($branchID != BRANCH_MAIN and in_array($productID, $unmodifiableProducts) and in_array($branchID, $unmodifiableBranches)) $isDisabled = "disabled='disabled'";?>
          <?php $title      = (in_array($productID, $unmodifiableProducts) and in_array($branchID, $unmodifiableBranches)) ? $lang->project->notAllowRemoveProducts : $productName;?>
          <?php $checked    = 'checked';?>
          <div class='col-sm-4'>
            <div class='product <?php echo $checked . (isset($allBranches[$productID]) ? ' has-branch' : '')?>'>
              <div class="checkbox-primary" title='<?php echo $title;?>'>
                <?php echo "<input type='checkbox' name='products[$i]' value='$productID' $checked id='products{$productID}' $isDisabled>";?>
                <label class='text-ellipsis checkbox-inline' for='<?php echo 'products' . $productID;?>' title='<?php echo $productName;?>'><?php echo $productName;?></label>
              </div>
              <?php if(isset($allBranches[$productID][$branchID])) echo html::select("branch[$i]", $allBranches[$productID], $branchID, "class='form-control chosen' data-drop_direction='down' disabled='disabled'");?>
            </div>
          </div>
          <?php if(!empty($isDisabled)) echo html::hidden("products[$i]", $productID);?>
          <?php echo html::hidden("branch[$i]", $branchID);?>
          <?php if(!isset($branchGroups[$productID])) unset($allProducts[$productID]);?>
          <?php if(isset($branchGroups[$productID][$branchID])) unset($branchGroups[$productID][$branchID]);?>
          <?php if(isset($branchGroups[$productID]) and empty($branchGroups[$productID])) unset($allProducts[$productID]);?>
          <?php $i++;?>
          <?php endforeach;?>
          <?php endif;?>
          <?php endforeach;?>
        </div>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->project->unlinkedProducts;?></div>
        <div class='detail-content row'>
          <?php foreach($allProducts as $productID => $productName):?>
          <div class='col-sm-4'>
            <div class='product<?php echo isset($branchGroups[$productID]) ? ' has-branch' : ''?>'>
              <div class="checkbox-primary" title='<?php echo $productName;?>'>
                <?php echo "<input type='checkbox' name='products[$i]' value='$productID' id='products{$productID}'>";?>
                <label class='text-ellipsis checkbox-inline' for='<?php echo 'products' . $productID;?>'><?php echo $productName;?></label>
              </div>
              <?php if(isset($branchGroups[$productID])) echo html::select("branch[$i]", $branchGroups[$productID], '', "class='form-control chosen' data-drop_direction='down'");?>
            </div>
          </div>
          <?php $i++;?>
          <?php endforeach;?>
        </div>
      </div>
      <div class="detail text-center form-actions">
        <?php echo html::hidden("post", 'post');?>
        <?php echo html::submitButton();?>
      </div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
