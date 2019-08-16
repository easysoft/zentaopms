<?php if(isset($pageCSS)) css::internal($pageCSS);?>
<div class="detail">
  <h2 class="detail-title">
    <span class="label-id"><?php echo $task->id?></span>
    <span class="label label-task"><?php echo $lang->task->common?></span>
    <span class="label label-task"><?php echo $this->processStatus('task', $task);?></span>
    <span class="title">
      <?php if($task->parent > 0) echo '<span class="label no-margin label-badge label-light">' . $this->lang->task->childrenAB . '</span>';?>
      <?php if(!empty($task->team)) echo '<span class="label no-margin label-badge label-light">' . $this->lang->task->multipleAB . '</span>';?>
      <?php echo isset($task->parentName) ? $task->parentName . '/' : '';?><?php echo $task->name;?>
    </span>
  </h2>
  <div class="detail-content article-content">
    <div class="infos">
      <span><?php echo $lang->task->estimate;?> <?php echo $task->estimate . ' ' . $lang->workingHour;?></span>
      <span><?php echo $lang->task->consumedAB;?> <?php echo round($task->consumed, 2) . ' ' . $lang->workingHour;?></span>
      <span><?php echo $lang->task->leftAB;?> <?php echo $task->left . ' ' . $lang->workingHour;?></span>
    </div>
    <div class="infos">
      <span><?php echo $lang->task->type;?> <?php echo $lang->task->typeList[$task->type];?></span>
      <span><?php echo $lang->task->deadline;?> <?php echo $task->deadline; if(isset($task->delay)) printf($lang->task->delayWarning, $task->delay);?></span>
    </div>
    <div class="btn-toolbar">
      <?php
      if($task->status == 'wait') common::printIcon('task', 'finish', "taskID=$task->id", $task, 'list', '', '', 'btn btn-info btn-icon iframe', true);
      if($task->status == 'wait') common::printIcon('task', 'start', "taskID=$task->id", $task, 'list', '', '', 'btn btn-info btn-icon iframe', true);
      if($task->status == 'pause') common::printIcon('task', 'restart', "taskID=$task->id", $task, 'list', '', '', 'btn btn-info btn-icon iframe', true);
      if($task->status == 'done' or $task->status == 'cancel' or $task->status == 'closed') common::printIcon('task', 'close',  "taskID=$task->id", $task, 'list', 'off', '', 'btn btn-info btn-icon iframe', true);
      if($task->status == 'doing') common::printIcon('task', 'finish', "taskID=$task->id", $task, 'list', '', '', 'btn btn-info btn-icon iframe', true);

      common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task, 'list', 'time', '', 'btn btn-info btn-icon iframe', true);
      common::printIcon('task', 'edit',   "taskID=$task->id", $task, 'list', '', '', 'btn btn-info btn-icon');
      if(empty($task->team) or empty($task->children))
      {
          common::printIcon('task', 'batchCreate', "project=$task->project&storyID=$task->story&moduleID=$task->module&taskID=$task->id&ifame=0", $task, 'list', 'plus', '', 'btn btn-info btn-icon', '', '', $lang->task->children);
      }
      ?>
    </div>
  </div>
</div>
<div class="detail" open>
  <div class="detail-title"><?php echo $lang->task->legendDesc;?></div>
  <div class="detail-content article-content">
    <?php echo !empty($task->desc) ? $task->desc : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
  </div>
</div>
<?php if($project->type != 'ops'):?>
<?php if($task->fromBug != 0):?>
<div class="detail" open>
  <div class="detail-title"><?php echo $lang->bug->steps;?></div>
  <div class="detail-content article-content">
    <?php echo !empty($task->bugSteps) ? $task->bugSteps : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
  </div>
</div>
<?php elseif($task->story):?>
<div class="detail" open>
  <div class='detail-title'><?php echo $lang->task->storySpec;?></div>
  <div class='detail-content article-content'>
    <?php echo (!empty($task->storySpec) || !empty($task->storyFiles)) ? $task->storySpec : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
  </div>
  <?php echo $this->fetch('file', 'printFiles', array('files' => $task->storyFiles, 'fieldset' => 'false'));?>
</div>
<div class='detail' open>
  <div class='detail-title'><?php echo $lang->task->storyVerify;?></div>
  <div class='detail-content article-content'>
    <?php echo !empty($task->storyVerify) ? $task->storyVerify : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
  </div>
</div>
<?php endif;?>
<?php if(isset($task->cases) and $task->cases):?>
<div class='detail' open>
  <div class='detail-title'><?php echo $lang->task->case;?></div>
  <div class='detail-content article-content'>
    <ul class='list-unstyled'>
      <?php foreach($task->cases as $caseID => $case) echo '<li>' . html::a($this->createLink('testcase', 'view', "caseID=$caseID", '', true), "#$caseID " . $case, '', "data-toggle='modal' data-type='iframe' data-width='90%'") . '</li>';?>
    </ul>
  </div>
</div>
<?php endif;?>
<?php endif;?>
<?php echo $this->fetch('file', 'printFiles', array('files' => $task->files, 'fieldset' => 'true'));?>
<?php $actionFormLink = $this->createLink('action', 'comment', "objectType=task&objectID=$task->id");?>
<?php include '../../common/view/action.html.php';?>
<script>
<?php if(isset($pageJS)) echo $pageJS;?>
</script>
