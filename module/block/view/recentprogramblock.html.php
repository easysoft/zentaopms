<style>
#cards {margin: 0 10px;}
#cards > .col {width: 33.33%;}
#cards .panel {margin: 10px 0; border: 1px solid #DCDCDC; border-radius: 2px; box-shadow: none; height: 146px; cursor: pointer;}
#cards .panel:hover {border-color: #006AF1; box-shadow: 0 0 10px 0 rgba(0,0,100,.25);}
#cards .panel-heading {padding: 12px 24px 10px 16px;}
#cards .panel-body {padding: 0 16px 16px;}
#cards .panel-actions {padding: 7px 0;} 
#cards .panel-actions .dropdown-menu > li > a {padding-left: 5px; text-align: left;}
#cards .panel-actions .dropdown-menu > li > a > i {opacity: .5; display: inline-block; margin-right: 4px; width: 18px; text-align: center;}
#cards .panel-actions .dropdown-menu > li > a:hover > i {opacity: 1;} 
#cards .program-type-label {padding: 1px 2px;}
#cards .program-name {font-size: 16px; font-weight: normal; display: inline-block; max-width: 80%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: middle;}
#cards .program-infos {font-size: 12px;}
#cards .program-infos > span {display: inline-block; line-height: 12px;}
#cards .program-infos > span > .icon {font-size: 12px; display: inline-block; position: relative; top: -1px}
#cards .program-infos > span + span {margin-left: 15px;}
#cards .program-detail {position: absolute; bottom: 16px; left: 16px; right: 16px; font-size: 12px;}
#cards .program-detail > p {margin-bottom: 8px;}
#cards .program-detail .progress {height: 4px;}
#cards .program-detail .progress-text-left .progress-text {width: 50px; left: -50px;}
</style>
<div class='row' id='cards'>
  <?php foreach ($programs as $projectID => $project):?>
  <div class='col'>
    <div class='panel'>
      <div class='panel-heading'>
        <?php if($program->template === 'cmmi'): ?>
        <span class='program-type-label label label-warning label-outline'><?php echo $lang->block->cmmi; ?></span>
        <?php else: ?>
        <span class='program-type-label label label-info label-outline'><?php echo $lang->block->scrum; ?></span>
        <?php endif; ?>
        <strong class='program-name' title='<?php echo $program->name;?>'><?php echo $program->name;?></strong>
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
          <span><i class='icon icon-clock'></i> <?php printf($lang->program->hoursUnit, $program->consumed); ?></span>
          <span><i class='icon icon-cost'></i> <?php echo $program->budget . '' . zget($lang->program->unitList, $program->budgetUnit);?></span>
        </div>
        <?php if($program->template === 'cmmi'): ?>
        <div class='program-detail program-stages'>
          <p class='text-muted'><?php echo $lang->program->ongoingStage; ?></p>
          <div class='label label-outline'><?php echo zget($lang->project->statusList, $program->status);?></div>
        </div>
        <?php else: ?>
        <div class='program-detail program-iteration'>
          <p class='text-muted'><?php echo $lang->program->lastIteration; ?></p>
          <div class='row'>
            <div class='col-xs-5'><?php echo $program->project->name; ?></div>
            <div class='col-xs-7'>
            <div class="progress progress-text-left">
              <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $program->project->progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $program->project->progress;?>%">
              <span class="progress-text"><?php echo $program->project->progress;?>%</span>
              </div>
            </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
