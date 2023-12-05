<?php
/**
 * The batch edit view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php
$dittoNotice = sprintf($this->lang->task->dittoNotice, $lang->executionCommon);
js::set('dittoNotice', $dittoNotice);
js::set('showFields', $showFields);
js::set('requiredFields', $config->task->edit->requiredFields);
?>
<div id='mainContent' class='main-content fade'>
  <div class='main-header'>
    <h2>
      <?php echo $lang->task->common . $lang->colon . $lang->task->batchEdit;?>
      <?php if($executionName and $execution->multiple):?>
      <small class='text-muted'><?php echo $lang->task->execution . $lang->colon . ' ' . $executionName;?></small>
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
  <form id='batchEditForm' class='main-form' method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "executionID={$executionID}")?>">
    <div class="table-responsive">
      <table class='table table-form table-fixed with-border'>
        <thead>
          <tr>
            <th class='c-id'><?php echo $lang->idAB;?></th>
            <th class='required <?php if(count($visibleFields) > 10) echo 'c-name';?>'><?php echo $lang->task->name?></th>
            <th class='c-module<?php echo zget($visibleFields,    'module',       ' hidden') . zget($requiredFields, 'module', '', ' required');?>'><?php echo $lang->task->module?></th>
            <th class='c-assigned<?php echo zget($visibleFields,  'assignedTo',   ' hidden') . zget($requiredFields, 'assignedTo', '', ' required');?>'><?php echo $lang->task->assignedTo;?></th>
            <th class='c-type required'><?php echo $lang->task->type; ?></th>
            <th class='c-status<?php echo zget($visibleFields,    'status',       ' hidden') . zget($requiredFields, 'status', '', ' required');?>'><?php echo $lang->task->status;?></th>
            <th class='c-date<?php  echo zget($visibleFields,     'estStarted',   ' hidden') . zget($requiredFields, 'estStarted', '', ' required');?>'><?php echo $lang->task->estStarted?></th>
            <th class='c-date<?php  echo zget($visibleFields,     'deadline',     ' hidden') . zget($requiredFields, 'deadline', '', ' required');?>'><?php echo $lang->task->deadline?></th>
            <th class='c-pri<?php  echo zget($visibleFields,      'pri',          ' hidden') . zget($requiredFields, 'pri', '', ' required');?>'><?php echo $lang->task->pri;?></th>
            <th class='c-estimate<?php  echo zget($visibleFields, 'estimate',     ' hidden') . zget($requiredFields, 'estimate',     '', ' required');?>'><?php echo $lang->task->estimateAB;?></th>
            <th class='c-record<?php  echo zget($visibleFields,   'record',       ' hidden') . zget($requiredFields, 'record',       '', ' required');?>'><?php echo $lang->task->consumedThisTime;?></th>
            <th class='c-left<?php  echo zget($visibleFields,     'left',         ' hidden') . zget($requiredFields, 'left',         '', ' required');?>'><?php echo $lang->task->leftAB?></th>
            <th class='c-user<?php echo zget($visibleFields,      'finishedBy',   ' hidden') . zget($requiredFields, 'finishedBy',   '', ' required');?>'><?php echo $lang->task->finishedBy;?></th>
            <th class='c-user<?php echo zget($visibleFields,      'canceledBy',   ' hidden') . zget($requiredFields, 'canceledBy',   '', ' required');?>'><?php echo $lang->task->canceledBy;?></th>
            <th class='c-user<?php echo zget($visibleFields,      'closedBy',     ' hidden') . zget($requiredFields, 'closedBy',     '', ' required');?>'><?php echo $lang->task->closedBy;?></th>
            <th class='c-reason<?php echo zget($visibleFields,    'closedReason', ' hidden') . zget($requiredFields, 'closedReason', '', ' required');?>'><?php echo $lang->task->closedReason;?></th>
            <?php
            $extendFields = $this->task->getFlowExtendFields();
            foreach($extendFields as $extendField) echo "<th class='c-extend'>{$extendField->name}</th>";
            ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($tasks as $taskID => $task):?>
          <?php
          if(!isset($execution))
          {
              $prjInfo = $this->execution->getById($task->execution);
              $modules = $this->tree->getTaskOptionMenu($task->execution, 0, 'allModule');
              foreach($modules as $moduleID => $moduleName) $modules[$moduleID] = $prjInfo->name. $moduleName;
              $modules = array('ditto' => $this->lang->task->ditto) + $modules;
          }
          ?>
          <tr>
            <?php $disableAssignedTo = (isset($teams[$taskID]) and (($task->assignedTo != $this->app->user->account and $task->mode == 'linear') or !isset($teams[$taskID][$app->user->account]))) ? "disabled='disabled'" : '';?>
            <?php $disableHour = (isset($teams[$taskID]) or $task->parent < 0) ? "disabled='disabled'" : '';?>
            <?php
            $members      = array('' => '', 'ditto' => $this->lang->task->ditto);
            $teamAccounts = !empty($executionTeams[$task->execution]) ? array_column($executionTeams[$task->execution], 'account') : array();
            foreach($teamAccounts as $teamAccount) $members[$teamAccount] = zget($users, $teamAccount);

            $taskMembers = array();
            if(isset($teams[$taskID]))
            {
                $teamAccounts = $teams[$taskID];
                foreach($teamAccounts as $teamAccount) $taskMembers[$teamAccount->account] = $users[$teamAccount->account];
            }
            else
            {
                if($task->status == 'closed') $members['closed'] = 'Closed';
                $taskMembers = $members;
            }

            if($task->assignedTo and !isset($taskMembers[$task->assignedTo]))
            {
                $taskMembers[$task->assignedTo] = $users[$task->assignedTo];
            }
            ?>
            <td><?php echo $taskID . html::hidden("taskIDList[$taskID]", $taskID);?></td>
            <td style='overflow:visible' title='<?php echo $task->name?>'>
              <div class="input-control has-icon-right">
                <?php echo html::input("name[$taskID]", $task->name, "class='form-control'");?>
                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix">
                    <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                  </ul>
                  <?php echo html::hidden("color[$taskID]", $task->color, "data-provide='colorpicker' data-wrapper='input-control-icon-right' data-icon='color' data-btn-tip='{$lang->task->colorTag}' data-update-text='#name\\[{$taskID}\\]'");?>
                </div>
              </div>
            </td>
            <td class='text-left<?php echo zget($visibleFields, 'module', ' hidden')?>' style='overflow:visible'><?php echo html::select("module[$taskID]", $modules, $task->module, "class='form-control picker-select' data-drop-width='auto'")?></td>
            <td class='text-left<?php echo zget($visibleFields, 'assignedTo', ' hidden')?>' style='overflow:visible'><?php echo html::select("assignedTo[$taskID]", $taskMembers, $task->assignedTo, "class='form-control picker-select' data-drop-width='auto' {$disableAssignedTo}");?></td>
            <td><?php echo html::select("type[$taskID]",    $lang->task->typeList, $task->type, "class='form-control'");?></td>
            <td <?php echo zget($visibleFields, 'status',     "class='hidden'")?>><?php echo html::select("status[$taskID]", $lang->task->statusList, $task->status, "class='form-control' {$disableHour}");?></td>
            <td <?php echo zget($visibleFields, 'estStarted', "class='hidden'")?>><?php echo html::input("estStarted[$taskID]", helper::isZeroDate($task->estStarted) ? '' : $task->estStarted, "class='form-control text-center form-date'");?></td>
            <td <?php echo zget($visibleFields, 'deadline',   "class='hidden'")?>><?php echo html::input("deadline[$taskID]", helper::isZeroDate($task->deadline) ? '' : $task->deadline, "class='form-control text-center form-date'");?></td>
            <td <?php echo zget($visibleFields, 'pri',        "class='hidden'")?>><?php echo html::select("pri[$taskID]", $lang->task->priList, $task->pri, "class='form-control'");?></td>
            <td <?php echo zget($visibleFields, 'estimate',   "class='hidden'")?>><?php echo html::input("estimate[$taskID]", $task->estimate, "class='form-control text-center' {$disableHour}");?></td>
            <td <?php echo zget($visibleFields, 'record',     "class='hidden'")?>><?php echo html::input("consumed[$taskID]", 0, "class='form-control text-center' {$disableHour}");?></td>
            <td <?php echo zget($visibleFields, 'left',       "class='hidden'")?>><?php echo html::input("left[$taskID]", $task->left, "class='form-control text-center' {$disableHour}");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'finishedBy', ' hidden')?>' style='overflow:visible'><?php echo html::select("finishedBy[$taskID]", $members, $task->finishedBy, "class='form-control picker-select' data-drop-width='auto'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'canceledBy', ' hidden')?>' style='overflow:visible'><?php echo html::select("canceledBy[$taskID]", $members, $task->canceledBy, "class='form-control picker-select' data-drop-width='auto'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'closedBy', ' hidden')?>' style='overflow:visible'><?php echo html::select("closedBy[$taskID]",   $members, $task->closedBy, "class='form-control picker-select' data-drop-width='auto'");?></td>
            <td <?php echo zget($visibleFields, 'closedReason', "class='hidden'")?>>
              <?php
              $reasonList[''] = '';
              $closedReason   = $task->closedReason;
              if(!empty($closedReason))
              {
                  $reasonList[$closedReason] = $lang->task->reasonList[$closedReason];
              }
              else
              {
                  $status = $task->status;
                  if($status == 'done' or $status == 'cancel')
                  {
                      $reasonList[$status] = $lang->task->reasonList[$status];
                  }
                  else
                  {
                      $reasonList = $lang->task->reasonList;
                  }
              }

              echo html::select("closedReason[$taskID]", $reasonList, $closedReason, 'class=form-control');
              ?>
            </td>
            <?php foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->loadModel('flow')->getFieldControl($extendField, $task, $extendField->field . "[{$taskID}]") . "</td>";?>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='<?php echo count($visibleFields) + 3;?>' class='text-center form-actions'>
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
