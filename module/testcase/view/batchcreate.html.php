<?php
/**
 * The batch create view of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('testcaseBatchCreateNum', $config->testcase->batchCreate);?>
<?php js::set('productID', $productID);?>
<?php js::set('branch', $branch);?>
<?php js::set('requiredFields', $config->testcase->create->requiredFields)?>
<?php js::set('showFields', $showFields);?>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2>
      <?php echo $lang->testcase->batchCreate;?>
      <?php if($story):?>
      <small class='text' title='<?php echo $story->title ?>'><?php echo $lang->arrow . $story->title ?></small>
      <?php endif;?>
    </h2>
    <div class="pull-right btn-toolbar">
      <button type='button' data-toggle='modal' data-target="#importLinesModal" class="btn btn-primary"><?php echo $lang->pasteText;?></button>
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=testcase&section=custom&key=batchCreateFields')?>
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
  foreach(explode(',', $config->testcase->create->requiredFields) as $field)
  {
      if($field)
      {
          $requiredFields[$field] = '';
          if(strpos(",{$config->testcase->list->customBatchCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
      }
  }
  $colspan     = count($visibleFields) + 3;
  $hiddenStory = (isonlybody() and $story) ? ' hidden' : '';
  if($hiddenStory and isset($visibleFields['story'])) $colspan -= 1;
  ?>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchCreateForm">
    <div class="table-responsive">
      <table class="table table-form" id="tableBody">
        <thead>
          <tr class='text-center'>
            <th class='c-id'><?php echo $lang->idAB;?></th>
            <th class='c-branch<?php echo zget($visibleFields, $product->type, ' hidden')?> branchBox'><?php echo $lang->product->branch;?></th>
            <th class='c-module<?php echo zget($visibleFields, 'module', ' hidden') . zget($requiredFields, 'module', '', ' required');?> moduleBox'><?php echo $lang->testcase->module;?></th>
            <th class='c-scene<?php echo zget($visibleFields, 'scene', ' hidden') . zget($requiredFields, 'scene', '', ' required');?> sceneBox'><?php echo $lang->testcase->scene;?></th>
            <th class='c-story<?php echo zget($visibleFields, 'story', ' hidden') . zget($requiredFields, 'story', '', ' required'); echo $hiddenStory;?> storyBox'> <?php echo $lang->testcase->story;?></th>
            <th class='text-left required has-btn c-title'><?php echo $lang->testcase->title;?></th>
            <th class='c-type text-left required'><?php echo $lang->testcase->type;?></th>
            <th class='c-pri<?php  echo zget($visibleFields, 'pri', ' hidden') . zget($requiredFields, 'pri', '', ' required')?> priBox'><?php echo $lang->testcase->pri;?></th>
            <th class='c-precondition<?php echo zget($visibleFields, 'precondition', ' hidden') . zget($requiredFields, 'precondition', '', ' required')?> preconditionBox'><?php echo $lang->testcase->precondition;?></th>
            <th class='c-keywords<?php echo zget($visibleFields, 'keywords', ' hidden') . zget($requiredFields, 'keywords', '', ' required')?> keywordsBox'><?php echo $lang->testcase->keywords;?></th>
            <th class='c-stage<?php echo zget($visibleFields, 'stage', ' hidden') . zget($requiredFields, 'stage', '', ' required')?> stageBox'><?php echo $lang->testcase->stage;?></th>
            <th class='c-review<?php  echo zget($visibleFields, 'review', ' hidden') . zget($requiredFields, 'review', '', ' required')?> reviewBox'><?php echo $lang->testcase->review;?></th>
            <?php
            $extendFields = $this->testcase->getFlowExtendFields();
            foreach($extendFields as $extendField)
            {
                $required = strpos(",$extendField->rules,", ',1,') !== false ? 'required' : '';
                echo "<th class='c-extend $required'>{$extendField->name}</th>";
            }
            ?>
            <th class='c-actions text-left'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php unset($lang->testcase->typeList['']);?>
          <?php for($i = 1; $i <= $config->testcase->batchCreate; $i++):?>
          <?php
          if($i != 1) $currentModuleID = 'ditto';
          if($i != 1) $currentSceneID = 'ditto';
          if($i != 1) $lang->testcase->typeList['ditto'] = $lang->testcase->ditto;
          if($i != 1) $lang->testcase->priList['ditto']  = $lang->testcase->ditto;
          $type = $i == 1 ? 'feature' : 'ditto';
          $pri  = $i == 1 ? 3 : 'ditto';
          ?>
          <tr>
            <td class="text-center"><?php echo $i;?></td>
            <td class='text-left<?php echo zget($visibleFields, $product->type, ' hidden')?> branchBox'><?php echo html::select("branch[$i]", $branches, $branch, "class='form-control' onchange='setModules(this.value, $productID, $i)'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'module', ' hidden')?> moduleBox' style='overflow:visible'><?php echo html::select("module[$i]", $moduleOptionMenu, $currentModuleID, "class='form-control chosen' onchange='onModuleChanged($productID, this.value, $i)' data-drop_direction='down'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'scene', ' hidden')?>' style='overflow:visible;'><?php echo html::select("scene[$i]", $sceneOptionMenu, $currentSceneID, "class='form-control chosen' data-drop_direction='down'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'story', ' hidden'); echo $hiddenStory;?> storyBox' style='overflow:visible'> <?php echo html::select("story[$i]", $storyPairs, $story ? $story->id : '', 'class="form-control picker-select"');?></td>
            <td style='overflow:visible'>
              <div class="input-control has-icon-right">
                <?php echo html::input("title[$i]", '', "class='form-control title-import'");?>
                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix">
                    <li class="heading"><?php echo $lang->testcase->colorTag;?><i class="icon icon-close"></i></li>
                  </ul>
                  <?php echo html::hidden("color[$i]", '', "data-provide='colorpicker' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#title\\[$i\\]'");?>
                </div>
              </div>
            </td>
            <td><?php echo html::select("type[$i]", $lang->testcase->typeList, $type, "class='form-control chosen'");?></td>
            <td class='<?php echo zget($visibleFields, 'pri', 'hidden')?> priBox'><?php echo html::select("pri[$i]", $lang->testcase->priList, $pri, "class='form-control chosen'");?></td>
            <td class='<?php echo zget($visibleFields, 'precondition', 'hidden')?> preconditionBox'><?php echo html::textarea("precondition[$i]", '', "rows='1' class='form-control autosize'")?></td>
            <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?> keywordsBox'><?php echo html::input("keywords[$i]", '', "class='form-control'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'stage', ' hidden')?> stageBox' style='overflow:visible'><?php echo html::select("stage[$i][]", $lang->testcase->stageList, '', "class='form-control chosen' multiple");?></td>
            <td class='<?php echo zget($visibleFields, 'review', 'hidden')?> reviewBox'><?php echo html::select("needReview[$i]", $lang->testcase->reviewList, $needReview, "class='form-control chosen'");?></td>
            <?php
            $this->loadModel('flow');
            foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->flow->getFieldControl($extendField, '', $extendField->field . "[$i]") . "</td>";
            ?>
            <td class='c-actions text-left'>
              <a href='javascript:;' onclick='addRow(this)' class='btn btn-link'><i class='icon-plus'></i></a>
              <?php if($i != 1):?>
              <a href='javascript:;' onclick='deleteRow(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
              <?php endif;?>
            </td>
          </tr>
          <?php endfor;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='<?php echo $colspan?>' class='text-center form-actions'>
              <?php echo html::submitButton('', '', 'form-stash-clear btn btn-wide btn-primary');?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<?php js::set('rowIndex', -- $i);?>
<table class='template' id='trTemp'>
  <tbody>
    <tr>
      <td class="text-center">%s</td>
      <td class='text-left<?php echo zget($visibleFields, $product->type, ' hidden')?> branchBox'><?php echo html::select("branch[%s]", $branches, $branch, "class='form-control chosen' onchange='setModules(this.value, $productID, \"%s\")'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'module', ' hidden')?> moduleBox' style='overflow:visible'><?php echo html::select("module[%s]", $moduleOptionMenu, $currentModuleID, "class='form-control chosen' onchange='onModuleChanged($productID, this.value, \"%s\")' data-drop_direction='down'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'scene', ' hidden')?>' style='overflow:visible'><?php echo html::select("scene[%s]", $sceneOptionMenu, $currentSceneID, "class='form-control chosen' data-drop_direction='down'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'story', ' hidden'); echo $hiddenStory;?> storyBox' style='overflow:visible'> <?php echo html::select("story[%s]", $storyPairs, '', 'class="form-control picker-select"');?></td>
      <td style='overflow:visible'>
        <div class="input-control has-icon-right">
          <?php echo html::input("title[%s]", '', "class='form-control title-import'");?>
          <div class="colorpicker">
            <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
            <ul class="dropdown-menu clearfix">
              <li class="heading"><?php echo $lang->testcase->colorTag;?><i class="icon icon-close"></i></li>
            </ul>
            <?php echo html::hidden("color[%s]", '', "data-provide='colorpicker-later' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#title\\[%s\\]'");?>
          </div>
        </div>
      </td>
      <td><?php echo html::select("type[%s]", $lang->testcase->typeList, $type, "class='form-control chosen'");?></td>
      <td class='<?php echo zget($visibleFields, 'pri', 'hidden')?> priBox'><?php echo html::select("pri[%s]", $lang->testcase->priList, $pri, "class='form-control chosen'");?></td>
      <td class='<?php echo zget($visibleFields, 'precondition', 'hidden')?> preconditionBox'><?php echo html::textarea("precondition[%s]", '', "rows='1' class='form-control'")?></td>
      <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?> keywordsBox'><?php echo html::input("keywords[%s]", '', "class='form-control'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'stage', ' hidden')?> stageBox' style='overflow:visible'><?php echo html::select("stage[%s][]", $lang->testcase->stageList, '', "class='form-control chosen' multiple");?></td>
      <td class='<?php echo zget($visibleFields, 'review', 'hidden')?> reviewBox'><?php echo html::select("needReview[%s]", $lang->testcase->reviewList, $needReview, "class='form-control chosen'");?></td>
      <?php
      $this->loadModel('flow');
      foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->flow->getFieldControl($extendField, '', $extendField->field . "[%s]") . "</td>";
      ?>
      <td class='c-actions text-left'>
        <a href='javascript:;' onclick='addRow(this)' class='btn btn-link'><i class='icon-plus'></i></a>
        <?php if($i != 1):?>
        <a href='javascript:;' onclick='deleteRow(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
        <?php endif;?>
      </td>
    </tr>
  </tbody>
</table>
<div>
  <?php $i = '%i%';?>
  <table class='hidden'>
    <tr id='addRow' class='hidden'>
      <td class="text-center"><?php echo $i;?></td>
      <td class='text-left<?php echo zget($visibleFields, $product->type, ' hidden')?> branchBox'><?php echo html::select("branch[$i]", $branches, $branch, "class='form-control' onchange='setModules(this.value, $productID, $i)'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'module', ' hidden')?> moduleBox' style='overflow:visible'><?php echo html::select("module[$i]", $moduleOptionMenu, $currentModuleID, "class='form-control chosen' onchange='onModuleChanged($productID, this.value, $i)' data-drop_direction='down'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'scene', ' hidden')?>' style='overflow:visible'><?php echo html::select("scene[$i]", $sceneOptionMenu, $currentSceneID, "class='form-control chosen' data-drop_direction='down'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'story', ' hidden'); echo $hiddenStory;?> storyBox' style='overflow:visible'> <?php echo html::select("story[$i]", $storyPairs, $story ? $story->id : '', 'class="form-control picker-select"');?></td>
      <td style='overflow:visible'>
        <div class="input-control has-icon-right">
          <?php echo html::input("title[$i]", '', "class='form-control title-import'");?>
          <div class="colorpicker">
            <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
            <ul class="dropdown-menu clearfix">
              <li class="heading"><?php echo $lang->testcase->colorTag;?><i class="icon icon-close"></i></li>
            </ul>
            <?php echo html::hidden("color[$i]", '', "data-provide='colorpicker' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#title\\[$i\\]'");?>
          </div>
        </div>
      </td>
      <td><?php echo html::select("type[$i]", $lang->testcase->typeList, $type, "class='form-control chosen'");?></td>
      <td class='<?php echo zget($visibleFields, 'pri', 'hidden')?> priBox'><?php echo html::select("pri[$i]", $lang->testcase->priList, $pri, "class='form-control chosen'");?></td>
      <td class='<?php echo zget($visibleFields, 'precondition', 'hidden')?> preconditionBox'><?php echo html::textarea("precondition[$i]", '', "rows='1' class='form-control autosize'")?></td>
      <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?> keywordsBox'><?php echo html::input("keywords[$i]", '', "class='form-control'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'stage', ' hidden')?> stageBox' style='overflow:visible'><?php echo html::select("stage[$i][]", $lang->testcase->stageList, '', "class='form-control chosen' multiple");?></td>
      <td class='<?php echo zget($visibleFields, 'review', 'hidden')?> reviewBox'><?php echo html::select("needReview[$i]", $lang->testcase->reviewList, $needReview, "class='form-control chosen'");?></td>
      <?php
      $this->loadModel('flow');
      foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->flow->getFieldControl($extendField, '', $extendField->field . "[$i]") . "</td>";
      ?>
      <td class='c-actions text-left'>
        <a href='javascript:;' onclick='addRow(this)' class='btn btn-link'><i class='icon-plus'></i></a>
        <a href='javascript:;' onclick='deleteRow(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
      </td>
    </tr>
  </table>
</div>
<?php include '../../common/view/pastetext.html.php';?>
<?php include '../../common/view/footer.html.php';?>
