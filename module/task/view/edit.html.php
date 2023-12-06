<?php
/**
 * The edit view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: edit.html.php 4897 2013-06-26 01:13:16Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('oldStoryID', $task->story);?>
<?php js::set('oldAssignedTo', $task->assignedTo);?>
<?php js::set('oldExecutionID', $task->execution);?>
<?php js::set('oldConsumed', $task->consumed);?>
<?php js::set('taskStatus', $task->status);?>
<?php js::set('currentUser', $app->user->account);?>
<?php js::set('team', $task->members);?>
<?php js::set('members', $members);?>
<?php js::set('page', 'edit');?>
<?php js::set('confirmChangeExecution', $lang->task->confirmChangeExecution);?>
<?php js::set('teamMemberError', $lang->task->error->teamMember);?>
<?php js::set('totalLeftError', sprintf($this->lang->task->error->leftEmptyAB, $this->lang->task->statusList[$task->status]));?>
<?php js::set('estimateNotEmpty', sprintf($lang->error->gt, $lang->task->estimate, '0'))?>
<?php js::set('leftNotEmpty', sprintf($lang->error->gt, $lang->task->left, '0'))?>
<?php js::set('requiredFields', $config->task->edit->requiredFields);?>
<?php
$requiredFields = array();
foreach(explode(',', $config->task->edit->requiredFields) as $field)
{
    if($field) $requiredFields[$field] = '';
}
?>
<div class='main-content' id='mainContent'>
  <form method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $task->id;?></span>
        <?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name, '', "class='task-name'");?>
        <small><?php echo $lang->arrow . $lang->task->edit;?></small>
      </h2>
    </div>
    <div class='main-row'>
      <div class='main-col col-8'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->task->name;?></div>
            <div class='detail-content'>
              <div class='form-group'>
                <div class="input-control has-icon-right">
                  <div class="colorpicker">
                    <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" title="<?php echo $lang->task->colorTag ?>"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                    <ul class="dropdown-menu clearfix">
                      <li class="heading"><?php echo $lang->task->colorTag;?><i class="icon icon-close"></i></li>
                    </ul>
                    <input type="hidden" class="colorpicker" id="color" name="color" value="<?php echo $task->color ?>" data-icon="color" data-wrapper="input-control-icon-right" data-update-color=".task-name"  data-provide="colorpicker">
                  </div>
                  <?php echo html::input('name', $task->name, 'class="form-control task-name" placeholder="' . $lang->task->name . '"');?>
                </div>
              </div>
            </div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->task->desc;?></div>
            <div class='detail-content'>
              <?php echo html::textarea('desc', htmlSpecialString($task->desc), "rows='8' class='form-control'");?>
            </div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->comment;?></div>
            <div class='detail-content'><?php echo html::textarea('comment', '',  "rows='5' class='form-control'");?></div>
          </div>
          <?php $this->printExtendFields($task, 'div', 'position=left');?>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->files;?></div>
            <div class='detail-content'>
              <?php echo $this->fetch('file', 'printFiles', array('files' => $task->files, 'fieldset' => 'false', 'object' => $task, 'method' => 'edit'));?>
              <?php echo $this->fetch('file', 'buildform');?>
            </div>
          </div>
          <div class='detail text-center form-actions'>
            <?php echo html::hidden('lastEditedDate', $task->lastEditedDate);?>
            <?php echo html::hidden('consumed', $task->consumed);?>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </div>
          <?php include '../../common/view/action.html.php';?>
        </div>
      </div>
      <div class='side-col col-4'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->task->legendBasic;?></div>
            <table class='table table-form'>
              <?php if($task->parent <= 0 and $execution->multiple):?>
              <tr>
                <th class='thWidth'><?php echo $lang->task->execution;?></th>
                <td><?php echo html::select('execution', $executions, $task->execution, 'class="form-control chosen" onchange="loadAll(this.value)"');?></td>
              </tr>
              <?php else:?>
              <?php echo html::hidden('execution', $task->execution);?>
              <?php endif;?>
              <tr>
                <th class='thWidth'><?php echo $lang->task->module;?></th>
                <td>
                  <div class='table-row'>
                    <span class='table-col' id="moduleIdBox"><?php echo html::select('module', $modules, $task->module, 'class="form-control chosen"');?></span>
                    <span class='table-col text-middle pl-10px' id='showAllModuleBox'>
                      <div class="checkbox-primary">
                        <input type="checkbox" id="showAllModule" <?php if($showAllModule) echo 'checked';?>><label for="showAllModule" class="no-margin"><?php echo $lang->all;?></label>
                      </div>
                    </span>
                  </div>
                </td>
              </tr>
              <?php if($execution->lifetime != 'ops' and !in_array($execution->attribute, array('request', 'review'))):?>
              <tr>
                <th><?php echo $lang->task->story;?></th>
                <td><span id="storyIdBox"><?php echo html::select('story', $stories, $task->story, "class='form-control chosen' data-drop_direction='down' data-max_drop_width='0'");?></span></td>
              </tr>
              <?php endif;?>
              <?php if($task->parent >= 0 and empty($task->team)):?>
              <tr>
                <th><?php echo $lang->task->parent;?></th>
                <td><?php echo html::select('parent', $tasks, $task->parent, "class='form-control chosen'");?></td>
              </tr>
              <?php endif;?>
              <tr class="modeBox">
                <th><?php echo $lang->task->mode;?></th>
                <td>
                  <?php
                  if($task->status == 'wait' and $task->parent == 0)
                  {
                      echo html::select('mode', $lang->task->editModeList, $task->mode, "class='form-control chosen'");
                  }
                  else
                  {
                      if($task->mode == '')
                      {
                          echo $lang->task->editModeList['single'];
                      }
                      else
                      {
                          echo zget($lang->task->editModeList, $task->mode);
                      }
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->task->assignedTo;?></th>
                <?php $disableAssignedTo = (!empty($task->team) and $task->mode == 'linear') ? "disabled='disabled'" : '';?>
                <?php
                $taskMembers = array();
                if(!empty($task->team))
                {
                    $teamAccounts = $task->members;
                    foreach($teamAccounts as $teamAccount)
                    {
                        if(!isset($members[$teamAccount])) continue;
                        $taskMembers[$teamAccount] = $members[$teamAccount];
                    }
                }
                else
                {
                    $taskMembers = $members;
                }
                ?>
                <td>
                  <div class='input-group' id='assignedToIdBox'>
                    <?php $hiddenTeam = $task->mode != '' ? '' : 'hidden';?>
                    <?php echo html::select('assignedTo', $taskMembers, $task->assignedTo, "class='form-control chosen' {$disableAssignedTo}");?>
                    <span class="input-group-btn team-group <?php echo $hiddenTeam;?>"><a class="btn br-0" href="#modalTeam" data-toggle="modal"><?php echo $lang->task->team;?></a></span>
                  </div>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->task->type;?></th>
                <td><?php echo html::select('type', $lang->task->typeList, $task->type, "class='form-control chosen'");?></td>
              </tr>
              <?php if(empty($task->children)):?>
              <tr>
                <th><?php echo $lang->task->status;?></th>
                <td><?php echo html::select('status', (array)$lang->task->statusList, $task->status, "class='form-control chosen'");?></td>
              </tr>
              <?php endif;?>
              <tr>
                <th><?php echo $lang->task->pri;?></th>
                <td><?php echo html::select('pri', $lang->task->priList, $task->pri, "class='form-control chosen'");?> </td>
              </tr>
              <tr>
                <th><?php echo $lang->task->mailto;?></th>
                <td>
                  <div class='input-group'>
                    <?php echo html::select('mailto[]', $users, $task->mailto, 'class="form-control picker-select" multiple data-drop-direction="bottom"');?>
                    <?php echo $this->fetch('my', 'buildContactLists');?>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->task->legendEffort;?></div>
            <table class='table table-form'>
              <tr>
                <th class='thWidth'><?php echo $lang->task->estStarted;?></th>
                <td><?php echo html::input('estStarted', $task->estStarted, "class='form-control form-date'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->deadline;?></th>
                <td><?php echo html::input('deadline', $task->deadline, "class='form-control form-date'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->estimate;?></th>
                <td>
                  <?php $readonly = (!empty($task->team) or $task->parent < 0) ? "readonly" : '';?>
                  <?php echo html::input('estimate', $task->estimate, "class='form-control' {$readonly}");?>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->task->consumed;?></th>
                <td><?php echo '<span id=consumedSpan>' . $task->consumed . '</span> '; common::printIcon('task', 'recordWorkhour', "taskID=$task->id", $task, 'list', '', '', 'record-estimate-toggle btn-link', true);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->left;?></th>
                <td><?php echo html::input('left', $task->left, "class='form-control' {$readonly}");?></td>
              </tr>
            </table>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->task->legendLife;?></div>
            <table class='table table-form'>
              <tr>
                <th class='lifeThWidth'><?php echo $lang->task->openedBy;?></th>
                <td><?php echo zget($users, $task->openedBy);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->realStarted;?></th>
                <td><?php echo html::input('realStarted', helper::isZeroDate($task->realStarted) ? '' : $task->realStarted, "class='form-control form-datetime'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->finishedBy;?></th>
                <td><?php echo html::select('finishedBy', $members, $task->finishedBy, "class='form-control chosen'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->finishedDate;?></th>
                <td><?php echo html::input('finishedDate', $task->finishedDate, 'class="form-control form-datetime"');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->canceledBy;?></th>
                <td><?php echo html::select('canceledBy', $users, $task->canceledBy, 'class="form-control chosen"');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->canceledDate;?></th>
                <td><?php echo html::input('canceledDate', $task->canceledDate, 'class="form-control form-datetime"');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->closedBy;?></th>
                <td><?php echo html::select('closedBy', $users, $task->closedBy, 'class="form-control chosen"');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->closedReason;?></th>
                <td><?php echo html::select('closedReason', $lang->task->reasonList, $task->closedReason, 'class="form-control chosen"');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->closedDate;?></th>
                <td><?php echo html::input('closedDate', $task->closedDate, 'class="form-control form-datetime"');?></td>
              </tr>
            </table>
          </div>
          <?php $this->printExtendFields($task, 'div', 'position=right');?>
        </div>
      </div>
    </div>
    <div class="modal fade modal-team" id="modalTeam"  data-scroll-inside='false'>
      <div class="modal-dialog">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <i class="icon icon-close"></i>
          </button>
          <h4 class="modal-title"><?php echo $lang->task->team?></h4>
        </div>
	<div class="modal-content with-padding" id='taskTeamEditor'>
	  <?php if(strpos('|closed|cancel|pause|', $task->status) !== false):?>
	     <h2 class='label label-info'>
               <?php echo $this->lang->task->error->teamCantOperate;?>
             </h2>
	  <?php endif;?>
          <table class='table table-form'>
            <tbody class="sortable">
              <?php include dirname(__FILE__) . DS . 'taskteam.html.php';?>
            </tbody>
	    <tfoot>
	      <?php if(strpos('|closed|cancel|pause|', $task->status) === false):?>
              <tr><td colspan='3' class='text-center form-actions'><?php echo html::a('javascript:void(0)', $lang->confirm, '', "id='confirmButton' class='btn btn-primary btn-wide'");?></td></tr>
	      <?php endif;?>
	    </tfoot>
          </table>
        </div>
      </div>
    </div>
  </form>
</div>
<?php js::set('executionID', $execution->id);?>
<?php include '../../common/view/footer.html.php';?>
