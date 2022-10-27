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
<?php js::set('unmodifiableProducts', $unmodifiableProducts);?>
<?php js::set('unmodifiableBranches', $unmodifiableBranches);?>
<?php js::set('unmodifiableMainBranches', $unmodifiableMainBranches);?>
<?php js::set('allProducts', $currentProducts);?>
<?php js::set('branchGroups', $branchGroups);?>
<?php js::set('BRANCH_MAIN', BRANCH_MAIN);?>
<?php js::set('unLinkProductTip', $lang->project->unLinkProductTip);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->project->manageProducts;?></span></span>
  </div>

  <div class='btn-toolbar pull-right'>
    <?php echo html::a(inlink('manageOtherProducts'), "<i class='icon icon-link'></i> " . $lang->project->manageOtherProducts, '', "class='btn btn-primary' data-toggle='modal'");?>

    <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#otherProductsModal'><i class='icon icon-link'></i> <?php echo $lang->project->manageOtherProducts; ?></button>
  </div>
</div>
<div id='mainContent'>
  <div class='cell'>
    <form class='main-form form-ajax' method='post' id='productsBox' enctype='multipart/form-data'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->project->linkedProducts;?></div>
        <div class='detail-content row'>
          <?php $i = 0;?>
          <?php foreach($linkedProducts as $productID => $linkedProduct):?>
          <?php foreach($linkedBranches[$productID] as $branchID):?>
          <div class='col-sm-4'>
            <div class='product checked <?php echo isset($allBranches[$productID]) ? ' has-branch' : ''?>'>
              <div class="checkbox-primary" title='<?php echo $linkedProduct->name;?>'>
                <?php echo "<input type='checkbox' name='products[$i]' value='$productID' checked id='products{$productID}'>";?>
                <label class='text-ellipsis checkbox-inline' for='<?php echo 'products' . $productID;?>' title='<?php echo $linkedProduct->name;?>'><?php echo $linkedProduct->name;?></label>
              </div>
              <?php if(isset($allBranches[$productID][$branchID])) echo html::select("branch[$i]", $allBranches[$productID], $branchID, "class='form-control chosen' data-drop_direction='down' disabled='disabled'");?>
            </div>
          </div>
          <?php echo html::hidden("branch[$i]", $branchID);?>
          <?php if(!isset($branchGroups[$productID])) unset($currentProducts[$productID]);?>
          <?php if(isset($branchGroups[$productID][$branchID])) unset($branchGroups[$productID][$branchID]);?>
          <?php if(isset($branchGroups[$productID]) and empty($branchGroups[$productID])) unset($currentProducts[$productID]);?>
          <?php $i++;?>
          <?php endforeach;?>
          <?php endforeach;?>
        </div>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->project->unlinkedProducts;?></div>
        <div class='detail-content row'>
          <?php foreach($currentProducts as $productID => $productName):?>
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
      <div class='modal fade' id='otherProductsModal'>
        <div class='modal-content'>
          <div class='modal-dialog w-600px'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span><span class='sr-only'><?php echo $this->lang->close;?></span></button>
              <h4 class='modal-title'><?php echo $lang->project->manageOtherProducts;?></h4>
            </div>
            <div class='modal-body'>
              <table class='table table-form'>
                <tr>
                  <th><?php echo $lang->project->selectProduct;?></th>
                  <td><?php echo html::select('otherProducts[]', $otherProducts, '', "class='form-control chosen' multiple");?></td>
                </tr>
                <tr>
                  <th></th>
                  <td>
                    <?php echo html::commonButton($lang->save, '', 'btn btn-primary btn-wide saveOtherProduct');?>
                    <?php echo html::commonButton($lang->cancel, '', 'btn btn-wide cancelOtherProduct');?>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
