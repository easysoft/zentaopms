<?php
/**
 * The batch create view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="main-header clearfix">
    <h2 class="pull-left">
      <?php if($parent):?>
      <span class='pull-left'><?php echo $parentTitle . $this->lang->colon;?></span> 
      <?php echo $lang->task->batchCreateChildren;?>
      <?php else:?>
      <?php echo $lang->task->batchCreate;?>
      <?php endif;?>
    </h2>
    <?php if($project->type != 'ops' && $config->global->flow != 'onlyTask'):?>
    <a class="checkbox-primary pull-left" id='zeroTaskStory' href='javascript:toggleZeroTaskStory();'>
      <label><?php echo $lang->story->zeroTask;?></label>
    </a>
    <?php endif;?>
    <div class="pull-right btn-toolbar">
      <button type='button' data-toggle='modal' data-target="#importLinesModal" class="btn btn-primary"><?php echo $lang->pasteText;?></button>
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=task&section=custom&key=batchCreateFields')?>
      <?php include '../../common/view/customfield.html.php';?>
    </div>
  </div>
  <?php
  $visibleFields  = array();
  $requiredFields = array();
  foreach(explode(',', $showFields) as $field)
  {
      if($field)$visibleFields[$field] = '';
  }
  foreach(explode(',', $config->task->create->requiredFields) as $field)
  {
      if($field)
      {
          $requiredFields[$field] = '';
          if(strpos(",{$config->task->customBatchCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
      }
  }
  $colspan     = count($visibleFields) + 3;
  $hiddenStory = ((isonlybody() and $storyID) || $config->global->flow == 'onlyTask') ? ' hidden' : '';
  if($hiddenStory and isset($visibleFields['story'])) $colspan -= 1;
  ?>
  <form method='post' class='load-indicator batch-actions-form form-ajax' enctype='multipart/form-data' id="batchCreateForm">
    <div class="table-responsive">
      <table class="table table-form" id="tableBody">
        <thead>
          <tr>
            <th class='w-30px'><?php echo $lang->idAB;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'module', ' hidden') . zget($requiredFields, 'module', '', ' required');?>'><?php echo $lang->task->module?></th>
            <?php if($project->type != 'ops'):?>
            <th class='w-200px<?php echo zget($visibleFields, 'story', ' hidden') . zget($requiredFields, 'story', '', ' required'); echo $hiddenStory;?>'><?php echo $lang->task->story;?></th>
            <?php endif;?>
            <th class='c-name required has-btn'><?php echo $lang->task->name;?></span></th>
            <th class='w-80px required'><?php echo $lang->typeAB;?></span></th>
            <th class='w-130px<?php echo zget($visibleFields, 'assignedTo', ' hidden') . zget($requiredFields, 'assignedTo', '', ' required');?>'><?php echo $lang->task->assignedTo;?></th>
            <th class='w-60px<?php  echo zget($visibleFields, 'estimate',   ' hidden') . zget($requiredFields, 'estimate',   '', ' required');?>'><?php echo $lang->task->estimateAB;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'estStarted', ' hidden') . zget($requiredFields, 'estStarted', '', ' required');?>'><?php echo $lang->task->estStarted;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'deadline',   ' hidden') . zget($requiredFields, 'deadline',   '', ' required');?>'><?php echo $lang->task->deadline;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'desc',       ' hidden') . zget($requiredFields, 'desc',       '', ' required');?>'><?php echo $lang->task->desc;?></th>
            <th class='w-80px<?php  echo zget($visibleFields, 'pri',        ' hidden') . zget($requiredFields, 'pri',        '', ' required');?>'><?php echo $lang->task->pri;?></th>
            <?php
            $extendFields = $this->task->getFlowExtendFields();
            foreach($extendFields as $extendField) echo "<th class='w-100px'>{$extendField->name}</th>";
            ?>
          </tr>
        </thead>
        <tbody>
          <?php
          $stories['ditto'] = $lang->task->ditto;
          $lang->task->typeList['ditto'] = $lang->task->ditto;
          $members['ditto'] = $lang->task->ditto;
          $modules['ditto'] = $lang->task->ditto;
          if($project->type == 'ops') $colspan = $colspan - 1;
          ?>
          <?php for($i = 0; $i < $config->task->batchCreate; $i++):?>
          <?php
          if($i == 0)
          {
              $currentStory = $storyID;
              $type         = '';
              $member       = '';
              $module       = $story ? $story->module : $moduleID;
          }
          else
          {
              $currentStory = $type = $member = $module = 'ditto';
          }
          ?>
          <?php $pri = 3;?>
          <tr>
            <td class='text-center'><?php echo $i + 1;?></td>
            <td <?php echo zget($visibleFields, 'module', "class='hidden'")?> style='overflow:visible'>
              <?php echo html::select("module[$i]", $modules, $module, "class='form-control chosen' onchange='setStories(this.value, $project->id, $i)'")?>
              <?php echo html::hidden("parent[$i]", $parent);?>
            </td>
            <?php if($project->type != 'ops'):?>
            <td <?php echo zget($visibleFields, 'story', "class='hidden'"); echo $hiddenStory;?> style='overflow: visible'>
              <div class='input-group'>
                <?php echo html::select("story[$i]", $stories, $currentStory, "class='form-control chosen' onchange='setStoryRelated($i)'");?>
                <span class='input-group-btn'>
                  <a id='preview<?php echo $i;?>' href='#' class='btn iframe btn-link btn-icon btn-copy' disabled='disabled' title='<?php echo $lang->preview; ?>'><i class='icon-search'></i></a>
                  <a href='javascript:copyStoryTitle(<?php echo $i;?>)' class='btn btn-link btn-icon btn-copy' title='<?php echo $lang->task->copyStoryTitle; ?>'><i class='icon-arrow-right'></i></a>
                  <?php echo html::hidden("storyEstimate$i") . html::hidden("storyDesc$i") . html::hidden("storyPri$i");?>
                </span>
              </div>
            </td>
            <?php endif;?>
            <td style='overflow:visible'>
              <div class="input-control has-icon-right">
                <?php echo html::input("name[$i]", '', "class='form-control title-import'");?>
                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix pull-right">
                    <li class="heading"><?php echo $lang->task->colorTag;?><i class="icon icon-close"></i></li>
                  </ul>
                  <?php echo html::hidden("color[$i]", '', "data-provide='colorpicker' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#name\\[$i\\]'");?>
                </div>
              </div>
            </td>
            <td><?php echo html::select("type[$i]", $lang->task->typeList, $type, 'class=form-control');?></td>
            <td <?php echo zget($visibleFields, 'assignedTo', "class='hidden'")?> style='overflow:visible'><?php echo html::select("assignedTo[$i]", $members, $member, "class='form-control chosen'");?></td>
            <td <?php echo zget($visibleFields, 'estimate', "class='hidden'")?>><?php echo html::input("estimate[$i]", '', "class='form-control text-center'");?></td>
            <td <?php echo zget($visibleFields, 'estStarted', "class='hidden'")?>><?php echo html::input("estStarted[$i]", '', "class='form-control text-center form-date'");?></td>
            <td <?php echo zget($visibleFields, 'deadline', "class='hidden'")?>><?php echo html::input("deadline[$i]", '', "class='form-control text-center form-date'");?></td>
            <td <?php echo zget($visibleFields, 'desc', "class='hidden'")?>><?php echo html::textarea("desc[$i]", '', "rows='1' class='form-control autosize'");?></td>
            <td <?php echo zget($visibleFields, 'pri', "class='hidden'")?>><?php echo html::select("pri[$i]", (array)$lang->task->priList, $pri, 'class=form-control');?></td>
            <?php foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->loadModel('flow')->getFieldControl($extendField, '', $extendField->field . "[$i]") . "</td>";?>
          </tr>
          <?php endfor;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='<?php echo $colspan?>' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<table class='template' id='trTemp'>
  <tbody>
    <tr>
      <td class='text-center'>%s</td>
      <td <?php echo zget($visibleFields, 'module', "class='hidden'")?> style='overflow:visible'>
        <?php echo html::select("module[%s]", $modules, $module, "class='form-control chosen' onchange='setStories(this.value, $project->id, \"%s\")'")?>
        <?php echo html::hidden("parent[%s]", $parent);?>
      </td>
      <td <?php echo zget($visibleFields, 'story', "class='hidden'"); echo $hiddenStory;?> style='overflow: visible'>
        <div class='input-group'>
          <?php echo html::select("story[%s]", $stories, $currentStory, "class='form-control chosen' onchange='setStoryRelated(\"%s\")'");?>
          <span class='input-group-btn'>
            <a id="preview%s" href='#' class='btn iframe btn-link btn-icon btn-copy' disabled='disabled' title='<?php echo $lang->preview; ?>'><i class='icon-search'></i></a>
            <a href='javascript:copyStoryTitle("%s")' class='btn btn-link btn-icon btn-copy' title='<?php echo $lang->task->copyStoryTitle; ?>'><i class='icon-arrow-right'></i></a>
          </span>
        </div>
      </td>
      <td style='overflow:visible'>
        <div class="input-control has-icon-right">
          <?php echo html::input("name[%s]", '', "class='form-control title-import'");?>
          <div class="colorpicker">
            <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
            <ul class="dropdown-menu clearfix pull-right">
              <li class="heading"><?php echo $lang->task->colorTag;?><i class="icon icon-close"></i></li>
            </ul>
            <?php echo html::hidden("color[%s]", '', "data-provide='colorpicker-later' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#name\\[%s\\]'");?>
          </div>
        </div>
        </div>
      </td>
      <td><?php echo html::select("type[%s]", $lang->task->typeList, $type, 'class="form-control"');?></td>
      <td <?php echo zget($visibleFields, 'assignedTo', "class='hidden'")?> style='overflow:visible'><?php echo html::select("assignedTo[%s]", $members, $member, "class='form-control chosen'");?></td>
      <td <?php echo zget($visibleFields, 'estimate', "class='hidden'")?>><?php echo html::input("estimate[%s]", '', "class='form-control text-center'");?></td>
      <td <?php echo zget($visibleFields, 'estStarted', "class='hidden'")?>><?php echo html::input("estStarted[%s]", '', "class='form-control text-center form-date'");?></td>
      <td <?php echo zget($visibleFields, 'deadline', "class='hidden'")?>><?php echo html::input("deadline[%s]", '', "class='form-control text-center form-date'");?></td>
      <td <?php echo zget($visibleFields, 'desc', "class='hidden'")?>><?php echo html::textarea("desc[%s]", '', "rows='1' class='form-control autosize'");?></td>
      <td <?php echo zget($visibleFields, 'pri', "class='hidden'")?>><?php echo html::select("pri[%s]", (array)$lang->task->priList, $pri, 'class=form-control');?></td>
      <?php foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->loadModel('flow')->getFieldControl($extendField, '', $extendField->field . "[%s]") . "</td>";?>
    </tr>
  </tbody>
</table>
<?php js::set('projectType', $project->type);?>
<?php js::set('storyTasks', $storyTasks);?>
<?php js::set('mainField', 'name');?>
<?php js::set('ditto', $lang->task->ditto);?>
<?php js::set('storyID', $storyID);?>
<?php include '../../common/view/pastetext.html.php';?>
<?php include '../../common/view/footer.html.php';?>
