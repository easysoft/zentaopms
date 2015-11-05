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
  <form class='form-condensed' method='post'>
    <div id='productsBox' class='row'>
      <?php foreach($allProducts as $productID => $productName):?>
      <?php $checked = isset($linkedProducts[$productID]) ? 'checked' : ''; ?>
      <div class='col-sm-4 <?php echo $checked?>'>
        <?php if(isset($branchGroups[$productID])) echo "<div class='col-sm-6' style='padding-left:0px'>"?>
        <label for='<?php echo 'products'. $productID?>';>
          <?php echo "<input type='checkbox' name='products[$productID]' value='$productID' $checked id='products{$productID}'> $productName";?>
        </label>
        <?php
        if(isset($branchGroups[$productID]))
        {
            echo "</div><div class='col-sm-6'>";
            echo html::select("branch[$productID]", $branchGroups[$productID], $checked ? $linkedProducts[$productID]->branch : '', "class='from-control chosen'");
            echo '</div>';
        }
        ?>
      </div>
      <?php endforeach;?>
      <?php echo html::hidden("post", 'post');?>
    </div>
    <div class="text-center">
      <?php echo html::submitButton();?>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
