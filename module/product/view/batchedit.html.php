<?php
/**
 * The batch edit file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('openTip',    $lang->product->aclTips['open']);?>
<?php js::set('privateTip', $lang->product->aclTips['private']);?>
<?php js::set('productLines', $lines);?>
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
            <th class='c-id'><?php echo $lang->idAB;?></th>
            <?php
            $width = '';
            $full  = 'width: 100%';
            if(count($visibleFields) > 6)
            {
                $width = 'w-200px';
                $full  = '';
            }
            ?>
            <?php if($this->config->systemMode == 'ALM'):?>
            <th class='c-program<?php echo zget($visibleFields, 'program', ' hidden') . zget($requiredFields, 'program', '', ' required');?>'><?php echo $lang->product->program;?></th>
            <?php endif;?>
            <th class='required <?php echo $width;?>' style="<?php echo $full;?>"><?php echo $lang->product->name;?></th>
            <?php if($this->config->systemMode == 'ALM'):?>
            <th class='c-line<?php echo zget($visibleFields, 'line',     ' hidden') . zget($requiredFields, 'line',   '', ' required');?>'><?php echo $lang->product->line;?></th>
            <?php endif;?>
            <th class='c-user-box<?php echo zget($visibleFields, 'PO',   ' hidden') . zget($requiredFields, 'PO',     '', ' required');?>'><?php echo $lang->product->PO;?></th>
            <th class='c-user-box<?php echo zget($visibleFields, 'QD',   ' hidden') . zget($requiredFields, 'QD',     '', ' required');?>'><?php echo $lang->product->QD;?></th>
            <th class='c-user-box<?php echo zget($visibleFields, 'RD',   ' hidden') . zget($requiredFields, 'RD',     '', ' required');?>'><?php echo $lang->product->RD;?></th>
            <th class='c-type<?php echo zget($visibleFields, 'type',     ' hidden') . zget($requiredFields, 'type',   '', ' required');?>'><?php echo $lang->product->type;?></th>
            <th class='c-status<?php echo zget($visibleFields, 'status', ' hidden') . zget($requiredFields, 'status', '', ' required');?>'><?php echo $lang->product->status;?></th>
            <th class='c-desc<?php echo zget($visibleFields, 'desc',     ' hidden') . zget($requiredFields, 'desc',   '', ' required');?>'><?php echo $lang->product->desc;?></th>
            <th class='c-acl<?php echo zget($visibleFields, 'acl',       ' hidden');?>'><?php echo $lang->product->acl;?></th>
            <?php
            $extendFields = $this->product->getFlowExtendFields();
            foreach($extendFields as $extendField) echo "<th class='c-extend'>{$extendField->name}</th>";
            ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach(array_keys($products) as $productID):?>
          <?php
          if(!empty($this->config->moreLinks["PO"])) $this->config->moreLinks["POs[$productID]"] = $this->config->moreLinks["PO"];
          if(!empty($this->config->moreLinks["QD"])) $this->config->moreLinks["QDs[$productID]"] = $this->config->moreLinks["QD"];
          if(!empty($this->config->moreLinks["RD"])) $this->config->moreLinks["RDs[$productID]"] = $this->config->moreLinks["RD"];
          ?>
          <tr>
            <td><?php echo sprintf('%03d', $productID) . html::hidden("productIdList[$productID]", $productID);?></td>
            <?php if($this->config->systemMode == 'ALM'):?>
            <?php if(isset($unauthPrograms[$products[$productID]->program])):?>
            <td class='text-left<?php echo zget($visibleFields, 'program', ' hidden')?>' style='overflow:visible'><?php echo html::select("programs[$productID]",  $unauthPrograms, $products[$productID]->program, "class='form-control' disabled");?></td>
            <?php else:?>
            <td class='text-left<?php echo zget($visibleFields, 'program', ' hidden')?>' style='overflow:visible'><?php echo html::select("programs[$productID]",  $authPrograms, $products[$productID]->program, "class='form-control picker-select' onchange='loadProductLines(this.value, $productID)'");?></td>
            <?php endif;?>
            <?php endif;?>
            <td title='<?php echo $products[$productID]->name?>'><?php echo html::input("names[$productID]", $products[$productID]->name, "class='form-control'");?></td>
            <?php if($this->config->systemMode == 'ALM'):?>
            <?php $productLines = isset($lines[$products[$productID]->program]) ? $lines[$products[$productID]->program] : '';?>
            <td class='text-left<?php echo zget($visibleFields, 'line', ' hidden')?>' style='overflow:visible' id="line_<?php echo $productID;?>"><?php echo html::select("lines[$productID]", $this->config->systemMode == 'ALM' ? $productLines : $lines, $products[$productID]->line, "class='form-control picker-select'");?></td>
            <?php endif;?>
            <td class='text-left<?php echo zget($visibleFields, 'PO', ' hidden')?>' style='overflow:visible'><?php echo html::select("POs[$productID]",  $poUsers, $products[$productID]->PO, "class='form-control picker-select'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'QD', ' hidden')?>' style='overflow:visible'><?php echo html::select("QDs[$productID]",  $qdUsers, $products[$productID]->QD, "class='form-control picker-select'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'RD', ' hidden')?>' style='overflow:visible'><?php echo html::select("RDs[$productID]",  $rdUsers, $products[$productID]->RD, "class='form-control picker-select'");?></td>
            <td class='<?php echo zget($visibleFields, 'type', 'hidden')?>'><?php echo html::select("types[$productID]",    $lang->product->typeList,   $products[$productID]->type,   "class='form-control'");?></td>
            <td class='<?php echo zget($visibleFields, 'status', 'hidden')?>'><?php echo html::select("statuses[$productID]", $lang->product->statusList, $products[$productID]->status, "class='form-control'");?></td>
            <td class='<?php echo zget($visibleFields, 'desc',   'hidden')?>'><?php echo html::textarea("descs[$productID]", htmlSpecialString($products[$productID]->desc), "rows='1' class='form-control autosize'");?></td>
            <td class='<?php echo zget($visibleFields, 'acl', 'hidden')?>'> <?php echo nl2br(html::radio("acls[$productID]", $lang->product->acls, $products[$productID]->acl));?></td>
            <?php foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->loadModel('flow')->getFieldControl($extendField, $products[$productID], $extendField->field . "[{$productID}]") . "</td>";?>
          </tr>
          <?php
          if(isset($this->config->moreLinks["POs[$productID]"])) unset($this->config->moreLinks["POs[$productID]"]);
          if(isset($this->config->moreLinks["QDs[$productID]"])) unset($this->config->moreLinks["QDs[$productID]"]);
          if(isset($this->config->moreLinks["RDs[$productID]"])) unset($this->config->moreLinks["RDs[$productID]"]);
          ?>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <div class="text-center form-actions">
          <?php echo html::submitButton();?>
          <?php $browseLink = $this->app->tab == 'product' ? $this->createLink('product', 'all') : $this->createLink('program', 'product', "programID=$programID");?>
          <?php echo html::a($browseLink, $lang->goback, '', 'class="btn btn-back btn-wide"');?>
        </div>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
