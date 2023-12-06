<?php
/**
 * The manage prjmanageproducts view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if(isonlybody()):?>
<style> .body-modal #mainMenu.clearfix > .btn-toolbar {width: unset;}</style>
<?php endif;?>
<?php js::set('unmodifiableProducts', $unmodifiableProducts);?>
<?php js::set('unmodifiableBranches', $unmodifiableBranches);?>
<?php js::set('unmodifiableMainBranches', $unmodifiableMainBranches);?>
<?php js::set('allProducts', $allProducts);?>
<?php js::set('branchGroups', $branchGroups);?>
<?php js::set('BRANCH_MAIN', BRANCH_MAIN);?>
<?php js::set('unLinkProductTip', $lang->project->unLinkProductTip);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->project->manageProducts;?></span></span>
  </div>

  <?php if($this->config->systemMode == 'ALM'):?>
  <div class='btn-toolbar pull-right'>
    <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#otherProductsModal'><i class='icon icon-link'></i> <?php echo $lang->project->manageOtherProducts; ?></button>
  </div>
  <?php endif;?>
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
          <?php $cannotUnlink = (in_array($productID, $unmodifiableProducts) and ($project->model == 'waterfall'));?>
          <?php $disabled = $cannotUnlink ? "disabled='disabled'" : '';?>
          <div class='col-sm-4'>
            <div class='product checked <?php echo isset($allBranches[$productID]) ? ' has-branch' : ''?>'>
              <div class="checkbox-primary" title='<?php echo $linkedProduct->name;?>'>
                <?php echo "<input type='checkbox' name='products[$i]' value='$productID' checked $disabled id='products{$productID}'>";?>
                <label class='text-ellipsis checkbox-inline' for='<?php echo 'products' . $productID;?>' title='<?php echo $linkedProduct->name;?>'><?php echo $linkedProduct->name;?></label>
              </div>
              <?php if(isset($allBranches[$productID][$branchID])) echo html::select("branch[$i]", $allBranches[$productID], $branchID, "class='form-control chosen' data-drop_direction='down' disabled='disabled'");?>
            </div>
          </div>
          <?php if($cannotUnlink) echo html::hidden("products[$i]", $productID);?>
          <?php echo html::hidden("branch[$i]", $branchID);?>

          <?php
          if(!isset($branchGroups[$productID]))
          {
              if($this->config->systemMode == 'ALM')
              {
                  unset($currentProducts[$productID]);
              }
              else
              {
                  unset($allProducts[$productID]);
              }
          }

          if(isset($branchGroups[$productID][$branchID])) unset($branchGroups[$productID][$branchID]);

          if(isset($branchGroups[$productID]) and empty($branchGroups[$productID]))
          {
              if($this->config->systemMode == 'ALM')
              {
                  unset($currentProducts[$productID]);
              }
              else
              {
                  unset($allProducts[$productID]);
              }
          }
          ?>
          <?php $i++;?>
          <?php endforeach;?>
          <?php endforeach;?>
        </div>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->project->unlinkedProducts;?></div>
        <div class='detail-content row'>
          <?php
          $unlinkedProducts = $this->config->systemMode == 'ALM' ? $currentProducts : $allProducts;
          foreach($unlinkedProducts as $productID => $productName):
          ?>
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

      <?php if($this->config->systemMode == 'ALM'):?>
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
                  <td><?php echo html::commonButton($lang->save, '', 'btn btn-primary btn-wide saveOtherProduct');?></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
      <?php endif;?>
    </form>
  </div>
</div>

<?php $noticeSwitch = ($project->stageBy == 'product' and count($linkedProducts) == 1 and empty($executions));?>
<?php js::set('linkedProducts', array_keys($linkedProducts));?>
<?php js::set('noticeSwitch', $noticeSwitch);?>
<?php js::set('noticeDivsion', $lang->project->noticeDivsion);?>
<?php js::set('stageBySwitchList', $lang->project->stageBySwitchList);?>
<?php include '../../common/view/footer.html.php';?>
