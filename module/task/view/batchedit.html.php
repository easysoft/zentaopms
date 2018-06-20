<?php
/**
 * The batch edit view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('dittoNotice', $this->lang->task->dittoNotice);?>
<div id='mainContent' class='main-content fade'>
  <div class='main-header'>
    <h2>
      <?php echo $lang->task->common . $lang->colon . $lang->task->batchEdit;?>
      <?php if($projectName):?>
      <small class='text-muted'><?php echo html::icon($lang->icons['project']) . ' ' . $lang->task->project . $lang->colon . ' ' . $projectName;?></small>
      <?php endif;?>
    </h2>
    <div class='pull-right btn-toolbar'>
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=task&section=custom&key=batchEditFields')?>
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
  foreach(explode(',', $config->task->edit->requiredFields) as $field)
  {
      if($field)
      {
          $requiredFields[$field] = '';
          if(strpos(",{$config->task->customBatchEditFields},", ",{$field},") !== false) $visibleFields[$field] = '';
      }
  }
  ?>
  <form id='batchEditForm' class='main-form' method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "projectID={$projectID}")?>">
    <div class="table-responsive">
      <table class='table table-form table-fixed with-border'>
        <thead>
          <tr>
            <th class='w-50px'><?php echo $lang->idAB;?></th>
            <th class='required <?php if(count($visibleFields) > 10) echo 'w-150px';?>'><?php echo $lang->task->name?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'module', ' hidden') . zget($requiredFields, 'module', '', ' required');?>'><?php echo $lang->task->module?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'assignedTo', ' hidden') . zget($requiredFields, 'assignedTo', '', ' required');?>'><?php echo $lang->task->assignedTo;?></th>
            <th class='w-80px required'><?php echo $lang->typeAB;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'status',       ' hidden') . zget($requiredFields, 'status',       '', ' required');?>'><?php echo $lang->task->status;?></th>
            <th class='w-70px<?php  echo zget($visibleFields, 'pri',          ' hidden') . zget($requiredFields, 'pri',          '', ' required');?>'><?php echo $lang->task->pri;?></th>
            <th class='w-40px<?php  echo zget($visibleFields, 'estimate',     ' hidden') . zget($requiredFields, 'estimate',     '', ' required');?>'><?php echo $lang->task->estimateAB;?></th>
            <th class='w-50px<?php  echo zget($visibleFields, 'record',       ' hidden') . zget($requiredFields, 'record',       '', ' required');?>'><?php echo $lang->task->consumedThisTime?></th>
            <th class='w-40px<?php  echo zget($visibleFields, 'left',         ' hidden') . zget($requiredFields, 'left',         '', ' required');?>'><?php echo $lang->task->leftAB?></th>
            <th class='w-90px<?php  echo zget($visibleFields, 'estStarted',   ' hidden') . zget($requiredFields, 'estStarted',   '', ' required');?>'><?php echo $lang->task->estStarted?></th>
            <th class='w-90px<?php  echo zget($visibleFields, 'deadline',     ' hidden') . zget($requiredFields, 'deadline',     '', ' required');?>'><?php echo $lang->task->deadline?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'finishedBy',   ' hidden') . zget($requiredFields, 'finishedBy',   '', ' required');?>'><?php echo $lang->task->finishedBy;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'canceledBy',   ' hidden') . zget($requiredFields, 'canceledBy',   '', ' required');?>'><?php echo $lang->task->canceledBy;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'closedBy',     ' hidden') . zget($requiredFields, 'closedBy',     '', ' required');?>'><?php echo $lang->task->closedBy;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'closedReason', ' hidden') . zget($requiredFields, 'closedReason', '', ' required');?>'><?php echo $lang->task->closedReason;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($taskIDList as $taskID):?>
          <?php
          if(!isset($project))
          {
              $prjInfo = $this->project->getById($tasks[$taskID]->project);
              $modules = $this->tree->getOptionMenu($tasks[$taskID]->project, $viewType = 'task');
              foreach($modules as $moduleID => $moduleName) $modules[$moduleID] = '/' . $prjInfo->name. $moduleName;
              $modules = array('ditto' => $this->lang->task->ditto) + $modules;
    
              $members = $this->project->getTeamMemberPairs($tasks[$taskID]->project, 'nodeleted');
              $members = array('' => '', 'ditto' => $this->lang->task->ditto) + $members;
          }
          ?>
          <tr>
            <?php $disableHour = isset($teams[$taskID]) ? "disabled='disabled'" : '';?>
            <td><?php echo $taskID . html::hidden("taskIDList[$taskID]", $taskID);?></td>
            <td style='overflow:visible' title='<?php echo $tasks[$taskID]->name?>'>
              <div class="input-control has-icon-right">
                <?php echo html::input("names[$taskID]", $tasks[$taskID]->name, "class='form-control' autocomplete='off'");?>
                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix">
                    <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                  </ul>
                  <?php echo html::hidden("colors[$taskID]", $tasks[$taskID]->color, "data-provide='colorpicker' data-wrapper='input-control-icon-right' data-icon='color' data-btn-tip='{$lang->task->colorTag}' data-update-text='#names\\[{$taskID}\\]'");?>
                </div>
              </div>
            </td>
            <td class='text-left<?php echo zget($visibleFields, 'module', ' hidden')?>' style='overflow:visible'><?php echo html::select("modules[$taskID]",     $modules, $tasks[$taskID]->module, "class='form-control chosen'")?></td>
            <td class='text-left<?php echo zget($visibleFields, 'assignedTo', ' hidden')?>' style='overflow:visible'><?php echo html::select("assignedTos[$taskID]", $members, $tasks[$taskID]->assignedTo, "class='form-control chosen'");?></td>
            <td><?php echo html::select("types[$taskID]",    $typeList, $tasks[$taskID]->type, "class='form-control'");?></td>
            <td <?php echo zget($visibleFields, 'status',     "class='hidden'")?>><?php echo html::select("statuses[$taskID]", $statusList, $tasks[$taskID]->status, "class='form-control'");?></td>
            <td <?php echo zget($visibleFields, 'pri',        "class='hidden'")?>><?php echo html::select("pris[$taskID]",     $priList, $tasks[$taskID]->pri, "class='form-control'");?></td>
            <td <?php echo zget($visibleFields, 'estimate',   "class='hidden'")?>><?php echo html::input("estimates[$taskID]", $tasks[$taskID]->estimate, "class='form-control text-center' autocomplete='off' {$disableHour}");?></td>
            <td <?php echo zget($visibleFields, 'record',     "class='hidden'")?>><?php echo html::input("consumeds[$taskID]", '', "class='form-control text-center' autocomplete='off' {$disableHour}");?></td>
            <td <?php echo zget($visibleFields, 'left',       "class='hidden'")?>><?php echo html::input("lefts[$taskID]",     $tasks[$taskID]->left, "class='form-control text-center' autocomplete='off' {$disableHour}");?></td>
            <td <?php echo zget($visibleFields, 'estStarted', "class='hidden'")?>><?php echo html::input("estStarteds[$taskID]",     $tasks[$taskID]->estStarted, "class='form-control text-center form-date'");?></td>
            <td <?php echo zget($visibleFields, 'deadline',   "class='hidden'")?>><?php echo html::input("deadlines[$taskID]",     $tasks[$taskID]->deadline, "class='form-control text-center form-date'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'finishedBy', ' hidden')?>' style='overflow:visible'><?php echo html::select("finishedBys[$taskID]", $members, $tasks[$taskID]->finishedBy, "class='form-control chosen'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'canceledBy', ' hidden')?>' style='overflow:visible'><?php echo html::select("canceledBys[$taskID]", $members, $tasks[$taskID]->canceledBy, "class='form-control chosen'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'closedBy', ' hidden')?>' style='overflow:visible'><?php echo html::select("closedBys[$taskID]",   $members, $tasks[$taskID]->closedBy, "class='form-control chosen'");?></td>
            <td <?php echo zget($visibleFields, 'closedReason', "class='hidden'")?>><?php echo html::select("closedReasons[$taskID]", $lang->task->reasonList, $tasks[$taskID]->closedReason, 'class=form-control');?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='<?php echo count($visibleFields) + 3;?>' class='text-center form-actions'>
              <?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?>
              <?php echo html::backButton('', '', 'btn btn-wide');?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
