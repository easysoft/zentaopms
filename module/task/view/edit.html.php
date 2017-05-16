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
<?php js::set('oldStoryID', $task->story); ?>
<?php js::set('oldAssignedTo', $task->assignedTo); ?>
<?php js::set('confirmChangeProject', $lang->task->confirmChangeProject); ?>
<?php js::set('changeProjectConfirmed', false); ?>
<form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['task']);?> <strong><?php echo $task->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name, '', "class='task-title'");?></strong>
    <small><?php echo html::icon($lang->icons['edit']) . ' ' . $lang->task->edit;?></small>
  </div>
  <div class='actions'>
    <?php echo html::submitButton($lang->save)?>
  </div>
</div>
<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <div class='form-group'>
        <div class='input-group'>
          <input type='hidden' id='color' name='color' data-provide='colorpicker' data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='<?php echo $lang->task->colorTag ?>' value='<?php echo $task->color ?>' data-update-text='.task-title, #name'>
          <?php echo html::input('name', $task->name, 'class="form-control" autocomplete="off" placeholder="' . $lang->task->name . '"');?>
        </div>
      </div>
      <fieldset class='fieldset-pure'>
        <legend><?php echo $lang->task->desc;?></legend>
        <div class='form-group'>
          <?php echo html::textarea('desc', htmlspecialchars($task->desc), "rows='8' class='form-control'");?>
        </div>
      </fieldset>
      <fieldset class='fieldset-pure'>
        <legend><?php echo $lang->comment;?></legend>
        <div class='form-group'><?php echo html::textarea('comment', '',  "rows='5' class='form-control'");?></div>
      </fieldset>
      <fieldset class='fieldset-pure'>
        <legend><?php echo $lang->files;?></legend>
        <div class='form-group'><?php echo $this->fetch('file', 'buildform');?></div>
      </fieldset>
      <div class='actions actions-form'>
        <?php echo html::hidden('lastEditedDate', $task->lastEditedDate);?>
        <?php echo html::submitButton($lang->save) . html::linkButton($lang->goback, $this->inlink('view', "taskID=$task->id")) .html::hidden('consumed', $task->consumed);?>
      </div>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->task->legendBasic;?></legend>
        <table class='table table-form'> 
          <tr>
            <th class='w-80px'><?php echo $lang->task->project;?></th>
            <td><?php echo html::select('project', $projects, $task->project, 'class="form-control chosen" onchange="loadAll(this.value)"');?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->module;?></th>
            <td id="moduleIdBox"><?php echo html::select('module', $modules, $task->module, 'class="form-control chosen" onchange="loadModuleRelated()"');?></td>
          </tr>
          <?php if($config->global->flow != 'onlyTask'):?>
          <tr>
            <th><?php echo $lang->task->story;?></th>
            <td><span id="storyIdBox"><?php echo html::select('story', $stories, $task->story, "class='form-control chosen'");?></span></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->task->assignedTo;?></th>
            <td><span id="assignedToIdBox"><?php echo html::select('assignedTo', $members, $task->assignedTo, "class='form-control chosen'");?></span></td> 
          </tr>  
          <tr>
            <th><?php echo $lang->task->type;?></th>
            <td><?php echo html::select('type', $lang->task->typeList, $task->type, 'class=form-control');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->status;?></th>
            <td><?php echo html::select('status', (array)$lang->task->statusList, $task->status, 'class=form-control');?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->pri;?></th>
            <td><?php echo html::select('pri', $lang->task->priList, $task->pri, 'class=form-control');?> </td>
          </tr>
          <tr>
            <th><?php echo $lang->task->mailto;?></th>
            <td><?php echo html::select('mailto[]', $project->acl == 'private' ? $members : $users, str_replace(' ' , '', $task->mailto), 'class="form-control" multiple');?></td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->task->legendEffort;?></legend>
        <table class='table table-form'> 
          <tr>
            <th class='w-70px'><?php echo $lang->task->estStarted;?></th>
            <td><?php echo html::input('estStarted', $task->estStarted, "class='form-control form-date'");?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->realStarted;?></th>
            <td><?php echo html::input('realStarted', $task->realStarted, "class='form-control form-date'");?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->deadline;?></th>
            <td><?php echo html::input('deadline', $task->deadline, "class='form-control form-date'");?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->estimate;?></th>
            <td><?php echo html::input('estimate', $task->estimate, "class='form-control' autocomplete='off'");?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->consumed;?></th>
            <td><?php echo $task->consumed . ' '; common::printIcon('task', 'recordEstimate',   "taskID=$task->id", $task, 'list', '', '', 'iframe', true);?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->left;?></th>
            <td><?php echo html::input('left', $task->left, "class='form-control' autocomplete='off'");?></td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->task->legendLife;?></legend>
        <table class='table table-form'> 
          <tr>
            <th class='w-70px'><?php echo $lang->task->openedBy;?></th>
            <td><?php echo $users[$task->openedBy];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->finishedBy;?></th>
            <td><?php echo html::select('finishedBy', $members, $task->finishedBy, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->finishedDate;?></th>
            <td><?php echo html::input('finishedDate', $task->finishedDate, 'class="form-control" autocomplete="off"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->canceledBy;?></th>
            <td><?php echo html::select('canceledBy', $users, $task->canceledBy, 'class="form-control chosen"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->canceledDate;?></th>
            <td><?php echo html::input('canceledDate', $task->canceledDate, 'class="form-control" autocomplete="off"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->closedBy;?></th>
            <td><?php echo html::select('closedBy', $users, $task->closedBy, 'class="form-control chosen"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->closedReason;?></th>
            <td><?php echo html::select('closedReason', $lang->task->reasonList, $task->closedReason, 'class="form-control"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->closedDate;?></th>
            <td><?php echo html::input('closedDate', $task->closedDate, 'class="form-control" autocomplete="off"');?></td>
          </tr>
        </table>
      </fieldset>
    </div>
  </div>
</div>
</form>
<?php include '../../common/view/footer.html.php';?>
