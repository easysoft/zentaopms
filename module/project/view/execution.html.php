<?php include '../../common/view/header.html.php';?>
<?php js::set('projectID', $projectID);?>
<?php js::set('project', $project);?>
<?php js::set('edit', $lang->edit);?>
<?php js::set('selectAll', $lang->selectAll);?>
<?php js::set('checkedExecutions', $lang->execution->checkedExecutions);?>
<?php js::set('cilentLang', $this->app->getClientLang());?>
<?php js::set('defaultTaskTip', $lang->programplan->stageCustom->task);?>
<?php js::set('disabledTaskTip', sprintf($lang->project->disabledInputTip, $lang->edit . $lang->executionCommon));?>
<?php js::set('defaultExecutionTip', $lang->edit . $lang->executionCommon);?>
<?php js::set('disabledExecutionTip', sprintf($lang->project->disabledInputTip, $lang->programplan->stageCustom->task));?>
<?php js::set('checkedSummary', $lang->execution->checkedExecSummary);?>
<?php js::set('pageSummary', $lang->execution->pageExecSummary);?>
<?php js::set('executionSummary', $lang->execution->executionSummary);?>
<?php js::set('changeStatusHtml', $changeStatusHtml);?>
<?php if($project->model == 'ipd') js::set('reviewPoints', json_encode($reviewPoints));?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php if($project->division and $project->hasProduct):?>
    <div class='btn-group'>
      <?php $viewName = $productID != 0 ? zget($productList, $productID) : $lang->product->allProduct;?>
      <a href='javascript:;' class='btn btn-link btn-limit text-ellipsis' data-toggle='dropdown' style="max-width: 120px;"><span class='text' title='<?php echo $viewName;?>'><?php echo $viewName;?></span> <span class='caret'></span></a>
      <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
        <?php
          $class = '';
          if($productID == 0) $class = 'class="active"';
          echo "<li $class>" . html::a($this->createLink('project', 'execution', "status=$status&projectID=$projectID&orderby=$orderBy"), $lang->product->allProduct) . "</li>";
          foreach($productList as $key => $productName)
          {
              $class = $productID == $key ? 'class="active"' : '';
              echo "<li $class>" . html::a($this->createLink('project', 'execution', "status=$status&projectID=$projectID&orderby=$orderBy&productID=$key"), $productName, '', "title='{$productName}' class='text-ellipsis'") . "</li>";
          }
        ?>
      </ul>
    </div>
    <?php endif;?>
    <?php common::sortFeatureMenu();?>
    <?php foreach($lang->project->featureBar['execution'] as $key => $label):?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($status == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a($this->createLink('project', 'execution', "status=$key&projectID=$projectID&orderBy=$orderBy&productID=$productID"), $label, '', "class='btn btn-link' id='{$key}Tab'");?>
    <?php endforeach;?>
    <?php if(common::hasPriv('execution', 'batchEdit') and !empty($executionStats)) echo html::checkbox('editExecution', array('1' => $lang->edit . $lang->executionCommon), '', $this->cookie->editExecution ? 'checked=checked' : '');?>
    <?php if(common::hasPriv('execution', 'task')) echo html::checkbox('showTask', array('1' => $lang->programplan->stageCustom->task), '', $this->cookie->showTask ? 'checked=checked' : '');?>
    <?php if($project->model == 'ipd') echo html::checkbox('showStage', array('1' => $lang->programplan->stageCustom->point), '', $this->cookie->showStage ? 'checked=checked' : '');?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php if(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')) and ($this->config->edition == 'max' or $this->config->edition == 'ipd')):?>
    <div class="btn-group">
      <?php echo html::a($this->createLink('programplan', 'browse', "projectID=$projectID&productID=$productID&type=gantt"), "<i class='icon-gantt-alt'></i> &nbsp;", '', "class='btn btn-icon switchBtn' title='{$lang->programplan->gantt}'");?>
      <?php echo html::a('', "<i class='icon-list'></i> &nbsp;", '', "class='btn btn-icon text-primary switchBtn' title='{$lang->project->bylist}'");?>
    </div>
    <?php endif;?>
    <?php common::printLink('execution', 'export', "status=$status&productID=$productID&orderBy=$orderBy&from=project", "<i class='icon-export muted'> </i> " . $lang->export, '', "class='btn btn-link export'")?>
    <?php if(common::hasPriv('programplan', 'create') and $isStage and empty($product->deleted)):?>
    <?php echo html::a($this->createLink('programplan', 'create', "projectID=$projectID&productID=$productID"), "<i class='icon icon-plus'></i> " . $lang->programplan->create, '', "class='btn btn-primary'");?>
    <?php elseif($project->model == 'agileplus'):?>
    <div class="btn-group dropdown">
      <?php echo html::a($this->createLink('execution', 'create', "projectID=$projectID"), "<i class='icon icon-sm icon-plus'></i> " . $lang->execution->create, '', "class='btn btn-primary create-execution-btn' data-app='project' onclick='$(this).removeAttr(\"data-toggle\")'");?>
      <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <li><?php echo html::a($this->createLink('execution', 'create', "projectID=$projectID"), $lang->execution->create);?></li>
        <li><?php echo html::a($this->createLink('execution', 'create', "projectID=$projectID&executionID=0&copyExecutionID=&planID=0&confirm=no&productID=0&extra=type=kanban"), $lang->project->createKanban);?></li>
      </ul>
    </div>
    <?php else: ?>
    <?php if(common::hasPriv('execution', 'create') and !$isStage) echo html::a($this->createLink('execution', 'create', "projectID=$projectID"), "<i class='icon icon-sm icon-plus'></i> " . $lang->execution->create, '', "class='btn btn-primary create-execution-btn' data-app='project' onclick='$(this).removeAttr(\"data-toggle\")'");?>
    <?php endif;?>
  </div>
</div>
<div id='mainContent' class="main-row fade">
  <?php if(empty($executionStats)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->execution->noExecution;?></span>
      <?php if($allExecutionNum):?>
        <?php if(common::hasPriv('programplan', 'create') and $isStage):?>
        <?php echo html::a($this->createLink('programplan', 'create', "projectID=$projectID&productID=$productID"), "<i class='icon icon-plus'></i> " . $lang->programplan->create, '', "class='btn btn-info'");?>
        <?php else: ?>
          <?php if(common::hasPriv('execution', 'create')):?>
          <?php echo html::a($this->createLink('execution', 'create', "projectID=$projectID"), "<i class='icon icon-plus'></i> " . $lang->execution->create, '', "class='btn btn-info' data-app='project'");?>
          <?php endif;?>
        <?php endif;?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <?php $canBatchEdit = common::hasPriv('execution', 'batchEdit'); ?>
  <form class='main-table' id='executionForm' method='post' data-nested='true' data-expand-nest-child='false' data-enable-empty-nested-row='true' data-replace-id='executionTableList' data-preserve-nested='true'>
    <table class="table table-from table-fixed table-nested" id="executionList">
      <?php $vars = "status=$status&orderBy=%s";?>
      <thead>
        <tr>
          <th class='table-nest-title'>
            <div class="flex-between">
              <?php echo $lang->nameAB;?>
              <?php if($canBatchEdit and $showToggleIcon):?>
                <a class='table-nest-toggle icon table-nest-toggle-global' data-expand-text='<?php echo $lang->expand; ?>' data-collapse-text='<?php echo $lang->collapse;?>'></a>
              <?php endif;?>
            </div>
          </th>
          <?php if($project->division and $project->hasProduct) echo "<th class='text-left w-100px'>{$lang->project->product}</th>";?>
          <th class='c-status text-center'><?php echo $lang->project->status;?></th>
          <th class='w-50px'><?php echo $lang->execution->owner;?></th>
          <th class='c-date'><?php echo $lang->programplan->begin;?></th>
          <th class='c-enddate'><?php echo $lang->programplan->end;?></th>
          <th class='w-50px text-right'><?php echo $lang->task->estimateAB;?></th>
          <th class='w-50px text-right'><?php echo $lang->task->consumedAB;?></th>
          <th class='w-50px text-right'><?php echo $lang->task->leftAB;?> </th>
          <th class='w-50px'><?php echo $lang->project->progress;?></th>
          <th class='c-progress'><?php echo $lang->execution->burn;?> </th>
          <th class='text-center c-actions-6'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody id="executionTableList">
        <?php foreach($executionStats as $execution):?>
        <?php $execution->division = $project->division;?>
        <?php $executionProductID = (empty($productID) and !empty($execution->product)) ? $execution->product : $productID;?>
        <?php $this->execution->printNestedList($execution, false, $users, $executionProductID, $project);?>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="table-statistic"></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php js::set('status', $status)?>
<?php js::set('orderBy', $orderBy)?>
<?php include '../../common/view/footer.html.php';?>
