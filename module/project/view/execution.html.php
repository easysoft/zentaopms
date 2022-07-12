<?php include '../../common/view/header.html.php';?>
<?php js::set('projectID', $projectID);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <div class='btn-group'>
      <?php $viewName = $productID != 0 ? zget($productList,$productID) : $lang->product->allProduct;?>
      <a href='javascript:;' class='btn btn-link btn-limit' data-toggle='dropdown'><span class='text' title='<?php echo $viewName;?>'><?php echo $viewName;?></span> <span class='caret'></span></a>
      <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
        <?php
          $class = '';
          if($productID == 0) $class = 'class="active"';
          echo "<li $class>" . html::a($this->createLink('project', 'execution', "status=$status&projectID=$projectID&orderby=$orderBy"), $lang->product->allProduct) . "</li>";
          foreach($productList as $key => $product)
          {
              $class = $productID == $key ? 'class="active"' : '';
              echo "<li $class>" . html::a($this->createLink('project', 'execution', "status=$status&projectID=$projectID&orderby=$orderBy&productID=$key"), $product) . "</li>";
          }
        ?>
      </ul>
    </div>
    <?php foreach($lang->execution->featureBar['all'] as $key => $label):?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($status == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a($this->createLink('project', 'execution', "status=$key&projectID=$projectID&orderBy=$orderBy&productID=$productID"), $label, '', "class='btn btn-link' id='{$key}Tab'");?>
    <?php endforeach;?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('execution', 'export', "status=$status&productID=$productID&orderBy=$orderBy&from=project", "<i class='icon-export muted'> </i> " . $lang->export, '', "class='btn btn-link export'")?>
     <?php if(common::hasPriv('programplan', 'create') and $isStage):?>
     <?php echo html::a($this->createLink('programplan', 'create', "projectID=$projectID&productID=$productID"), "<i class='icon icon-plus'></i> " . $lang->programplan->create, '', "class='btn btn-primary'");?>
    <?php else: ?>
    <?php if(common::hasPriv('execution', 'create')) echo html::a($this->createLink('execution', 'create', "projectID=$projectID"), "<i class='icon icon-sm icon-plus'></i> " . $lang->execution->create, '', "class='btn btn-primary create-execution-btn' data-app='execution' onclick='$(this).removeAttr(\"data-toggle\")'");?>
    <?php endif;?>
  </div>
</div>
<div id='mainContent' class="main-row fade">
  <?php if(empty($executionStats)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->execution->noExecution;?></span>
      <?php if($status == "all"):?>
        <?php if(common::hasPriv('programplan', 'create') and $isStage):?>
        <?php echo html::a($this->createLink('programplan', 'create', "projectID=$projectID&productID=$productID"), "<i class='icon icon-plus'></i> " . $lang->programplan->create, '', "class='btn btn-info'");?>
        <?php else: ?>
          <?php if(common::hasPriv('execution', 'create')):?>
          <?php echo html::a($this->createLink('execution', 'create', "projectID=$projectID"), "<i class='icon icon-plus'></i> " . $lang->execution->create, '', "class='btn btn-info' data-app='execution'");?>
          <?php endif;?>
        <?php endif;?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <?php $canBatchEdit = common::hasPriv('execution', 'batchEdit'); ?>
  <form class='main-table' id='executionForm' method='post' data-ride='table' data-nested='true' data-expand-nest-child='false' data-checkable='false' data-enable-empty-nested-row='true' data-replace-id='executionTableList' data-preserve-nested='true'>
    <table class="table table-from table-fixed table-nested" id="executionList">
      <?php $vars = "status=$status&orderBy=%s";?>	
      <thead>
        <tr>
          <th class='table-nest-title'>
          <a class='table-nest-toggle icon table-nest-toggle-global' data-expand-text='<?php echo $lang->expand; ?>' data-collapse-text='<?php echo $lang->collapse;?>'></a>
          <?php echo $lang->nameAB;?>
          </th>
          <th class='c-user'><?php echo $lang->execution->owner;?></th>
          <th class='c-status'><?php echo $lang->project->status;?></th>
          <th class='c-hours'><?php echo $lang->project->progress;?></th>
          <th class='c-date'><?php echo $lang->programplan->begin;?></th>
          <th class='c-date'><?php echo $lang->programplan->end;?></th>
          <th class='c-hours'><?php echo $lang->task->estimateAB;?></th>
          <th class='c-hours'><?php echo $lang->task->consumedAB;?></th>
          <th class='c-hours'><?php echo $lang->task->leftAB;?> </th>
          <th class='c-progress'><?php echo $lang->execution->burn;?> </th>
          <th class='text-center c-actions-6'><?php echo $lang->actions;?></th> 
        </tr>
      </thead>
      <tbody id="executionTableList">
        <?php foreach($executionStats as $execution):?>
        <?php
        $trClass  = '';
        $trAttrs  = "data-id='$execution->id' data-order='$execution->order' data-nested='true'";
        $trClass .= ' is-top-level table-nest-child-hide';
        ?>
        <tr <?php echo $trAttrs;?> class="<?php echo $trClass;?>">
          <td>
            <span id = <?php echo $execution->id;?> class="table-nest-icon icon table-nest-toggle"></span>
            <?php echo html::a($this->createLink('execution', 'view', "executionID=$execution->id"), $execution->name);?>
          </td>
          <td><?php echo zget($users, $execution->PM);?></td>
          <td><?php echo zget($lang->project->statusList, $execution->status);?></td>
          <td><?php echo html::ring($execution->hours->progress); ?></td>
          <td><?php echo $execution->begin;?></td>
          <td><?php echo $execution->end;?></td>
          <td class='hours' title='<?php echo $execution->hours->totalEstimate . ' ' . $this->lang->execution->workHour;?>'><?php echo $execution->hours->totalEstimate . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $execution->hours->totalConsumed . ' ' . $this->lang->execution->workHour;?>'><?php echo $execution->hours->totalConsumed . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $execution->hours->totalLeft     . ' ' . $this->lang->execution->workHour;?>'><?php echo $execution->hours->totalLeft     . $this->lang->execution->workHourUnit;?></td>
          <td id='spark-<?php echo $execution->id?>' class='sparkline text-left no-padding' values='<?php echo join(',', $execution->burns);?>'></td>
          <td class='c-actions'>
            <?php
            common::printIcon('execution', 'start', "executionID={$execution->id}", $execution, 'list', '', '', 'iframe', true);
            $class = !empty($execution->children) ? 'disabled' : '';
            common::printIcon('task', 'create', "executionID={$execution->id}", $execution, 'list', '', '', $class, false, "data-app='execution'");

            if($execution->grade == 1 && $this->loadModel('programplan')->isCreateTask($execution->id))
            {
                common::printIcon('programplan', 'create', "program={$execution->parent}&productID=$productID&planID=$execution->id", $execution, 'list', 'split', '', '', '', '', $this->lang->programplan->createSubPlan);
            }
            else
            {
                $disabled = ($execution->grade == 2) ? ' disabled' : '';
                echo common::hasPriv('programplan', 'create') ? html::a('javascript:alert("' . $this->lang->programplan->error->createdTask . '");', '<i class="icon-programplan-create icon-split"></i>', '', 'class="btn ' . $disabled . '"') : '';
            }

            common::printIcon('programplan', 'edit', "stageID=$execution->id&projectID=$execution->project", $execution, 'list', '', '', 'iframe', true);

            $disabled = !empty($execution->children) ? ' disabled' : '';
            if($execution->status != 'closed' and common::hasPriv('execution', 'close', $execution))
            {
                common::printIcon('execution', 'close', "stageID=$execution->id", $execution, 'list', 'off', 'hiddenwin' , $disabled . ' iframe', true, '', $this->lang->programplan->close);
            }
            elseif($execution->status == 'closed' and common::hasPriv('execution', 'activate', $execution))
            {
                common::printIcon('execution', 'activate', "stageID=$execution->id", $execution, 'list', 'magic', 'hiddenwin' , $disabled . ' iframe', true, '', $this->lang->programplan->activate);
            }

            if(common::hasPriv('execution', 'delete', $execution))
            {
                common::printIcon('execution', 'delete', "stageID=$execution->id&confirm=no", $execution, 'list', 'trash', 'hiddenwin' , $disabled, '', '', $this->lang->programplan->delete);
            }
            ?>
          </td>
        </tr>

        <?php if(!empty($execution->tasks)):?>
        <?php foreach($execution->tasks as $task):?>
        <?php
        $trClass  = '';
        $trAttrs  = "data-id={$task->id} data-parent={$task->execution}";
        $trClass .= " is-nest-child no-nest";
        $trAttrs .= " data-nest-parent='$task->execution' data-nest-path=',$execution->id,$task->id,'";
        if($task == end($child->tasks) and count($execution->tasks) == 50) $trClass .= ' showmore';
        ?>
        <tr <?php echo $trAttrs;?> class='<?php echo $trClass;?>'>
          <td><?php echo html::a($this->createLink('task', 'view', "id=$task->id"), $task->name);?></td>
          <td><?php echo zget($users, $task->assignedTo);?></td>
          <td><?php echo zget($lang->task->statusList, $task->status);?></td>
          <td></td>
          <td><?php echo $task->estStarted;?></td>
          <td><?php echo $task->deadline;?></td>
          <td class='hours' title='<?php echo $task->estimate . ' ' . $this->lang->execution->workHour;?>'><?php echo $task->estimate . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $task->consumed . ' ' . $this->lang->execution->workHour;?>'><?php echo $task->consumed . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $task->left . ' ' . $this->lang->execution->workHour;?>'><?php echo $task->left . $this->lang->execution->workHourUnit;?></td>
          <td></td>
          <td class='c-actions'><?php echo $this->task->buildOperateMenu($task, 'browse');?></td>
        </tr>

        <?php if(!empty($task->children)):?>
        <?php foreach($task->children as $childTask):?>
        <?php
        $trClass  = '';
        $trAttrs  = "data-id={$childTask->id} data-parent={$child->parent}";
        $trClass .= " is-nest-child no-nest";
        $trAttrs .= " data-nest-parent='$childTask->parent' data-nest-path=',$execution->id,$childTask->parent,$childTask->id,'";
        ?>
        <tr <?php echo $trAttrs;?> class='<?php echo $trClass;?>'>
          <td><?php echo html::a($this->createLink('task', 'view', "id=$childTask->id"), $childTask->name);?></td>
          <td><?php echo zget($users, $childTask->assignedTo);?></td>
          <td><?php echo zget($lang->task->statusList, $childTask->status);?></td>
          <td></td>
          <td><?php echo $childTask->estStarted;?></td>
          <td><?php echo $childTask->deadline;?></td>
          <td class='hours' title='<?php echo $childTask->estimate . ' ' . $this->lang->execution->workHour;?>'><?php echo $childTask->estimate . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $childTask->consumed . ' ' . $this->lang->execution->workHour;?>'><?php echo $childTask->consumed . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $childTask->left . ' ' . $this->lang->execution->workHour;?>'><?php echo $childTask->left . $this->lang->execution->workHourUnit;?></td>
          <td></td>
          <td class='c-actions'><?php echo $this->task->buildOperateMenu($childTask, 'browse');?></td>
        </tr>
        <?php endforeach;?>
        <?php endif;?>

        <?php endforeach;?>
        <?php endif;?>

        <?php if(!empty($execution->children)):?>
        <?php foreach($execution->children as $child):?>
        <?php
        $trClass  = '';
        $trAttrs  = "data-id={$child->id} data-parent={$child->parent}";
        $trClass .= " is-nest-child";
        $trAttrs .= " data-nest-parent='$child->parent' data-order='$child->order' data-nest-path=',$execution->id,$child->id,'";
        ?>
        <tr <?php echo $trAttrs;?> class='<?php echo $trClass;?>'>
          <td>
            <?php echo html::a($this->createLink('execution', 'view', "executionID=$child->id"), $child->name);?>
          </td>
          <td><?php echo zget($users, $child->PM);?></td>
          <td><?php echo zget($lang->project->statusList, $child->status);?></td>
          <td><?php echo html::ring($child->hours->progress); ?></td>
          <td><?php echo $child->begin;?></td>
          <td><?php echo $child->end;?></td>
          <td class='hours' title='<?php echo $child->hours->totalEstimate . ' ' . $this->lang->execution->workHour;?>'><?php echo $child->hours->totalEstimate . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $child->hours->totalConsumed . ' ' . $this->lang->execution->workHour;?>'><?php echo $child->hours->totalConsumed . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $child->hours->totalLeft . ' ' . $this->lang->execution->workHour;?>'><?php echo $child->hours->totalLeft . $this->lang->execution->workHourUnit;?></td>
          <td id='spark-<?php echo $child->id?>' class='sparkline text-left no-padding' values='<?php echo join(',', $child->burns);?>'></td>
          <td class='c-actions'>
            <?php
            common::printIcon('execution', 'start', "executionID={$child->id}", $child, 'list', '', '', 'iframe', true);
            $class = !empty($child->children) ? 'disabled' : '';
            common::printIcon('task', 'create', "executionID={$child->id}", $child, 'list', '', '', $class, false, "data-app='execution'");

            if($child->grade == 1 && $this->loadModel('programplan')->isCreateTask($child->id))
            {
                common::printIcon('programplan', 'create', "program={$child->parent}&productID=$productID&planID=$child->id", $child, 'list', 'split', '', '', '', '', $this->lang->programplan->createSubPlan);
            }
            else
            {
                $disabled = ($child->grade == 2) ? ' disabled' : '';
                echo common::hasPriv('programplan', 'create') ? html::a('javascript:alert("' . $this->lang->programplan->error->createdTask . '");', '<i class="icon-programplan-create icon-split"></i>', '', 'class="btn ' . $disabled . '"') : '';
            }

            common::printIcon('programplan', 'edit', "stageID=$child->id&projectID=$child->project", $child, 'list', '', '', 'iframe', true);

            $disabled = !empty($child->children) ? ' disabled' : '';
            if($child->status != 'closed' and common::hasPriv('execution', 'close', $child))
            {
                common::printIcon('execution', 'close', "stageID=$child->id", $child, 'list', 'off', 'hiddenwin' , $disabled . ' iframe', true, '', $this->lang->programplan->close);
            }
            elseif($child->status == 'closed' and common::hasPriv('execution', 'activate', $child))
            {
                common::printIcon('execution', 'activate', "stageID=$child->id", $child, 'list', 'magic', 'hiddenwin' , $disabled . ' iframe', true, '', $this->lang->programplan->activate);
            }

            if(common::hasPriv('execution', 'delete', $child))
            {
                common::printIcon('execution', 'delete', "stageID=$child->id&confirm=no", $child, 'list', 'trash', 'hiddenwin' , $disabled, '', '', $this->lang->programplan->delete);
            }
            ?>
          </td>
        </tr>

        <?php if(!empty($child->tasks)):?>
        <?php foreach($child->tasks as $task):?>
        <?php
        $trClass  = '';
        $trAttrs  = "data-id={$task->id} data-parent={$task->execution}";
        $trClass .= " is-nest-child no-nest";
        $trAttrs .= " data-nest-parent='$task->execution' data-nest-path=',$execution->id,$child->id,$task->id,'";
        if($task == end($child->tasks) and count($child->tasks) == 50) $trClass .= ' showmore';
        ?>
        <tr <?php echo $trAttrs;?> class='<?php echo $trClass;?>'>
          <td><?php echo html::a($this->createLink('task', 'view', "id=$task->id"), $task->name);?></td>
          <td><?php echo zget($users, $task->assignedTo);?></td>
          <td><?php echo zget($lang->task->statusList, $task->status);?></td>
          <td></td>
          <td><?php echo $task->estStarted;?></td>
          <td><?php echo $task->deadline;?></td>
          <td class='hours' title='<?php echo $task->estimate . ' ' . $this->lang->execution->workHour;?>'><?php echo $task->estimate . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $task->consumed . ' ' . $this->lang->execution->workHour;?>'><?php echo $task->consumed . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $task->left . ' ' . $this->lang->execution->workHour;?>'><?php echo $task->left . $this->lang->execution->workHourUnit;?></td>
          <td></td>
          <td class='c-actions'><?php echo $this->task->buildOperateMenu($task, 'browse');?></td>
        </tr>

        <?php if(!empty($task->children)):?>
        <?php foreach($task->children as $childTask):?>
        <?php
        $trClass  = '';
        $trAttrs  = "data-id={$childTask->id} data-parent={$child->parent}";
        $trClass .= " is-nest-child no-nest";
        $trAttrs .= " data-nest-parent='$childTask->parent' data-nest-path=',$execution->id,$child->id,$childTask->parent,$childTask->id,'";
        ?>
        <tr <?php echo $trAttrs;?> class='<?php echo $trClass;?>'>
          <td><?php echo html::a($this->createLink('task', 'view', "id=$childTask->id"), $childTask->name);?></td>
          <td><?php echo zget($users, $childTask->assignedTo);?></td>
          <td><?php echo zget($lang->task->statusList, $childTask->status);?></td>
          <td></td>
          <td><?php echo $childTask->estStarted;?></td>
          <td><?php echo $childTask->deadline;?></td>
          <td class='hours' title='<?php echo $childTask->estimate . ' ' . $this->lang->execution->workHour;?>'><?php echo $childTask->estimate . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $childTask->consumed . ' ' . $this->lang->execution->workHour;?>'><?php echo $childTask->consumed . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $childTask->left . ' ' . $this->lang->execution->workHour;?>'><?php echo $childTask->left . $this->lang->execution->workHourUnit;?></td>
          <td></td>
          <td class='c-actions'><?php echo $this->task->buildOperateMenu($childTask, 'browse');?></td>
        </tr>
        <?php endforeach;?>
        <?php endif;?>

        <?php endforeach;?>
        <?php endif;?>
        <?php endforeach;?>

        <?php endif;?>
        <?php endforeach;?>
      </tbody>
    </table>
  </form>
  <?php endif;?>
</div>
<?php js::set('status', $status)?>
<?php js::set('orderBy', $orderBy)?>
<script>
$("#<?php echo $status;?>Tab").addClass('btn-active-text');
</script>
<?php include '../../common/view/footer.html.php';?>
