<?php
/**
 * The html productlist file of productlist method of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <div class="title">
      <?php echo empty($program) ? $lang->program->PGMCommon : $program->name;?>
      <?php if($programID) echo html::a(inLink('all', 'programID=0'), "<i class='icon icon-sm icon-close'></i>", '', 'class="text-muted"');?>
    </div>
  </div>
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->product->featureBar['all'] as $key => $label):?>
    <?php echo html::a(inlink("all", "programID=$programID&browseType=$key&orderBy=$orderBy"), "<span class='text'>{$label}</span>", '', "class='btn btn-link' id='{$key}Tab'");?>
    <?php endforeach;?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('product', 'create', "programID=$programID", '<i class="icon icon-plus"></i>' . $lang->product->create, '', 'class="btn btn-primary"');?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div id="sidebar" class="side-col">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php echo $programTree;?>
    </div>
  </div>
  <div class="main-col">
    <form class="main-table table-product" data-ride="table" id="productListForm" method="post" action='<?php echo inLink('batchEdit', "programID=$programID");?>'>
      <?php $canOrder = common::hasPriv('product', 'updataOrder');?>
      <?php $canBatchEdit = common::hasPriv('product', 'batchEdit');?>
      <table id="productList" class="table has-sort-head table-fixed">
        <?php $vars = "programID=$programID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <thead>
          <tr>
            <th class='c-id'>
              <?php if($canBatchEdit):?>
                <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll;?>">
                  <label></label>
                </div>
              <?php endif;?>
              <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
            </th>
            <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->product->name);?></th>
            <th class='w-110px text-left'><?php common::printOrderLink('line', $orderBy, $vars, $lang->product->line);?></th>
            <th class='w-80px' title='<?php echo $lang->product->activeStoriesTitle;?>'><?php echo $lang->product->activeStories;?></th>
            <th class='w-90px' title='<?php echo $lang->product->changedStoriesTitle;?>'><?php echo $lang->product->changedStories;?></th>
            <th class='w-70px' title='<?php echo $lang->product->draftStoriesTitle;?>'><?php echo $lang->product->draftStories;?></th>
            <th class='w-90px' title='<?php echo $lang->product->closedStoriesTitle;?>'><?php echo $lang->product->closedStories;?></th>
            <th class='w-70px' title='<?php echo $lang->product->plans;?>'><?php echo $lang->product->plans;?></th>
            <th class='w-70px' title='<?php echo $lang->product->releases;?>'><?php echo $lang->product->releases;?></th>
            <th class='w-80px' title='<?php echo $lang->product->unResolvedBugsTitle;?>'><?php echo $lang->product->unResolvedBugs;?></th>
            <th class='w-110px' title='<?php echo $lang->product->assignToNullBugsTitle;?>'><?php echo $lang->product->assignToNullBugs;?></th>
            <?php if($canOrder):?>
            <th class='w-70px sort-default'><?php common::printOrderLink('order', $orderBy, $vars, $lang->product->updateOrder);?></th>
            <?php endif;?>
          </tr>
        </thead>
        <tbody class="sortable" id="productTableList">
        <?php foreach($productStats as $product):?>
        <tr data-id='<?php echo $product->id ?>' data-order='<?php echo $product->code;?>'>
          <td class='c-id'>
            <?php if($canBatchEdit):?>
            <?php echo html::checkbox('productIDList', array($product->id => sprintf('%03d', $product->id)));?>
            <?php else:?>
            <?php printf('%03d', $product->id);?>
            <?php endif;?>
          </td>
          <td class="c-name" title='<?php echo $product->name?>'><?php echo html::a($this->createLink('product', 'browse', 'product=' . $product->id), $product->name);?></td>
          <td title='<?php echo zget($lines, $product->line, '')?>'><?php echo zget($lines, $product->line, '');?></td>
          <td class='text-center'><?php echo $product->stories['active'];?></td>
          <td class='text-center'><?php echo $product->stories['changed'];?></td>
          <td class='text-center'><?php echo $product->stories['draft'];?></td>
          <td class='text-center'><?php echo $product->stories['closed'];?></td>
          <td class='text-center'><?php echo $product->plans;?></td>
          <td class='text-center'><?php echo $product->releases;?></td>
          <td class='text-center'><?php echo $product->unResolved;?></td>
          <td class='text-center'><?php echo $product->assignToNull;?></td>
          <?php if($canOrder):?>
          <td class='c-actions sort-handler'><i class="icon icon-move"></i></td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <?php if($productStats):?>
      <div class="table-footer">
        <?php if($canBatchEdit):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class="table-actions btn-toolbar">
          <?php echo html::submitButton($lang->edit, '', 'btn');?>
        </div>
        <?php endif;?>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
  </div>
</div>
<?php js::set('orderBy', $orderBy)?>
<?php js::set('programID', $programID)?>
<?php js::set('browseType', $browseType)?>
<?php include '../../common/view/footer.html.php';?>
