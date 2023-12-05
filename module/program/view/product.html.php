<?php
/**
 * The html product list file of product method of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('checkedProducts', $lang->product->checkedProducts);?>
<?php js::set('cilentLang', $this->app->getClientLang());?>
<?php $canBatchEdit = common::hasPriv('product', 'batchEdit');?>
<div id="mainMenu" class="clearfix">
  <?php if(!isonlybody()):?>
  <div class="btn-toolBar pull-left">
    <?php foreach($lang->program->featureBar['product'] as $key => $label):?>
    <?php $active = $key == $browseType ? 'btn-active-text' : '';?>
    <?php if($key == $browseType) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink("product", "programID=$programID&browseType=$key&orderBy=$orderBy"), "<span class='text'>{$label}</span>", '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php if($canBatchEdit) echo html::checkbox('showEdit', array('1' => $lang->product->edit), $showBatchEdit);?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('product', 'create', "programID=$programID", '<i class="icon icon-plus"></i> ' . $lang->product->create, '', 'class="btn btn-primary"');?>
  </div>
  <?php endif;?>
</div>
<div id="mainContent" class="main-row fade">
  <?php if(empty($products)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->product->noProduct;?></span></p>
  </div>
  <?php else:?>
  <div class="main-col">
    <form class="main-table table-product" data-ride="table" id="productListForm" method="post" action='<?php echo $this->createLink('product', 'batchEdit', "programID=$programID");?>'>
      <?php $canOrder = common::hasPriv('product', 'updateOrder');?>
      <table id="productList" class="table has-sort-head table-bordered table-fixed">
        <?php $vars = "programID=$programID&browseType=$browseType&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <thead>
          <tr class="text-center">
            <th class='c-id text-left' rowspan='2'>
              <?php if($canBatchEdit):?>
                <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll;?>">
                  <label></label>
                </div>
              <?php endif;?>
              <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
            </th>
            <th rowspan="2"><?php common::printOrderLink('name', $orderBy, $vars, $lang->product->name);?></th>
            <th class='c-PO' rowspan="2"><?php common::printOrderLink('PO', $orderBy, $vars, $lang->product->manager);?></th>
            <th class='c-story' colspan="5"><?php echo $lang->story->story;?></th>
            <th class='c-bug' colspan="2"><?php echo $lang->bug->common;?></th>
            <th class='c-plan' rowspan="2"><?php echo $lang->product->plan;?></th>
            <th class='c-release' rowspan="2"><?php echo $lang->product->release;?></th>
            <th class='c-actions' rowspan="2"><?php echo $lang->actions;?></th>
          </tr>
          <tr class="text-center">
            <th style="border-left: 1px solid #ddd;"><?php echo $lang->story->draft;?></th>
            <th><?php echo $lang->story->activate;?></th>
            <th><?php echo $lang->story->change;?></th>
            <th><?php echo $lang->story->statusList['reviewing'];?></th>
            <th><?php echo $lang->story->completeRate;?></th>
            <th style="border-left: 1px solid #ddd;"><?php echo $lang->bug->activate;?></th>
            <th><?php echo $lang->bug->fixedRate;?></th>
          </tr>
        </thead>
        <tbody class="sortable" id="productTableList">
        <?php foreach($products as $product):?>
          <?php $totalStories = $product->stories['finishClosed'] + $product->stories['unclosed'];?>
          <tr class="text-center" data-id='<?php echo $product->id ?>' data-order='<?php echo $product->code;?>'>
            <td class='c-id text-left'>
              <?php if($canBatchEdit):?>
              <?php echo html::checkbox('productIDList', array($product->id => sprintf('%03d', $product->id)), '', 'class="id-checkbox ' . (!$showBatchEdit ? 'hidden"' : '"'));?>
              <?php endif;?>
              <span class="product-id <?php if($canBatchEdit && $showBatchEdit) echo 'hidden';?>"><?php printf('%03d', $product->id);?></span>
            </td>
            <td class="c-name" title='<?php echo $product->name?>'><?php echo html::a($this->createLink('product', 'browse', 'product=' . $product->id), $product->name);?></td>
            <td class='c-manager'>
              <?php
              if(!empty($product->PO))
              {
                  $userName  = zget($users, $product->PO);
                  echo html::smallAvatar(array('avatar' => $usersAvatar[$product->PO], 'account' => $product->PO, 'name' => $userName), 'avatar-circle avatar-' . zget($userIdPairs, $product->PO));

                  $userID = isset($userIdPairs[$product->PO]) ? $userIdPairs[$product->PO] : '';
                  echo html::a($this->createLink('user', 'profile', "userID=$userID", '', true), $userName, '', "title='{$userName}' data-toggle='modal' data-type='iframe' data-width='600'");
              }
              ?>
            </td>
            <td><?php echo $product->stories['draft'];?></td>
            <td><?php echo $product->stories['active'];?></td>
            <td><?php echo $product->stories['changing'];?></td>
            <td><?php echo $product->stories['reviewing'];?></td>
            <td><?php echo $totalStories == 0 ? 0 : round($product->stories['finishClosed'] / $totalStories, 3) * 100;?>%</td>
            <td><?php echo $product->unResolved;?></td>
            <td><?php echo ($product->unResolved + $product->fixedBugs) == 0 ? 0 : round($product->fixedBugs / ($product->unResolved + $product->fixedBugs), 3) * 100;?>%</td>
            <td><?php echo $product->plans;?></td>
            <td><?php echo $product->releases;?></td>
            <td class='c-actions'>
              <?php common::printIcon('product', 'edit', "product=$product->id&action=edit&extra=&programID=$programID", $product, 'list', 'edit');?>
              <?php if($canOrder):?>
              <span class='c-actions sort-handler'><i class="icon icon-move"></i></span>
              <?php endif;?>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <?php if($products):?>
      <div class="table-footer">
        <?php if($canBatchEdit):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class="table-actions btn-toolbar">
          <?php
          $actionLink = $this->createLink('product', 'batchEdit');
          echo html::commonButton($lang->edit, "id='editBtn' data-form-action='$actionLink'");
          ?>
        </div>
        <?php
        $summary = sprintf($lang->product->pageSummary, count($products));
        echo "<div id='productsCount' class='table-statistic'>$summary</div>";
        ?>
        <?php endif;?>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
  </div>
  <?php endif;?>
</div>
<?php js::set('orderBy', $orderBy)?>
<?php js::set('programID', $programID)?>
<script>
$(function()
{
    $('#productTableList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i].item).attr('data-id') + ',';
        $.post(createLink('product', 'updateOrder'), {'products' : list, 'orderBy' : orderBy});
    });
});
</script>
<?php include '../../common/view/footer.html.php';?>
