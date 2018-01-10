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
<?php include '../../common/view/sortable.html.php';?>
<div id='featurebar'>
  <div class='actions'>
    <?php 
    $link = $this->createLink('product', 'export', "status=$status&orderBy=$orderBy");
    if(common::hasPriv('product', 'export')) echo html::a($link, "<i class='icon-download-alt icon'></i> " . $lang->export, '', "class='export btn'");
    ?>
    <?php echo html::a($this->createLink('product', 'create'), "<i class='icon-plus'></i> " . $lang->product->create,'', "class='btn'") ?>
  </div>
  <ul class='nav'>
    <?php echo "<li id='noclosedTab'>" . html::a(inlink("all", "productID=$productID&line=&status=noclosed"), $lang->product->unclosed) . '</li>';?>
    <?php echo "<li id='closedTab'>" . html::a(inlink("all", "productID=$productID&line=&status=closed"), $lang->product->statusList['closed']) . '</li>';?>
    <?php echo "<li id='allTab'>" . html::a(inlink("all", "productID=$productID&line=&status=all"), $lang->product->allProduct) . '</li>';?>
  </ul>
</div>

<div class='side' id='treebox'>
  <a class='side-handle' data-id='productTree'><i class='icon-caret-left'></i></a>
  <div class='side-body'>
    <div class='panel panel-sm'>
      <div class='panel-heading nobr'><?php echo html::icon($lang->icons['product']);?> <strong><?php echo $lang->product->line;?></strong></div>
      <div class='panel-body'>
        <?php echo $lineTree;?>
        <div class='text-right'>
          <?php common::printLink('tree', 'browse', "rootID=$productID&view=line", $lang->tree->manageLine);?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class='main'>
  <?php if(empty($productStats)):?>
  <div class='block' id='productbox'>
    <div class='container mw-500px'>
      <div class='alert'>
        <i class='icon icon-info-sign'></i>
        <div class='content'>
          <h5><?php echo $lang->my->home->noProductsTip ?></h5>
          <?php echo html::a($this->createLink('product', 'create'), "<i class='icon-plus'></i> " . $lang->my->home->createProduct,'', "class='btn btn-success'") ?>
        </div>
      </div>
    </div>
  </div>
  <?php else:?>
  <script>setTreeBox();</script>
  <?php $canOrder = (common::hasPriv('product', 'updateOrder') and strpos($orderBy, 'order') !== false)?>
  <form method='post' action='<?php echo inLink('batchEdit', "productID=$productID");?>' id='productsForm'>
    <table class='table table-condensed table-hover table-striped tablesorter table-datatable table-selectable' id='productList'>
      <?php $vars = "productID=$productID&line=$line&status=$status&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
      <thead>
        <tr>
          <th class='w-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
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
          <th class='w-60px sort-default'><?php common::printOrderLink('order', $orderBy, $vars, $lang->product->updateOrder);?></th>
          <?php endif;?>
        </tr>
      </thead>
      <?php $canBatchEdit = common::hasPriv('product', 'batchEdit'); ?>
      <tbody class='sortable' id='productTableList'>
        <?php foreach($productStats as $product):?>
        <tr class='text-center' data-id='<?php echo $product->id ?>' data-order='<?php echo $product->code ?>'>
          <td class='cell-id'>
            <?php if($canBatchEdit):?>
            <input type='checkbox' name='productIDList[<?php echo $product->id;?>]' value='<?php echo $product->id;?>' /> 
            <?php endif;?>
            <?php echo html::a($this->createLink('product', 'view', 'product=' . $product->id), sprintf('%03d', $product->id));?>
          </td>
          <td class='text-left' title='<?php echo $product->name?>'><?php echo html::a($this->createLink('product', 'view', 'product=' . $product->id), $product->name);?></td>
          <td class='text-left'><?php echo zget($lines, $product->line);?></td>
          <td><?php echo $product->stories['active']?></td>
          <td><?php echo $product->stories['changed']?></td>
          <td><?php echo $product->stories['draft']?></td>
          <td><?php echo $product->stories['closed']?></td>
          <td><?php echo $product->plans?></td>
          <td><?php echo $product->releases?></td>
          <td><?php echo $product->bugs?></td>
          <td><?php echo $product->unResolved;?></td>
          <td><?php echo $product->assignToNull;?></td>
          <?php if($canOrder):?>
          <td class='sort-handler'><i class="icon icon-move"></i></td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='<?php echo $canOrder ? 13 : 12?>'>
            <div class='table-actions clearfix'>
              <?php if($canBatchEdit and !empty($productStats)):?>
              <?php echo html::selectButton();?>
              <?php echo html::submitButton($lang->product->batchEdit);?>
              <?php endif;?>
              <?php if(!$canOrder and common::hasPriv('product', 'updateOrder')) echo html::a(inlink('all', "productID=$productID&line=$line&status=$status&order=order_desc&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"), $lang->product->updateOrder, '' , "class='btn'");?>
            </div>
            <?php $pager->show();?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
  <?php endif;?>
</div>
<script>$("#<?php echo $status;?>Tab").addClass('active');</script>
<?php js::set('orderBy', $orderBy)?>
<?php include '../../common/view/footer.html.php';?>
