<?php
/**
 * The manage product view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: manageproducts.html.php 4129 2013-01-18 01:58:14Z wwccss $
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
    <form id='productsBox' method='post'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->project->linkedProducts;?></div>
        <div class='detail-content row'>
          <?php foreach($allProducts as $productID => $productName):?>
          <?php if(isset($linkedProducts[$productID])):?>
          <?php $checked = 'checked';?>
          <div class='col-sm-4'>
            <div class='product <?php echo $checked . (isset($branchGroups[$productID]) ? ' has-branch' : '')?>'>
              <div class="checkbox-primary" title='<?php echo $productName;?>'>
                <?php echo "<input type='checkbox' name='products[$productID]' value='$productID' $checked id='products{$productID}'>";?>
                <label class='text-ellipsis checkbox-inline' for='<?php echo 'products' . $productID;?>' title='<?php echo $productName;?>'><?php echo $productName;?></label>
              </div>
              <?php if(isset($branchGroups[$productID])) echo html::select("branch[$productID]", $branchGroups[$productID], $linkedProducts[$productID]->branch, "class='form-control chosen'");?>
            </div>
          </div>
          <?php unset($allProducts[$productID]);?>
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
                <?php echo "<input type='checkbox' name='products[$productID]' value='$productID' id='products{$productID}'>";?>
                <label class='text-ellipsis checkbox-inline' for='<?php echo 'products' . $productID;?>'><?php echo $productName;?></label>
              </div>
              <?php if(isset($branchGroups[$productID])) echo html::select("branch[$productID]", $branchGroups[$productID], '', "class='form-control chosen'");?>
            </div>
          </div>
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
