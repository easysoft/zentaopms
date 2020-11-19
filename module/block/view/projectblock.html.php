<?php if(empty($programs)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.block-programs .c-pri {width: 45px;text-align: center;}
.block-programs .c-status {width: 80px;}
</style>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter block-programs <?php if(!$longBlock) echo 'block-sm';?>'>
    <thead>
      <tr>
        <th class='c-name'><?php echo $lang->program->PGMName;?></th>
        <th class='w-100px'> <?php echo $lang->program->PGMPM;?></th>
        <th class='w-80px'><?php echo $lang->program->PGMStatus;?></th>
        <?php if($longBlock):?>
        <th class='w-80px'><?php echo $lang->program->teamCount;?></th>
        <th class='w-80px'><?php echo $lang->task->consumed;?></th>
        <th class='w-100px'><?php echo $lang->program->PGMBudget;?></th>
        <th class='w-80px'><?php echo $lang->program->leftStories;?></th>
        <th class='w-80px'><?php echo $lang->program->leftTasks;?></th>
        <th class='w-80px'><?php echo $lang->program->leftBugs;?></th>
        <?php endif;?>
      </tr>
    </thead>
    <tbody>
      <?php foreach($programs as $program):?>
      <?php
      $viewLink = $this->createLink('program', 'index', "programID={$program->id}");
      ?>
      <tr>
        <td title='<?php echo $program->name?>'><?php echo html::a($viewLink, $program->name);?></td>
        <td><?php echo zget($users, $program->PM, $program->PM)?></td>
        <td class='c-status'>
          <span class="status-program status-<?php echo $program->status?>"><?php echo zget($lang->project->statusList, $program->status);?></span>
        </td>
        <?php if($longBlock):?>
        <td><?php echo $program->teamCount;?></td>
        <td><?php echo $program->consumed;?></td>
        <td><?php echo $program->budget . ' ' . zget($lang->program->unitList, $program->budgetUnit);?></td>
        <td><?php echo $program->leftStories;?></td>
        <td><?php echo $program->leftTasks;?></td>
        <td><?php echo $program->leftBugs;?></td>
        <?php endif;?>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
