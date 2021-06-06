<?php
/**
 * The html product list file of product method of program module of ZenTaoPMS.
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
  <?php if(!isonlybody()):?>
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->product->featureBar['all'] as $key => $label):?>
    <?php $active = $key == $browseType ? 'btn-active-text' : '';?>
    <?php if($key == $browseType) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink("product", "programID=$program->id&browseType=$key&orderBy=$orderBy"), "<span class='text'>{$label}</span>", '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('product', 'create', "programID=$program->id", '<i class="icon icon-plus"></i>' . $lang->product->create, '', 'class="btn btn-primary"');?>
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
    <form class="main-table table-product" data-ride="table" id="productListForm" method="post" action='<?php echo $this->createLink('product', 'batchEdit', "programID=$program->id");?>'>
      <?php $canOrder = common::hasPriv('product', 'updateOrder');?>
      <?php $canBatchEdit = common::hasPriv('product', 'batchEdit');?>
      <table id="productList" class="table has-sort-head table-bordered table-fixed">
        <?php $vars = "programID=$program->id&browseType=$browseType&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
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
            <th class="w-300px" colspan="4"><?php echo $lang->story->requirement;?></th>
            <th class="w-300px" colspan="4"><?php echo $lang->story->story;?></th>
            <th class="w-150px" colspan="2"><?php echo $lang->bug->common;?></th>
            <th class="w-80px"  rowspan="2"><?php echo $lang->product->release;?></th>
            <th class="w-80px"  rowspan="2"><?php echo $lang->product->plan;?></th>
            <th class='c-actions w-60px' rowspan="2"><?php echo $lang->actions;?></th>
          </tr>
          <tr class="text-center">
            <th style="border-left: 1px solid #ddd;"><?php echo $lang->story->activate;?></th>
            <th><?php echo $lang->story->close;?></th>
            <th><?php echo $lang->story->draft;?></th>
            <th><?php echo $lang->story->completeRate;?></th>
            <th style="border-left: 1px solid #ddd;"><?php echo $lang->story->activate;?></th>
            <th><?php echo $lang->story->close;?></th>
            <th><?php echo $lang->story->draft;?></th>
            <th><?php echo $lang->story->completeRate;?></th>
            <th style="border-left: 1px solid #ddd;"><?php echo $lang->bug->activate;?></th>
            <th><?php echo $lang->close;?></th>
          </tr>
        </thead>
        <tbody class="sortable" id="productTableList">
        <?php foreach($products as $product):?>
          <?php
          $totalStories      = $product->stories['active'] + $product->stories['closed'] + $product->stories['draft'] + $product->stories['changed'];
          $totalRequirements = $product->requirements['active'] + $product->requirements['closed'] + $product->requirements['draft'] + $product->requirements['changed'];
          ?>
          <tr class="text-center" data-id='<?php echo $product->id ?>' data-order='<?php echo $product->code;?>'>
            <td class='c-id text-left'>
              <?php if($canBatchEdit):?>
              <?php echo html::checkbox('productIDList', array($product->id => sprintf('%03d', $product->id)));?>
              <?php else:?>
              <?php printf('%03d', $product->id);?>
              <?php endif;?>
            </td>
            <td class="c-name" title='<?php echo $product->name?>'><?php echo html::a($this->createLink('product', 'browse', 'product=' . $product->id), $product->name);?></td>
            <td><?php echo $product->requirements['active'];?></td>
            <td><?php echo $product->requirements['closed'];?></td>
            <td><?php echo $product->requirements['draft'];?></td>
            <td><?php echo $totalRequirements == 0 ? 0 : round($product->requirements['closed'] / $totalRequirements, 3) * 100;?>%</td>
            <td><?php echo $product->stories['active'];?></td>
            <td><?php echo $product->stories['closed'];?></td>
            <td><?php echo $product->stories['draft'];?></td>
            <td><?php echo $totalStories == 0 ? 0 : round($product->stories['closed'] / $totalStories, 3) * 100;?>%</td>
            <td><?php echo $product->unResolved;?></td>
            <td><?php echo $product->closedBugs;?></td>
            <td><?php echo $product->releases;?></td>
            <td><?php echo $product->plans;?></td>
            <td class='c-actions'>
              <?php common::printIcon('product', 'edit', "product=$product->id&action=edit&extra=&programID=$program->id", $product, 'list', 'edit');?>
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
          <?php echo html::submitButton($lang->edit, '', 'btn');?>
        </div>
        <?php endif;?>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
  </div>
  <?php endif;?>
</div>
<?php js::set('orderBy', $orderBy)?>
<?php js::set('programID', $program->id)?>
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
