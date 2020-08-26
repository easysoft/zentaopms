<?php
/**
 * The batch edit view of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('dittoNotice', $this->lang->testcase->dittoNotice);?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->testcase->common . $lang->colon . $lang->testcase->batchEdit;?></h2>
    <div class="pull-right btn-toolbar">
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=testcase&section=custom&key=batchEditFields')?>
      <?php include '../../common/view/customfield.html.php';?>
    </div>
  </div>
  <?php if(isset($suhosinInfo)):?>
  <div class='alert alert-info'><?php echo $suhosinInfo;?></div>
  <?php else:?>
  <?php
  $visibleFields  = array();
  $requiredFields = array();
  foreach(explode(',', $showFields) as $field)
  {
      if($field)$visibleFields[$field] = '';
  }
  foreach(explode(',', $config->testcase->edit->requiredFields) as $field)
  {
      if($field)
      {
          $requiredFields[$field] = '';
          if(strpos(",{$config->testcase->customBatchEditFields},", ",{$field},") !== false) $visibleFields[$field] = '';
      }
  }
  ?>
  <form method='post' target='hiddenwin' action="<?php echo inLink('batchEdit');?>" id='batchEditForm'>
    <div class="table-responsive">
      <table class='table table-form table-fixed'>
        <thead>
          <tr class='text-center'>
            <th class='w-50px'><?php  echo $lang->idAB;?></th>
            <th class='w-70px<?php echo zget($visibleFields, 'pri', ' hidden') . zget($requiredFields, 'pri', '', ' required');?>'><?php echo $lang->priAB;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'status', ' hidden') . zget($requiredFields, 'status', '', ' required');?>'><?php echo $lang->statusAB;?></th>
            <?php if($branchProduct):?>
            <th class='w-150px<?php echo zget($visibleFields, 'branch', ' hidden')?>'><?php echo $lang->testcase->branch;?></th>
            <?php endif;?>
            <th class='w-150px<?php echo zget($visibleFields, 'module', ' hidden')?>'><?php echo $lang->testcase->module;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'story', ' hidden') . zget($requiredFields, 'story', '', ' required');?>'><?php echo $lang->testcase->story;?></th>
            <th class='text-left required'><?php echo $lang->testcase->title;?></th>
            <th class='w-120px required'><?php echo $lang->testcase->type;?></th>
            <th class='<?php echo zget($visibleFields, 'precondition', 'hidden') . zget($requiredFields, 'precondition', '', ' required');?>'><?php echo $lang->testcase->precondition;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'keywords', ' hidden') . zget($requiredFields, 'keywords', '', ' required');?>'><?php echo $lang->testcase->keywords;?></th>
            <th class='w-250px<?php echo zget($visibleFields, 'stage', ' hidden') . zget($requiredFields, 'stage', '', ' required');?>'><?php echo $lang->testcase->stage;?></th>
            <?php
            $extendFields = $this->testcase->getFlowExtendFields();
            foreach($extendFields as $extendField) echo "<th class='w-100px'>{$extendField->name}</th>";
            ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($caseIDList as $caseID):?>
          <?php
          if(!isset($cases[$caseID])) continue;
          if(!$productID and $branchProduct)
          {
              $product = $this->product->getByID($cases[$caseID]->product);

              $branches = $product->type == 'normal' ? array('' => '') : $this->loadModel('branch')->getPairs($product->id);
              if($product->type != 'normal')
              {
                  foreach($branches as $branchID => $branchName) $branches[$branchID] = '/' . $product->name . '/' . $branchName;
                  $branches = array('ditto' => $this->lang->story->ditto) + $branches;
              }

              $modules = $this->tree->getOptionMenu($cases[$caseID]->product, $viewType = 'case', 0, $cases[$caseID]->branch);
              $modules = array('ditto' => $this->lang->story->ditto) + $modules;
          }
          ?>
          <tr class='text-center'>
            <td><?php echo $caseID . html::hidden("caseIDList[$caseID]", $caseID);?></td>
            <td class='<?php echo zget($visibleFields, 'pri', 'hidden')?>'>   <?php echo html::select("pris[$caseID]",     $priList, $cases[$caseID]->pri, 'class=form-control');?></td>
            <td class='<?php echo zget($visibleFields, 'status', 'hidden')?>'>
              <?php
              if(!$forceNotReview and $cases[$caseID]->status == 'wait')
              {
                  echo $lang->testcase->statusList['wait'];
                  echo html::hidden("statuses[$caseID]", 'wait');
              }
              else
              {
                  echo html::select("statuses[$caseID]", (array)$lang->testcase->statusList, $cases[$caseID]->status, 'class=form-control');
              }
              ?>
            </td>
            <?php if($branchProduct):?>
            <td class='text-left<?php echo zget($visibleFields, 'branch', ' hidden')?>' style='overflow:visible'>
              <?php $branchProductID = $productID ? $productID : $product->id;?>
              <?php $disabled        = (isset($product) and $product->type == 'normal') ? "disabled='disabled'" : '';?>
              <?php echo html::select("branches[$caseID]",  $branches,   $cases[$caseID]->branch, "class='form-control chosen' onchange='loadBranches($branchProductID, this.value, $caseID)', $disabled");?>
            </td>
            <?php endif;?>
            <td class='text-left<?php echo zget($visibleFields, 'module', ' hidden')?>' style='overflow:visible'><?php echo html::select("modules[$caseID]",  $modules,   $cases[$caseID]->module, "class='form-control chosen'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'story', ' hidden')?>' style='overflow:visible'><?php echo html::select("stories[$caseID]",  $stories,   $cases[$caseID]->story, "class='form-control chosen'");?></td>
            <td style='overflow:visible' title='<?php echo $cases[$caseID]->title?>'>
              <div class='input-group'>
                <div class="input-control has-icon-right">
                  <?php echo html::input("title[$caseID]", $cases[$caseID]->title, "class='form-control'");?>
                  <div class="colorpicker">
                    <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                    <ul class="dropdown-menu clearfix">
                      <li class="heading"><?php echo $lang->testcase->colorTag;?><i class="icon icon-close"></i></li>
                    </ul>
                    <?php echo html::hidden("color[$caseID]", $cases[$caseID]->color, "data-provide='colorpicker' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#title\\[$caseID\\]'");?>
                  </div>
                </div>
              </div>
            </td>
            <td><?php echo html::select("types[$caseID]", $typeList, $cases[$caseID]->type, 'class=form-control');?></td>
            <td class='<?php echo zget($visibleFields, 'precondition', 'hidden')?>'><?php echo html::textarea("precondition[$caseID]", $cases[$caseID]->precondition, "rows='1' class='form-control autosize'")?></td>
            <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?>'>    <?php echo html::input("keywords[$caseID]", $cases[$caseID]->keywords, "class='form-control'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'stage', ' hidden')?>' style='overflow:visible'><?php echo html::select("stages[$caseID][]", $lang->testcase->stageList, $cases[$caseID]->stage, "class='form-control chosen' multiple data-placeholder='{$lang->testcase->stage}'");?></td>
            <?php foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->loadModel('flow')->getFieldControl($extendField, $cases[$caseID], $extendField->field . "[{$caseID}]") . "</td>";?>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='<?php echo $branchProduct ? (count($visibleFields) + 3) : (count($visibleFields) + 2);?>' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
<?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
