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
<div>
  <div id='titlebar'>
    <div class='heading'>
      <?php echo html::icon($lang->icons['product']);?> <?php echo $lang->project->manageProducts;?>
    </div>
  </div>
  <form id='productsBox' class='form-condensed' method='post'>
    <fieldset>
      <legend><?php echo $lang->project->linkedProducts;?></legend>
      <div class='row'>
        <?php foreach($allProducts as $productID => $productName):?>
        <?php if(isset($linkedProducts[$productID])):?>
        <?php $checked = 'checked';?>
        <div class='product col-sm-4 <?php echo $checked . (isset($branchGroups[$productID]) ? ' has-branch' : '')?>'>
          <label class='text-ellipsis checkbox-inline' for='<?php echo 'products'. $productID?>';>
            <?php echo "<input type='checkbox' name='products[$productID]' value='$productID' $checked id='products{$productID}'> $productName";?>
          </label>
          <?php
          if(isset($branchGroups[$productID]))
          {
              echo html::select("branch[$productID]", $branchGroups[$productID], $linkedProducts[$productID]->branch, "class='form-control chosen'");
          }
          ?>
        </div>
        <?php unset($allProducts[$productID]);?>
        <?php endif;?>
        <?php endforeach;?>
      </div>
    </fieldset>
    <fieldset>
      <legend><?php echo $lang->project->unlinkedProducts;?></legend>
      <div class='row'>
        <?php foreach($allProducts as $productID => $productName):?>
        <div class='col-sm-4 product<?php echo isset($branchGroups[$productID]) ? ' has-branch' : ''?>'>
          <label class='text-ellipsis checkbox-inline' for='<?php echo 'products'. $productID?>';>
            <?php echo "<input type='checkbox' name='products[$productID]' value='$productID' id='products{$productID}'> $productName";?>
          </label>
          <?php
          if(isset($branchGroups[$productID]))
          {
              echo html::select("branch[$productID]", $branchGroups[$productID], '', "class='form-control chosen'");
          }
          ?>
        </div>
        <?php endforeach;?>
      </div>
    </fieldset>
    <div class="text-center">
      <?php echo html::hidden("post", 'post');?>
      <?php echo html::submitButton();?>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
