<style>#mainMenu{padding-left: 10px; padding-right: 10px;}</style>
<div class='row cell' id='cards'>
  <?php foreach ($programs as $programID => $program):?>
  <div class='col' data-id='<?php echo $programID?>'>
    <div class='panel' data-url='<?php echo $this->createLink('program', 'index', "programID=$program->id");?>'>
      <div class='panel-heading'>
        <strong class='program-name' title='<?php echo $program->name;?>'><?php echo $program->name;?></strong>
        <?php if($program->template === 'cmmi'): ?>
        <span class='program-type-label label label-warning label-outline'><?php echo $lang->program->cmmi; ?></span>
        <?php else: ?>
        <span class='program-type-label label label-info label-outline'><?php echo $lang->program->scrum; ?></span>
        <?php endif; ?>
        <nav class='panel-actions nav nav-default'>
          <li class='dropdown'>
            <a href='javascript:;' data-toggle='dropdown' class='panel-action'><i class='icon icon-ellipsis-v'></i></a>
            <ul class='dropdown-menu pull-right'>
              <li><?php common::printIcon('program', 'group', "programID=$program->id", $program, 'button', 'group');?></li>
              <li><?php common::printIcon('program', 'manageMembers', "programID=$program->id", $program, 'button', 'persons');?></li>
              <li><?php common::printicon('program', 'activate', "programid=$program->id", $program, 'button', '', '', 'iframe', true);?></li>
              <li><?php if(common::hasPriv('program', 'edit')) echo html::a($this->createLink("program", "edit", "programID=$program->id"), "<i class='icon-edit'></i> " . $lang->edit, '', "");?></li>
              <li><?php common::printIcon('program', 'start',   "programID=$program->id", $program, 'button', '', '', 'iframe', true);?></li>
              <li><?php common::printIcon('program', 'suspend', "programID=$program->id", $program, 'button', '', '', 'iframe', true);?></li>
              <li><?php common::printIcon('program', 'close',   "programID=$program->id", $program, 'button', '', '', 'iframe', true);?></li>
              <li><?php if(common::hasPriv('program', 'delete'))  echo html::a($this->createLink("program", "delete", "programID=$program->id"), "<i class='icon-trash'></i> " . $lang->delete, 'hiddenwin', "");?></li>
            </ul>
          </li>
        </nav>
      </div>
      <div class='panel-body'>
        <div class='program-infos'>
          <span><i class='icon icon-group'></i> <?php printf($lang->program->membersUnit, $program->teamCount); ?></span>
          <span><i class='icon icon-clock'></i> <?php printf($lang->program->hoursUnit, $program->estimate); ?></span>
          <span><i class='icon icon-cost'></i> <?php echo $program->budget . '' . zget($lang->program->unitList, $program->budgetUnit);?></span>
        </div>
        <?php if($program->template === 'cmmi'): ?>
        <div class='program-detail program-stages'>
          <p class='text-muted'><?php echo $lang->program->ongoingStage; ?></p>
          <div class='label label-outline'><?php echo zget($lang->project->statusList, $program->status);?></div>
        </div>
        <?php else: ?>
        <?php $project = $program->projects ? current($program->projects) : '';?>
        <div class='program-detail program-iteration'>
          <p class='text-muted'><?php echo $lang->program->lastIteration; ?></p>
          <?php if($project):?>
          <div class='row'>
            <div class='col-xs-5'><?php echo $project->name; ?></div>
            <div class='col-xs-7'>
            <div class="progress progress-text-left">
              <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $project->hours->progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $project->hours->progress;?>%">
              <span class="progress-text"><?php echo $project->hours->progress;?>%</span>
              </div>
            </div>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endforeach;?>
  <div class='col-xs-12' id='cardsFooter'>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
</div>
