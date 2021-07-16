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
    <?php common::printLink('product', 'manageLine', '', $lang->product->line, '', 'class="btn btn-link"');?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('product', 'create', '', '<i class="icon icon-plus"></i>' . $lang->product->create, '', 'class="btn btn-primary"');?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <?php if(empty($productStructure)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->product->noProduct;?></span></p>
  </div>
  <?php else:?>
  <div class="main-col">
    <form class="main-table table-product" data-ride="table" data-nested='true' id="productListForm" method="post" action='<?php echo inLink('batchEdit', '');?>' data-preserve-nested='false' data-expand-nest-child='true'>
      <?php
      $canOrder     = common::hasPriv('product', 'updateOrder');
      $canBatchEdit = common::hasPriv('product', 'batchEdit');
      ?>
      <table id="productList" class="table has-sort-head table-fixed table-nested table-form">
        <?php $vars = "browseType=$browseType&orderBy=%s";?>
        <thead>
          <tr class="text-center">
            <th class='table-nest-title text-left' rowspan="2">
              <?php if($canBatchEdit) echo "<div class='checkbox-primary check-all' title='{$this->lang->selectAll}'><label></label></div>";?>
              <a class='table-nest-toggle table-nest-toggle-global <?php echo $canBatchEdit ? 'nest-has-checkbox' : 'nest-none-checkbox';?>' data-expand-text='<?php echo $lang->expand; ?>' data-collapse-text='<?php echo $lang->collapse; ?>'></a>
              <?php common::printOrderLink('name', $orderBy, $vars, $lang->product->name);?>
            </th>
            <?php if($this->config->URAndSR):?>
            <th class="w-300px" colspan="4"><?php echo $lang->story->requirement;?></th>
            <?php endif;?>
            <th class="w-300px" colspan="4"><?php echo $lang->story->story;?></th>
            <th class="w-150px" colspan="2"><?php echo $lang->bug->common;?></th>
            <th class="w-80px"  rowspan="2"><?php echo $lang->product->release;?></th>
            <th class="w-80px"  rowspan="2"><?php echo $lang->product->plan;?></th>
            <th class='c-actions w-70px' rowspan="2"><?php echo $lang->actions;?></th>
          </tr>
          <tr class="text-center">
            <?php if($this->config->URAndSR):?>
            <th style="border-left: 1px solid #ddd;"><?php echo $lang->story->activate;?></th>
            <th><?php echo $lang->story->close;?></th>
            <th><?php echo $lang->story->draft;?></th>
            <th><?php echo $lang->story->completeRate;?></th>
            <?php endif;?>
            <th style="border-left: 1px solid #ddd;"><?php echo $lang->story->activate;?></th>
            <th><?php echo $lang->story->close;?></th>
            <th><?php echo $lang->story->draft;?></th>
            <th><?php echo $lang->story->completeRate;?></th>
            <th style="border-left: 1px solid #ddd;"><?php echo $lang->bug->activate;?></th>
            <th><?php echo $lang->close;?></th>
          </tr>
        </thead>
        <tbody id="productTableList">
        <?php $lineNames = array();?>
        <?php foreach($productStructure as $programID => $program):?>
        <?php
        $trAttrs  = "data-id='program.$programID' data-parent='0' data-nested='true'";
        $trClass  = 'is-top-level table-nest-child';
        $trAttrs .= " class='$trClass'";
        ?>
          <?php if(isset($program['programName']) and $config->systemMode == 'new'):?>
          <tr <?php echo $trAttrs;?>>
            <td colspan="<?php echo $this->config->URAndSR ? 14 : 10;?>">
              <span class="table-nest-icon icon table-nest-toggle"></span>
              <?php echo $program['programName']?>
            </td>
          </tr>
          <?php unset($program['programName']);?>
          <?php endif;?>

          <?php foreach($program as $lineID => $line):?>
          <?php if(isset($line['lineName']) and !in_array($line['lineName'], $lineNames)):?>
          <?php $lineNames[] = $line['lineName'];?>
          <?php
          if($this->config->systemMode == 'new' and $programID)
          {
              $trAttrs  = "data-id='line.$lineID' data-parent='program.$programID'";
              $trClass .= ' is-nest-child  table-nest';
              $trAttrs .= " data-nest-parent='program.$programID' data-nest-path='program.$programID,$lineID'";
          }
          else
          {
              $trAttrs  = "data-id='line.$lineID' data-parent='0' data-nested='true'";
              $trClass  = 'is-top-level table-nest-child';
              $trAttrs .= " class='$trClass'";
          }
          ?>
          <tr <?php echo $trAttrs;?>>
            <td colspan="<?php echo $this->config->URAndSR ? 14 : 10;?>">
              <span class="table-nest-icon icon table-nest-toggle"></span>
              <?php echo $line['lineName']?>
            </td>
          </tr>
          <?php unset($line['lineName']);?>
          <?php endif;?>

          <?php if(isset($line['products']) and is_array($line['products'])):?>
          <?php foreach($line['products'] as $productID => $product):?>
          <?php
          $totalStories      = $product->stories['active'] + $product->stories['closed'] + $product->stories['draft'] + $product->stories['changed'];
          $totalRequirements = $product->requirements['active'] + $product->requirements['closed'] + $product->requirements['draft'] + $product->requirements['changed'];

          $trClass = '';
          if($product->line)
          {
              $path = "$product->line,$product->id";
              if($this->config->systemMode == 'new' and $product->program) $path = "$product->program,$path";
              $trAttrs  = "data-id='$product->id' data-parent='line.$product->line'";
              $trClass .= ' is-nest-child  table-nest';
              $trAttrs .= " data-nest-parent='line.$product->line' data-nest-path='line.{$path}'";
          }
          elseif($product->program and $this->config->systemMode == 'new')
          {
              $trAttrs  = "data-id='$product->id' data-parent='program.$product->program'";
              $trClass .= ' is-nest-child  table-nest';
              $trAttrs .= " data-nest-parent='program.$product->program' data-nest-path='program.$product->program,$product->id'";
          }
          else
          {
              $trAttrs  = "data-id='$product->id' data-parent='0'";
              $trClass .= ' no-nest';
          }
          $trAttrs .= " class='$trClass'";
          ?>
          <tr class="text-center" <?php echo $trAttrs;?>>
            <td class="c-name text-left" title='<?php echo $product->name?>'>
              <?php
              $productLink = html::a($this->createLink('product', 'browse', 'product=' . $product->id), $product->name);
              $productName = "<span class='table-nest-icon icon icon-product'></span>" . $productLink;
              echo $canBatchEdit ? html::checkbox('productIDList', array($product->id => '')) . $productName : $productName;
              ?>
            </td>
            <?php if($this->config->URAndSR):?>
            <td><?php echo $product->requirements['active'];?></td>
            <td><?php echo $product->requirements['closed'];?></td>
            <td><?php echo $product->requirements['draft'];?></td>
            <td><?php echo $totalRequirements == 0 ? 0 : round($product->requirements['closed'] / $totalRequirements, 3) * 100;?>%</td>
            <?php endif;?>
            <td><?php echo $product->stories['active'];?></td>
            <td><?php echo $product->stories['closed'];?></td>
            <td><?php echo $product->stories['draft'];?></td>
            <td><?php echo $totalStories == 0 ? 0 : round($product->stories['closed'] / $totalStories, 3) * 100;?>%</td>
            <td><?php echo $product->unResolved;?></td>
            <td><?php echo $product->closedBugs;?></td>
            <td><?php echo $product->releases;?></td>
            <td><?php echo $product->plans;?></td>
            <td class='c-actions sort-handler'>
              <?php common::printIcon('product', 'edit', "product=$product->id", $product, 'list', 'edit');?>
              <?php if($canOrder):?>
              <i class="icon icon-move"></i>
              <?php endif;?>
            </td>
          </tr>
          <?php endforeach;?>
          <?php endif;?>
          <?php endforeach;?>
        <?php endforeach;?>
        </tbody>
      </table>
      <?php if(!empty($product) and $canBatchEdit):?>
      <div class='table-footer'>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class="table-actions btn-toolbar">
        <?php
        $actionLink = $this->createLink('product', 'batchEdit');
        echo html::commonButton($lang->edit, "data-form-action='$actionLink'");
        ?>
        </div>
      </div>
      <?php endif;?>
    </form>
  </div>
  <?php endif;?>
</div>
<?php js::set('orderBy', $orderBy)?>
<?php js::set('browseType', $browseType)?>
<?php include '../../common/view/footer.html.php';?>
