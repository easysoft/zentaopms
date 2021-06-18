<?php if(empty($projects)): ?>
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
        <th class='c-name w-100px'><?php echo $lang->project->name;?></th>
        <th class='w-80px'><?php echo $lang->project->PM;?></th>
        <th class='w-60px'><?php echo $lang->project->status;?></th>
        <?php if($longBlock):?>
        <th class='w-60px'><?php echo $lang->project->teamCount;?></th>
        <th class='w-90px text-right'><?php echo $lang->task->consumed;?></th>
        <th class='w-80px text-right'><?php echo $lang->project->budget;?></th>
        <th class='w-80px'><?php echo $lang->project->leftStories;?></th>
        <th class='w-80px'><?php echo $lang->project->leftTasks;?></th>
        <th class='w-80px'><?php echo $lang->project->leftBugs;?></th>
        <?php endif;?>
      </tr>
    </thead>
    <tbody>
      <?php foreach($projects as $project):?>
      <?php $viewLink = $this->createLink('project', 'index', "programID={$project->id}");?>
      <tr>
        <td title='<?php echo $project->name?>'><?php echo html::a($viewLink, $project->name);?></td>
        <td><?php echo zget($users, $project->PM, $project->PM)?></td>
        <td class='c-status'>
          <span class="status-program status-<?php echo $project->status?>"><?php echo zget($lang->project->statusList, $project->status);?></span>
        </td>
        <?php if($longBlock):?>
        <td class='text-center'><?php echo $project->teamCount;?></td>
        <td class='text-right' title="<?php echo $project->consumed . ' ' . $lang->execution->workHour;?>"><?php echo $project->consumed . $lang->execution->workHourUnit;?></td>
        <?php $programBudget = in_array($this->app->getClientLang(), ['zh-cn','zh-tw']) ? round((float)$project->budget / 10000, 2) . $this->lang->project->tenThousand : round((float)$project->budget, 2);?>
        <td class='text-right'><?php echo $project->budget != 0 ? zget($lang->project->currencySymbol, $project->budgetUnit) . ' ' . $programBudget : $lang->project->future;?></td>
        <td class='text-center'><?php echo $project->leftStories;?></td>
        <td class='text-center'><?php echo $project->leftTasks;?></td>
        <td class='text-center'><?php echo $project->leftBugs;?></td>
        <?php endif;?>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
