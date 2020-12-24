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
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->product->featureBar['all'] as $key => $label):?>
    <?php $recTotalLabel = $browseType == $key ? " <span class='label label-light label-badge'>{$recTotal}</span>" : '';?>
    <?php echo html::a(inlink("all", "browseType=$key&orderBy=$orderBy"), "<span class='text'>{$label}</span>" . $recTotalLabel, '', "class='btn btn-link' id='{$key}Tab'");?>
    <?php endforeach;?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('product', 'create', '', '<i class="icon icon-plus"></i>' . $lang->product->create, '', 'class="btn btn-primary"');?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <?php if(empty($programs)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->product->noProduct;?></span></p>
  </div>
  <?php else:?>
  <div class="main-col">
    <form class="main-table table-product" data-ride="table" data-nested='true' id="productListForm" method="post" action='<?php echo inLink('batchEdit', '');?>'>
      <?php $canOrder = common::hasPriv('product', 'updateOrder');?>
      <?php $canBatchEdit = common::hasPriv('product', 'batchEdit');?>
      <table id="productList" class="table has-sort-head table-fixed table-nested">
        <?php $vars = "browseType=$browseType&orderBy=%s";?>
        <thead>
          <tr>
            <th class='table-nest-title'>
              <a class='table-nest-toggle table-nest-toggle-global' data-expand-text='<?php echo $lang->expand; ?>' data-collapse-text='<?php echo $lang->collapse; ?>'></a>
              <?php common::printOrderLink('name', $orderBy, $vars, $lang->product->name);?>
            </th>
            <th class='w-100px text-center' title='<?php echo $lang->product->activeStoriesTitle;?>'><?php echo $lang->product->activeStories;?></th>
            <th class='w-100px text-center' title='<?php echo $lang->product->changedStoriesTitle;?>'><?php echo $lang->product->changedStories;?></th>
            <th class='w-100px text-center' title='<?php echo $lang->product->draftStoriesTitle;?>'><?php echo $lang->product->draftStories;?></th>
            <th class='w-100px text-center' title='<?php echo $lang->product->closedStoriesTitle;?>'><?php echo $lang->product->closedStories;?></th>
            <th class='w-70px text-center' title='<?php echo $lang->product->plans;?>'><?php echo $lang->product->plans;?></th>
            <th class='w-70px text-center' title='<?php echo $lang->product->releases;?>'><?php echo $lang->product->releases;?></th>
            <th class='w-80px text-center' title='<?php echo $lang->product->unResolvedBugsTitle;?>'><?php echo $lang->product->unResolvedBugs;?></th>
            <th class='w-80px text-center' title='<?php echo $lang->product->assignToNullBugsTitle;?>'><?php echo $lang->product->assignToNullBugs;?></th>
            <?php if($canOrder):?>
            <th class='w-70px sort-default'><?php common::printOrderLink('order', $orderBy, $vars, $lang->product->updateOrder);?></th>
            <?php endif;?>
            <th class='c-actions w-60px'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody id="productTableList">
        <?php foreach($programs as $programID => $program):?>
        <?php
        $trAttrs  = "data-id='program.$programID' data-parent='0' data-nested='true'";
        $trClass  = 'is-top-level table-nest-child-hide';
        $trAttrs .= " class='$trClass'";
        ?>
        <?php if($program->name):?>
        <tr <?php echo $trAttrs;?>>
          <td>
            <span class="table-nest-icon icon table-nest-toggle"></span>
            <?php echo $program->name?>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <?php endif;?>
        <?php foreach($program->products as $product):?>
        <?php
        $trClass = '';
        if($product->programName)
        {
            $trAttrs  = "data-id='$product->id' data-parent='program.$product->program'";
            $trClass .= ' is-nest-child  table-nest-hide';
            $trAttrs .= " data-nest-parent='program.$product->program' data-nest-path='program.$product->program,$product->id'";
        }
        else
        {
            $trAttrs  = "data-id='$product->id' data-parent='0'";
            $trClass .= ' no-nest';
        }
        $trAttrs .= " class='$trClass'";
        ?>
        <tr <?php echo $trAttrs;?>>
          <td class="c-name" title='<?php echo $product->name?>'><?php echo html::a($this->createLink('product', 'browse', 'product=' . $product->id), $product->name);?>
          </td>
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
          <td class='c-actions'>
            <?php common::printIcon('product', 'edit', "product=$product->id", $product, 'list', 'edit');?>
          </td>
        </tr>
        <?php endforeach;?>
        <?php endforeach;?>
        </tbody>
      </table>
    </form>
  </div>
  <?php endif;?>
</div>
<?php js::set('orderBy', $orderBy)?>
<?php js::set('browseType', $browseType)?>
<?php include '../../common/view/footer.html.php';?>
