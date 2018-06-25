<?php
/**
 * The batch edit file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->product->batchEdit;?></h2>
    <div class="pull-right btn-toolbar">
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=product&section=custom&key=batchEditFields')?>
      <?php include '../../common/view/customfield.html.php';?>
    </div>
  </div>
  <?php
  $visibleFields  = array();
  $requiredFields = array();
  foreach(explode(',', $showFields) as $field)
  {
      if($field) $visibleFields[$field] = '';
  }
  foreach(explode(',', $config->product->edit->requiredFields) as $field)
  {
      if($field)
      {
          $requiredFields[$field] = '';
          if(strpos(",{$config->product->customBatchEditFields},", ",{$field},") !== false) $visibleFields[$field] = '';
      }
  }
  ?>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchEditForm">
    <div class="table-responsive">
      <table class="table table-form">
        <thead>
          <tr>
            <th class='w-60px'><?php echo $lang->idAB;?></th>
            <th class='w-200px required'><?php echo $lang->product->name;?></th>
            <th class='w-150px required'><?php echo $lang->product->code;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'line',   ' hidden') . zget($requiredFields, 'line',   '', ' required');?>'><?php echo $lang->product->line;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'PO',     ' hidden') . zget($requiredFields, 'PO',     '', ' required');?>'><?php echo $lang->product->PO;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'QD',     ' hidden') . zget($requiredFields, 'QD',     '', ' required');?>'><?php echo $lang->product->QD;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'RD',     ' hidden') . zget($requiredFields, 'RD',     '', ' required');?>'><?php echo $lang->product->RD;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'type',   ' hidden') . zget($requiredFields, 'type',   '', ' required');?>'><?php echo $lang->product->type;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'status', ' hidden') . zget($requiredFields, 'status', '', ' required');?>'><?php echo $lang->product->status;?></th>
            <th class='w-200px<?php echo zget($visibleFields, 'desc',   ' hidden') . zget($requiredFields, 'desc',   '', ' required');?>'><?php echo $lang->product->desc;?></th>
            <th class='w-80px'><?php echo $lang->product->order;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($productIDList as $productID):?>
          <tr>
            <td><?php echo sprintf('%03d', $productID) . html::hidden("productIDList[$productID]", $productID);?></td>
            <td title='<?php echo $products[$productID]->name?>'><?php echo html::input("names[$productID]", $products[$productID]->name, "class='form-control' autocomplete='off'");?></td>
            <td><?php echo html::input("codes[$productID]", $products[$productID]->code, "class='form-control' autocomplete='off'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'line', ' hidden')?>' style='overflow:visible'><?php echo html::select("lines[$productID]", $lines, $products[$productID]->line, "class='form-control chosen'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'PO', ' hidden')?>' style='overflow:visible'><?php echo html::select("POs[$productID]",  $poUsers, $products[$productID]->PO, "class='form-control chosen'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'QD', ' hidden')?>' style='overflow:visible'><?php echo html::select("QDs[$productID]",  $qdUsers, $products[$productID]->QD, "class='form-control chosen'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'RD', ' hidden')?>' style='overflow:visible'><?php echo html::select("RDs[$productID]",  $rdUsers, $products[$productID]->RD, "class='form-control chosen'");?></td>
            <td class='<?php echo zget($visibleFields, 'type',   'hidden')?>'><?php echo html::select("types[$productID]",    $lang->product->typeList,   $products[$productID]->type,   "class='form-control'");?></td>
            <td class='<?php echo zget($visibleFields, 'status', 'hidden')?>'><?php echo html::select("statuses[$productID]", $lang->product->statusList, $products[$productID]->status, "class='form-control'");?></td>
            <td class='<?php echo zget($visibleFields, 'desc',   'hidden')?>'><?php echo html::textarea("descs[$productID]", htmlspecialchars($products[$productID]->desc), "rows='1' class='form-control autosize'");?></td>
            <td><?php echo html::input("orders[$productID]", $products[$productID]->order, "class='form-control' autocomplete='off'");?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="<?php echo count($visibleFields) + 4?>" class="text-center form-actions">
              <?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?>
              <?php echo html::backButton('', '', "btn btn-wide");?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
