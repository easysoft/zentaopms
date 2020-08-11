<?php
/**
 * The edit view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: edit.html.php 4897 2013-06-26 01:13:16Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('oldStoryID', $task->story);?>
<?php js::set('oldAssignedTo', $task->assignedTo);?>
<?php js::set('oldProjectID', $task->project);?>
<?php js::set('confirmChangeProject', $lang->task->confirmChangeProject);?>
<?php js::set('changeProjectConfirmed', false);?>
<?php js::set('newRowCount', count($task->team) < 6 ? 6 - count($task->team) : 1);?>
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
                <div class='<?php if(empty($task->children) and empty($task->parent) and $task->type != 'affair') echo 'input-group';?>'>
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
                  <?php if(empty($task->children) and empty($task->parent) and $task->type != 'affair'):?>
                  <span class='input-group-addon'>
                    <div class='checkbox-primary'>
                      <input type='checkBox' name='multiple' id='multiple' value='1' <?php echo empty($task->team) ? '' : 'checked';?> />
                      <label for='multiple'><?php echo $lang->task->multipleAB;?></label>
                    </div>
                  </span>
                  <?php endif;?>
                </div>
              </div>
            </div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->task->desc;?></div>
            <div class='detail-content'>
              <?php echo html::textarea('desc', htmlspecialchars($task->desc), "rows='8' class='form-control'");?>
            </div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->comment;?></div>
            <div class='detail-content'><?php echo html::textarea('comment', '',  "rows='5' class='form-control'");?></div>
          </div>
          <?php $this->printExtendFields($task, 'div', 'position=left');?>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->files;?></div>
            <div class='detail-content'><?php echo $this->fetch('file', 'buildform');?></div>
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
              <?php if($task->parent <= 0):?>
              <tr>
                <th class='thWidth'><?php echo $lang->task->project;?></th>
                <td><?php echo html::select('project', $projects, $task->project, 'class="form-control chosen" onchange="loadAll(this.value)"');?></td>
              </tr>
              <?php endif;?>
              <tr>
                <th class='thWidth'><?php echo $lang->task->module;?></th>
                <td>
                  <div class='table-row'>
                    <span class='table-col' id="moduleIdBox"><?php echo html::select('module', $modules, $task->module, 'class="form-control chosen" onchange="loadModuleRelated()"');?></span>
                    <span class='table-col w-100px text-middle pl-10px'>
                      <div class="checkbox-primary">
                        <input type="checkbox" id="showAllModule" <?php if($showAllModule) echo 'checked';?>><label for="showAllModule" class="no-margin"><?php echo $lang->task->allModule;?></label>
                      </div>
                    </span>
                  </div>
                </td>
              </tr>
              <?php if($config->global->flow != 'onlyTask' and $project->type != 'ops'):?>
              <tr>
                <th><?php echo $lang->task->story;?></th>
                <td><span id="storyIdBox"><?php echo html::select('story', $stories, $task->story, "class='form-control chosen'");?></span></td>
              </tr>
              <?php endif;?>
              <?php if($task->parent >= 0 and empty($task->team)):?>
              <tr>
                <th><?php echo $lang->task->parent;?></th>
                <td><?php echo html::select('parent', $tasks, $task->parent, "class='form-control chosen'");?></td>
              </tr>
              <?php endif;?>
              <tr>
                <th><?php echo $lang->task->assignedTo;?></th>
                <?php $disableAssignedTo = (!empty($task->team) and $task->assignedTo != $this->app->user->account) ? "disabled='disabled'" :'';?>
                <?php
                $taskMembers = array();
                if(!empty($task->team))
                {
                    $teamAccounts = array_keys($task->team);
                    foreach($teamAccounts as $teamAccount)
                    {
                        $taskMembers[$teamAccount] = $members[$teamAccount];
                    }
                }
                else
                {
                    $taskMembers = $members;
                }
                ?>
                <td><span id="assignedToIdBox"><?php echo html::select('assignedTo', $taskMembers, $task->assignedTo, "class='form-control chosen' {$disableAssignedTo}");?></span></td>
              </tr>
              <tr class='<?php echo empty($task->team) ? 'hidden' : ''?>' id='teamTr'>
                <th><?php echo $lang->task->team;?></th>
                <td><?php echo html::a('#modalTeam', $lang->task->team, '', "class='form-control btn' data-toggle='modal'");?></td>
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
                    <?php echo html::select('mailto[]', $project->acl == 'private' ? $members : $users, str_replace(' ' , '', $task->mailto), 'class="form-control chosen" multiple');?>
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
                  <?php $disabled = !empty($task->team) ? "disabled='disabled'" : '';?>
                  <?php echo html::input('estimate', $task->estimate, "class='form-control' {$disabled}");?>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->task->consumed;?></th>
                <td><?php echo $task->consumed . ' '; common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task, 'list', '', '', 'record-estimate-toggle btn-link', true);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->left;?></th>
                <td>
                  <?php $disabled = !empty($task->team) ? "disabled='disabled'" : '';?>
                  <?php echo html::input('left', $task->left, "class='form-control' {$disabled}");?>
                </td>
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
                <td><?php echo html::input('realStarted', $task->realStarted, "class='form-control form-date'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->finishedBy;?></th>
                <td><?php echo html::select('finishedBy', $members, $task->finishedBy, "class='form-control chosen'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->finishedDate;?></th>
                <td><?php echo html::input('finishedDate', $task->finishedDate, 'class="form-control form-date"');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->canceledBy;?></th>
                <td><?php echo html::select('canceledBy', $users, $task->canceledBy, 'class="form-control chosen"');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->canceledDate;?></th>
                <td><?php echo html::input('canceledDate', $task->canceledDate, 'class="form-control form-date"');?></td>
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
                <td><?php echo html::input('closedDate', $task->closedDate, 'class="form-control form-date"');?></td>
              </tr>
            </table>
          </div>
          <?php $this->printExtendFields($task, 'div', 'position=right');?>
        </div>
      </div>
    </div>
    <div class="modal fade modal-team" id="modalTeam">
      <div class="modal-dialog">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <i class="icon icon-close"></i>
          </button>
          <h4 class="modal-title"><?php echo $lang->task->team?></h4>
        </div>
        <div class="modal-content with-padding" id='taskTeamEditor'>
          <table class='table table-form'>
            <tbody class="sortable">
              <?php foreach($task->team as $member):?>
              <tr>
                <td class='w-250px'><?php echo html::select("team[]", $members, $member->account, "class='form-control chosen'")?></td>
                <td>
                  <div class='input-group'>
                    <span class='input-group-addon'><?php echo $lang->task->estimate?></span>
                    <?php echo html::input("teamEstimate[]", (float)$member->estimate, "class='form-control text-center' placeholder='{$lang->task->hour}'")?>
                    <span class='input-group-addon fix-border'><?php echo $lang->task->consumed?></span>
                    <?php echo html::input("teamConsumed[]", (float)$member->consumed, "class='form-control text-center' readonly placeholder='{$lang->task->hour}'")?>
                    <span class='input-group-addon fix-border'><?php echo $lang->task->left?></span>
                    <?php echo html::input("teamLeft[]", (float)$member->left, "class='form-control text-center' placeholder='{$lang->task->hour}'")?>
                  </div>
                </td>
                <td class='w-130px sort-handler'>
                  <button type="button" class="btn btn-link btn-sm btn-icon btn-add"><i class="icon icon-plus"></i></button>
                  <button type='button' class='btn btn-link btn-sm btn-icon btn-move'><i class='icon-move'></i></button>
                  <button type="button" class="btn btn-link btn-sm btn-icon btn-delete"><i class="icon icon-close"></i></button>
                </td>
              </tr>
              <?php endforeach;?>
              <tr class='template'>
                <td class='w-250px'><?php echo html::select("team[]", $members, '', "class='form-control chosen'")?></td>
                <td>
                  <div class='input-group'>
                    <span class='input-group-addon'><?php echo $lang->task->estimate?></span>
                    <?php echo html::input("teamEstimate[]", '', "class='form-control text-center' placeholder='{$lang->task->hour}'")?>
                    <span class='input-group-addon fix-border'><?php echo $lang->task->consumed?></span>
                    <?php echo html::input("teamConsumed[]", '', "class='form-control text-center' placeholder='{$lang->task->hour}'")?>
                    <span class='input-group-addon fix-border'><?php echo $lang->task->left?></span>
                    <?php echo html::input("teamLeft[]", '', "class='form-control text-center' placeholder='{$lang->task->hour}'")?>
                  </div>
                </td>
                <td class='w-130px sort-handler'>
                  <button type="button" class="btn btn-link btn-sm btn-icon btn-add"><i class="icon icon-plus"></i></button>
                  <button type='button' class='btn btn-link btn-sm btn-icon btn-move'><i class='icon-move'></i></button>
                  <button type="button" class="btn btn-link btn-sm btn-icon btn-delete"><i class="icon icon-close"></i></button>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr><td colspan='3' class='text-center form-actions'><?php echo html::a('javascript:void(0)', $lang->confirm, '', "class='btn btn-primary btn-wide' data-dismiss='modal'");?></td></tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </form>
</div>
<?php js::set('projectID', $project->id);?>
<?php include '../../common/view/footer.html.php';?>
