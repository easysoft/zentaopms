<?php
/**
 * The html template file of all method of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <?php echo html::commonButton('<i class="icon icon-caret-left"></i>', '', 'btn btn-icon btn-sm btn-info sidebar-toggle');?>
    <div class="title">
      <?php echo $lang->product->line;?>
    </div>
  </div>
  <div class="btn-toolbar pull-left">
    <?php 
    foreach($lang->product->featureBar['all'] as $key => $label)
    {
        $label   = "<span class='text'>{$label}</span>";
        $label  .= $key == $status ? "<span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';
        $active  = $key == $status ? 'btn-active-text' : '';
        echo html::a(inlink("all", "productID={$productID}&line=&status={$key}"), $label, '', "class='btn btn-link {$active}' id='{$key}'");
    }
    ?>
  </div>
  <div class="btn-toolbar pull-right">
    <div class="btn-group">
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-export muted"></i> <span class="text"><?php echo $lang->export;?></span> <span class="caret"></span></button>
      <ul class="dropdown-menu">
        <?php
        $misc = common::hasPriv('product', 'export') ? "class='export'" : "class=disabled";
        $link = common::hasPriv('product', 'export') ? $this->createLink('product', 'export', "status=$status&orderBy=$orderBy") : '#';
        echo "<li>" . html::a($link, $lang->product->export, '', $misc) . "</li>";
        ?>
      </ul>
    </div>
    <?php echo html::a($this->createLink('product', 'create'), "<i class='icon-plus'></i> " . $lang->product->create,'', "class='btn btn-primary'") ?>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="side-col" id="sidebar">
    <div class="cell">
      <?php echo $lineTree;?>
      <div class="text-center">
        <?php common::printLink('tree', 'browse', "rootID=$productID&view=line", $lang->tree->manageLine, '', "class='btn btn-info btn-wide'");?>
        <hr class="space-sm" />
      </div>
    </div>
  </div>
  <div class="main-col">
    <div class="cell" id="queryBox"></div>
    <form class="main-table table-product" data-ride="table" method="post" id='productsForm' action='<?php echo inLink('batchEdit', "productID=$productID");?>'>
      <?php $canOrder = (common::hasPriv('product', 'updateOrder') and strpos($orderBy, 'order') !== false)?>
      <?php $canBatchEdit = common::hasPriv('product', 'batchEdit'); ?>
      <table class="table has-sort-head" id='productList'>
        <?php $vars = "productID=$productID&line=$line&status=$status&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <thead>
          <tr>
            <th class='c-id'>
              <?php if($canBatchEdit):?>
              <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                <label></label>
              </div>
              <?php endif;?>
              <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
            </th>
            <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->product->name);?></th>
            <th class='w-80px text-left'><?php common::printOrderLink('line', $orderBy, $vars, $lang->product->line);?></th>
            <th class='w-80px'><?php echo $lang->product->activeStories;?></th>
            <th class='w-80px'><?php echo $lang->product->changedStories;?></th>
            <th class='w-70px'><?php echo $lang->product->draftStories;?></th>
            <th class='w-80px'><?php echo $lang->product->closedStories;?></th>
            <th class='w-80px'><?php echo $lang->product->plans;?></th>
            <th class='w-80px'><?php echo $lang->product->releases;?></th>
            <th class='w-80px'><?php echo $lang->product->bugs;?></th>
            <th class='w-80px'><?php echo $lang->product->unResolvedBugs;?></th>
            <th class='w-80px'><?php echo $lang->product->assignToNullBugs;?></th>
            <?php if($canOrder):?>
            <th class='w-70px sort-default'><?php common::printOrderLink('order', $orderBy, $vars, $lang->product->updateOrder);?></th>
            <?php endif;?>
          </tr>
        </thead>
        <tbody>
        <?php foreach($productStats as $product):?>
        <tr data-id='<?php echo $product->id ?>' data-order='<?php echo $product->code;?>'>
          <td class='cell-id'>
            <?php if($canBatchEdit):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='productIDList[<?php echo $product->id;?>]' value='<?php echo $product->id;?>' /> 
              <label></label>
            </div>
            <?php endif;?>
            <?php echo sprintf('%03d', $product->id);?>
          </td>
          <td title='<?php echo $product->name?>'><?php echo html::a($this->createLink('product', 'view', 'product=' . $product->id), $product->name);?></td>
          <td><?php echo zget($lines, $product->line);?></td>
          <td class='text-center'><?php echo $product->stories['active'];?></td>
          <td class='text-center'><?php echo $product->stories['changed'];?></td>
          <td class='text-center'><?php echo $product->stories['draft'];?></td>
          <td class='text-center'><?php echo $product->stories['closed'];?></td>
          <td class='text-center'><?php echo $product->plans;?></td>
          <td class='text-center'><?php echo $product->releases;?></td>
          <td class='text-center'><?php echo $product->bugs;?></td>
          <td class='text-center'><?php echo $product->unResolved;?></td>
          <td class='text-center'><?php echo $product->assignToNull;?></td>
          <?php if($canOrder):?>
          <td class='sort-handler'><i class="icon icon-move"></i></td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <?php if($productStats):?>
      <div class="table-footer">
        <?php if(common::hasPriv('productplan', 'batchEdit')):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class="table-actions btn-toolbar">
          <?php echo html::submitButton($lang->edit);?>
        </div>
        <?php endif;?>
        <?php if(!$canOrder and common::hasPriv('product', 'updateOrder')) echo html::a(inlink('all', "productID=$productID&line=$line&status=$status&order=order_desc&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"), $lang->product->updateOrder, '' , "class='btn'");?>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
  </div>
</div>
<?php js::set('orderBy', $orderBy)?>
<?php include '../../common/view/footer.html.php';?>
