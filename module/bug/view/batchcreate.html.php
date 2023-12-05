<?php
/**
 * The batch create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
js::set('requiredFields', $config->bug->create->requiredFields);
js::set('productID', $productID);
js::set('released', $lang->build->released);
?>
<?php
$visibleFields  = array();
$requiredFields = array();
foreach(explode(',', $showFields) as $field)
{
    if($field) $visibleFields[$field] = '';
}

foreach(explode(',', $config->bug->create->requiredFields) as $field)
{
    if($field)
    {
        $requiredFields[$field] = '';
        if(strpos(",{$config->bug->list->customBatchCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
    }
}
?>
<?php js::set('showFields', $showFields);?>
<div id='mainContent' class='main-content fade'>
  <div class='main-header'>
    <h2>
      <?php echo $lang->bug->batchCreate;?>
    </h2>
    <div class="pull-right btn-toolbar">
      <?php if(common::hasPriv('file', 'uploadImages')) echo html::a($this->createLink('file', 'uploadImages', 'module=bug&params=' . helper::safe64Encode("productID=$productID&branch=$branch&executionID=$executionID&moduleID=$moduleID")), $lang->uploadImages, '', "data-toggle='modal' data-type='iframe' class='btn btn-primary' data-width='70%'")?>
      <button type='button' data-toggle='modal' data-target="#importLinesModal" class="btn btn-primary"><?php echo $lang->pasteText;?></button>
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=bug&section=custom&key=batchCreateFields')?>
      <?php include '../../common/view/customfield.html.php';?>
      <?php if(isonlybody()):?>
      <div class="divider"></div>
      <button id="closeModal" type="button" class="btn btn-link" data-dismiss="modal"><i class="icon icon-close"></i></button>
      <?php endif;?>
    </div>
  </div>
  <form class='main-form' method='post' target='hiddenwin' id='batchCreateForm'>
    <div class="table-responsive">
      <table class='table table-form'>
        <thead>
          <tr>
            <th class='c-id'><?php echo $lang->idAB;?></th>
            <th class='c-branch<?php echo zget($visibleFields, $product->type, ' hidden')?> branchBox'> <?php echo $lang->product->branch;?></th>
            <th class='c-module<?php echo zget($requiredFields, 'module', '', ' required');?>'> <?php echo $lang->bug->module;?></th>
            <th class='c-project<?php echo zget($visibleFields, 'project', ' hidden') . zget($requiredFields, 'project', '', ' required');?> projectBox'><?php echo $lang->bug->project;?></th>
            <th class='c-execution<?php echo zget($visibleFields, 'execution', ' hidden') . zget($requiredFields, 'execution', '', ' required');?> executionBox'><?php echo (isset($project->model) and $project->model == 'kanban') ? $lang->bug->kanban : $lang->bug->execution;?></th>
            <th class='c-build required'><?php echo $lang->bug->openedBuild;?></th>
            <th class='c-title required'><?php echo $lang->bug->title;?></th>
            <?php if(isset($executionType) and $executionType == 'kanban'):?>
            <th class='c-execution'><?php echo $lang->kanbancard->region;?></th>
            <th class='c-execution'><?php echo $lang->kanbancard->lane;?></th>
            <?php endif;?>
            <th class='c-date<?php echo zget($visibleFields, 'deadline', ' hidden') . zget($requiredFields, 'deadline', '', ' required');?> deadlineBox'><?php echo $lang->bug->deadline;?></th>
            <th class='c-steps<?php echo zget($visibleFields, 'steps', ' hidden') . zget($requiredFields, 'steps', '', ' required');?> stepsBox'><?php echo $lang->bug->steps;?></th>
            <th class='c-type<?php echo zget($visibleFields, 'type', ' hidden') . zget($requiredFields, 'type', '', ' required');?> typeBox'><?php echo $lang->typeAB;?></th>
            <th class='c-pri<?php echo zget($visibleFields, 'pri', ' hidden') . zget($requiredFields, 'pri', '', ' required');?> priBox'><?php echo $lang->bug->pri;?></th>
            <th class='c-severity<?php echo zget($visibleFields, 'severity', ' hidden') . zget($requiredFields, 'severity', '', ' required');?> severityBox'><?php echo $lang->bug->severity;?></th>
            <th class='c-os<?php echo zget($visibleFields, 'os', ' hidden') . zget($requiredFields, 'os', '', ' required');?> osBox'><?php echo $lang->bug->os;?></th>
            <th class='c-browser<?php echo zget($visibleFields, 'browser', ' hidden') . zget($requiredFields, 'browser', '', ' required');?> browserBox'><?php echo $lang->bug->browser;?></th>
            <th class='c-keywords<?php echo zget($visibleFields, 'keywords', ' hidden') . zget($requiredFields, 'keywords', '', ' required');?> keywordsBox'><?php echo $lang->bug->keywords;?></th>
            <?php
            $extendFields = $this->bug->getFlowExtendFields();
            foreach($extendFields as $extendField)
            {
                $required = strpos(",$extendField->rules,", ',1,') !== false ? 'required' : '';
                echo "<th class='c-extend $required'>{$extendField->name}</th>";
            }
            ?>
            <th class='c-actions'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $moduleOptionMenu       += array('ditto' => $lang->bug->ditto);
          $projects               += array('ditto' => $lang->bug->ditto);
          $executions             += array('ditto' => $lang->bug->ditto);
          $lang->bug->typeList    += array('ditto' => $lang->bug->ditto);
          $lang->bug->priList     += array('ditto' => $lang->bug->ditto);
          ?>
          <?php $i = 1; ?>
          <?php if(!empty($titles)):?>
          <?php foreach($titles as $bugTitle => $fileName):?>
          <?php
          $moduleID    = $i == 1 ? $moduleID : 'ditto';
          $projectID   = $i == 1 ? $projectID : 'ditto';
          $executionID = $i == 1 ? $executionID : 'ditto';
          $type        = $i == 1 ? '' : 'ditto';
          $pri         = $i == 1 ? 0  : 'ditto';
          ?>
          <tr>
            <td class='text-left'><?php echo $i;?></td>
            <td class='<?php echo zget($visibleFields, $product->type, ' hidden')?> branchBox' style='overflow:visible'><?php echo html::select("branches[$i]", $branches, $branch, "class='form-control chosen' onchange='setBranchRelated(this.value, $productID, $i)'");?></td>
            <td><?php echo html::select("modules[$i]", $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></td>
            <td class='<?php echo zget($visibleFields, 'project', ' hidden')?> projectBox' style='overflow:visible'><?php echo html::select("projects[$i]", $projects, $projectID, "class='form-control chosen' onchange='loadProductExecutionsByProject($productID, this.value, $i)'");?></td>
            <td class='<?php echo zget($visibleFields, 'execution', ' hidden')?> executionBox' style='overflow:visible'><?php echo html::select("executions[$i]", $executions, $executionID, "class='form-control chosen' onchange='loadExecutionBuilds($productID, this.value, $i)'");?></td>
            <td id='buildBox<?php echo $i;?>'><?php echo html::select("openedBuilds[$i][]", $builds, 'trunk', "class='form-control picker-select' multiple");?></td>
            <td>
              <div class='input-group'>
                <div class="input-control has-icon-right">
                  <?php echo html::input("title[$i]", $bugTitle, "class='form-control title-import'") . html::hidden("uploadImage[$i]", $fileName);?>
                  <div class="colorpicker">
                    <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                    <ul class="dropdown-menu clearfix">
                      <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                    </ul>
                    <?php echo html::hidden("color[$i]", '', "data-provide='colorpicker' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#title\\[{$i}\\]'");?>
                  </div>
                </div>
              </div>
            </td>
            <?php if(isset($executionType) and $executionType == 'kanban'):?>
            <td><?php echo html::select("regions[$i]", $regionPairs, $regionID, "class='form-control chosen'");?></td>
            <td><?php echo html::select("lanes[$i]", $lanePairs, $laneID, "class='form-control chosen'");?></td>
            <?php endif;?>
            <td class='<?php echo zget($visibleFields, 'deadline', 'hidden')?> deadlineBox'><?php echo html::input("deadlines[$i]", '', "class='form-control form-date'");?></td>
            <td class='<?php echo zget($visibleFields, 'steps', 'hidden')?> stepsBox'><?php echo html::textarea("stepses[$i]", '', "rows='1' class='form-control autosize'");?></td>
            <td class='<?php echo zget($visibleFields, 'type', 'hidden')?> typeBox' style='overflow:visible'>    <?php echo html::select("types[$i]", $lang->bug->typeList, $type, "class='form-control chosen'");?></td>
            <td class='<?php echo zget($visibleFields, 'pri', 'hidden')?> priBox' style='overflow:visible'>     <?php echo html::select("pris[$i]", $lang->bug->priList, $pri, "class='form-control'");?></td>
            <td class='<?php echo zget($visibleFields, 'severity', 'hidden')?> severityBox' style='overflow:visible'><?php echo html::select("severities[$i]", $lang->bug->severityList, '3', "class='form-control'");?></td>
            <td class='<?php echo zget($visibleFields, 'os', 'hidden')?> osBox' style='overflow:visible'>      <?php echo html::select("oses[$i][]", $lang->bug->osList, '', "class='form-control chosen' multiple");?></td>
            <td class='<?php echo zget($visibleFields, 'browser', 'hidden')?> browserBox' style='overflow:visible'> <?php echo html::select("browsers[$i][]", $lang->bug->browserList, '', "class='form-control chosen' multiple");?></td>
            <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?> keywordsBox'><?php echo html::input("keywords[$i]", '', "class='form-control'");?></td>
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
          <?php $i++;?>
          <?php endforeach;?>
          <?php endif;?>
          <?php $nextStart = $i;?>
          <?php for($i = $nextStart; $i <= $config->bug->batchCreate; $i++):?>
          <?php
          $moduleID    = $i - $nextStart == 0 ? $moduleID : 'ditto';
          $projectID   = $i - $nextStart == 0 ? $projectID : 'ditto';
          $executionID = $i - $nextStart == 0 ? $executionID : 'ditto';
          $type        = $i - $nextStart == 0 ? '' : 'ditto';
          $pri         = $i - $nextStart == 0 ? 0  : 'ditto';
          ?>
          <tr>
            <td><?php echo $i;?></td>
            <td class='<?php echo zget($visibleFields, $product->type, ' hidden')?> branchBox' style='overflow:visible'><?php echo html::select("branches[$i]", $branches, $branch, "class='form-control chosen' onchange='setBranchRelated(this.value, $productID, $i)'");?></td>
            <td><?php echo html::select("modules[$i]", $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></td>
            <td class='<?php echo zget($visibleFields, 'project', ' hidden')?> projectBox' style='overflow:visible'><?php echo html::select("projects[$i]", $projects, $projectID, "class='form-control chosen' onchange = 'loadProductExecutionsByProject($productID, this.value, $i)'");?></td>
            <td class='<?php echo zget($visibleFields, 'execution', ' hidden')?> executionBox' style='overflow:visible'><?php echo html::select("executions[$i]", $executions, $executionID, "class='form-control chosen' onchange='loadExecutionBuilds($productID, this.value, $i)'");?></td>
            <td id='buildBox<?php echo $i;?>'><?php echo html::select("openedBuilds[$i][]", $builds, '', "class='form-control picker-select' multiple");?></td>
            <td>
              <div class='input-group'>
                <div class="input-control has-icon-right">
                  <?php echo html::input("title[$i]", '', "class='form-control title-import'");?>
                  <div class="colorpicker">
                    <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                    <ul class="dropdown-menu clearfix">
                      <li class="heading"><?php echo $lang->bug->colorTag;?><i class="icon icon-close"></i></li>
                    </ul>
                    <?php echo html::hidden("color[$i]", '', "data-provide='colorpicker' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#title\\[$i\\]'");?>
                  </div>
                </div>
              </div>
            </td>
            <?php if(isset($executionType) and $executionType == 'kanban'):?>
            <td><?php echo html::select("regions[$i]", $regionPairs, $regionID, "class='form-control chosen' onchange='setLane(this.value, $i)'");?></td>
            <td><?php echo html::select("lanes[$i]", $lanePairs, $laneID, "class='form-control chosen'");?></td>
            <?php endif;?>
            <td class='<?php echo zget($visibleFields, 'deadline', 'hidden')?> deadlineBox'><?php echo html::input("deadlines[$i]", '', "class='form-control form-date'");?></td>
            <td class='<?php echo zget($visibleFields, 'steps', 'hidden')?> stepsBox'><?php echo html::textarea("stepses[$i]", '', "rows='1' class='form-control autosize'");?></td>
            <td class='<?php echo zget($visibleFields, 'type', 'hidden')?> typeBox' style='overflow:visible'>    <?php echo html::select("types[$i]", $lang->bug->typeList, $type, "class='form-control chosen'");?></td>
            <td class='<?php echo zget($visibleFields, 'pri', 'hidden')?> priBox' style='overflow:visible'>     <?php echo html::select("pris[$i]", $lang->bug->priList, $pri, "class='form-control'");?></td>
            <td class='<?php echo zget($visibleFields, 'severity', 'hidden')?> severityBox' style='overflow:visible'><?php echo html::select("severities[$i]", $lang->bug->severityList, '3', "class='form-control'");?></td>
            <td class='<?php echo zget($visibleFields, 'os', 'hidden')?> osBox' style='overflow:visible'>      <?php echo html::select("oses[$i][]", $lang->bug->osList, '', "class='form-control chosen' multiple");?></td>
            <td class='<?php echo zget($visibleFields, 'browser', 'hidden')?> browserBox' style='overflow:visible'> <?php echo html::select("browsers[$i][]", $lang->bug->browserList, '', "class='form-control chosen' multiple");?></td>
            <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?> keywordsBox'><?php echo html::input("keywords[$i]", '', "class='form-control'");?></td>
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
            <td colspan='<?php echo count($visibleFields) + 4?>' class='text-center form-actions'>
              <?php echo html::submitButton();?>
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
      <td>%s</td>
      <td class='<?php echo zget($visibleFields, $product->type, ' hidden')?> productBox' style='overflow:visible'><?php echo html::select("branches[%s]", $branches, $branch, "class='form-control chosen' onchange='setBranchRelated(this.value, $productID, \"%s\")'");?></td>
      <td><?php echo html::select("modules[%s]", $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></td>
      <td class='<?php echo zget($visibleFields, 'project', ' hidden')?> projectBox' style='overflow:visible'><?php echo html::select("projects[%s]", $projects, $projectID, "class='form-control chosen' onchange = 'loadProductExecutionsByProject($productID, this.value, \"%s\")'");?></td>
      <td class='<?php echo zget($visibleFields, 'execution', ' hidden')?> executionBox' style='overflow:visible'><?php echo html::select("executions[%s]", $executions, $executionID, "class='form-control chosen' onchange='loadExecutionBuilds($productID, this.value, \"%s\")'");?></td>
      <td id='buildBox%s'><?php echo html::select("openedBuilds[%s][]", $builds, '', "class='form-control picker-select' multiple");?></td>
      <td>
        <div class='input-group'>
          <div class="input-control has-icon-right">
            <?php echo html::input("title[%s]", '', "class='form-control title-import'");?>
            <div class="colorpicker">
              <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
              <ul class="dropdown-menu clearfix">
                <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
              </ul>
              <?php echo html::hidden("color[%s]", '', "data-provide='colorpicker-later' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#title\\[%s\\]'");?>
            </div>
          </div>
        </div>
      </td>
      <td class='<?php echo zget($visibleFields, 'deadline', 'hidden')?> deadlineBox'><?php echo html::input("deadlines[%s]", '', "class='form-control form-date'");?></td>
      <td class='<?php echo zget($visibleFields, 'steps', 'hidden')?> stepsBox'><?php echo html::textarea("stepses[%s]", '', "rows='1' class='form-control autosize'");?></td>
      <td class='<?php echo zget($visibleFields, 'type', 'hidden')?> typeBox' style='overflow:visible'>    <?php echo html::select("types[%s]", $lang->bug->typeList, $type, "class='form-control chosen'");?></td>
      <td class='<?php echo zget($visibleFields, 'pri', 'hidden')?> priBox' style='overflow:visible'>     <?php echo html::select("pris[%s]", $lang->bug->priList, $pri, "class='form-control'");?></td>
      <td class='<?php echo zget($visibleFields, 'severity', 'hidden')?> severityBox' style='overflow:visible'><?php echo html::select("severities[%s]", $lang->bug->severityList, '3', "class='form-control'");?></td>
      <td class='<?php echo zget($visibleFields, 'os', 'hidden')?> osBox' style='overflow:visible'>      <?php echo html::select("oses[%s][]", $lang->bug->osList, '', "class='form-control chosen' multiple");?></td>
      <td class='<?php echo zget($visibleFields, 'browser', 'hidden')?> browserBox' style='overflow:visible'> <?php echo html::select("browsers[%s][]", $lang->bug->browserList, '', "class='form-control chosen' multiple");?></td>
      <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?> keywordsBox'><?php echo html::input("keywords[%s]", '', "class='form-control'");?></td>
      <?php
      $this->loadModel('flow');
      foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->flow->getFieldControl($extendField, '', $extendField->field . "[%s]") . "</td>";
      ?>
      <td class='c-actions text-left'>
        <a href='javascript:;' onclick='addRow(this)' class='btn btn-link'><i class='icon-plus'></i></a>
        <a href='javascript:;' onclick='deleteRow(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
      </td>
    </tr>
  </tbody>
</table>
<div>
  <?php $i = '%i%';?>
  <table class='hidden'>
    <tr id='addRow' class='hidden'>
      <td><?php echo $i;?></td>
      <td class='<?php echo zget($visibleFields, $product->type, ' hidden')?> branchBox' style='overflow:visible'><?php echo html::select("branches[$i]", $branches, $branch, "class='form-control chosen' onchange='setBranchRelated(this.value, $productID, $i)'");?></td>
      <td><?php echo html::select("modules[$i]", $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></td>
      <td class='<?php echo zget($visibleFields, 'project', ' hidden')?> projectBox' style='overflow:visible'><?php echo html::select("projects[$i]", $projects, $projectID, "class='form-control chosen' onchange = 'loadProductExecutionsByProject($productID, this.value, $i)'");?></td>
      <td class='<?php echo zget($visibleFields, 'execution', ' hidden')?> executionBox' style='overflow:visible'><?php echo html::select("executions[$i]", $executions, $executionID, "class='form-control chosen' onchange='loadExecutionBuilds($productID, this.value, $i)'");?></td>
      <td id='buildBox<?php echo $i;?>'><?php echo html::select("openedBuilds[$i][]", $builds, '', "class='form-control picker-select' multiple");?></td>
      <td>
        <div class='input-group'>
          <div class="input-control has-icon-right">
            <?php echo html::input("title[$i]", '', "class='form-control title-import'");?>
            <div class="colorpicker">
              <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
              <ul class="dropdown-menu clearfix">
                <li class="heading"><?php echo $lang->bug->colorTag;?><i class="icon icon-close"></i></li>
              </ul>
              <?php echo html::hidden("color[$i]", '', "data-provide='colorpicker' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#title\\[$i\\]'");?>
            </div>
          </div>
        </div>
      </td>
      <?php if(isset($executionType) and $executionType == 'kanban'):?>
      <td><?php echo html::select("regions[$i]", $regionPairs, $regionID, "class='form-control chosen' onchange='setLane(this.value, $i)'");?></td>
      <td><?php echo html::select("lanes[$i]", $lanePairs, $laneID, "class='form-control chosen'");?></td>
      <?php endif;?>
      <td class='<?php echo zget($visibleFields, 'deadline', 'hidden')?> deadlineBox'><?php echo html::input("deadlines[$i]", '', "class='form-control form-date'");?></td>
      <td class='<?php echo zget($visibleFields, 'steps', 'hidden')?> stepsBox'><?php echo html::textarea("stepses[$i]", '', "rows='1' class='form-control autosize'");?></td>
      <td class='<?php echo zget($visibleFields, 'type', 'hidden')?> typeBox' style='overflow:visible'>    <?php echo html::select("types[$i]", $lang->bug->typeList, $type, "class='form-control chosen'");?></td>
      <td class='<?php echo zget($visibleFields, 'pri', 'hidden')?> priBox' style='overflow:visible'>     <?php echo html::select("pris[$i]", $lang->bug->priList, $pri, "class='form-control'");?></td>
      <td class='<?php echo zget($visibleFields, 'severity', 'hidden')?> severityBox' style='overflow:visible'><?php echo html::select("severities[$i]", $lang->bug->severityList, '3', "class='form-control'");?></td>
      <td class='<?php echo zget($visibleFields, 'os', 'hidden')?> osBox' style='overflow:visible'>      <?php echo html::select("oses[$i][]", $lang->bug->osList, '', "class='form-control chosen' multiple");?></td>
      <td class='<?php echo zget($visibleFields, 'browser', 'hidden')?> browserBox' style='overflow:visible'> <?php echo html::select("browsers[$i][]", $lang->bug->browserList, '', "class='form-control chosen' multiple");?></td>
      <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?> keywordsBox'><?php echo html::input("keywords[$i]", '', "class='form-control'");?></td>
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
<?php js::set('branch', $branch)?>
<?php if(isonlybody()):?>
<style>
.body-modal .main-header {padding-right: 0px;}
.btn-toolbar > .dropdown {margin: 0px;}
</style>
<script>
$(function()
{
    parent.$('#triggerModal .modal-content .modal-header .close').hide();
})
</script>
<?php endif;?>
<?php include '../../common/view/pastetext.html.php';?>
<?php include '../../common/view/footer.html.php';?>
