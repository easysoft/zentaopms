<style>
.cards-menu > li > a {padding-left: 5px; text-align: left;}
.cards-menu > li > a > i {opacity: .5; display: inline-block; margin-right: 4px; width: 18px; text-align: center;}
.cards-menu > li > a:hover > i {opacity: 1;}

#cards {margin: 0; padding: 0 10px 10px 10px;}
#cards > .col {width: 33.33%;}
.col-side #cards > .col {width: 100%;}
#cards .panel {margin: 10px 0; border: 1px solid #DCDCDC; border-radius: 2px; box-shadow: none; height: 146px; cursor: pointer;}
#cards .panel:hover {border-color: #006AF1; box-shadow: 0 0 10px 0 rgba(0,0,100,.25);}
#cards .panel-heading {padding: 12px 24px 10px 16px;}
#cards .panel-body {padding: 0 16px 16px;}
#cards .panel-actions {padding: 7px 0; z-index: 0}
#cards .project-type-label {padding: 1px 2px;}
#cards .project-name {font-size: 16px; font-weight: normal; display: inline-block; max-width: 75%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: middle;}
#cards .project-infos {font-size: 12px; padding: 0 15px;}
#cards .project-infos > span {display: inline-block; line-height: 12px;}
#cards .project-infos > span > .icon {font-size: 12px; display: inline-block; position: relative; top: -1px}
#cards .project-infos > span + span {margin-left: 10px;}
#cards .project-detail {position: absolute; bottom: 16px; left: 16px; right: 16px; font-size: 12px;}
#cards .project-detail > p {margin-bottom: 8px;}
#cards .project-detail .progress {height: 4px;}
#cards .project-detail .progress-text-left .progress-text {width: 50px; left: -50px;}
#cards .panel-heading {cursor: pointer;}
#cards .project-stages-container {margin: 0 -16px -16px -16px; padding: 0 4px; height: 46px; overflow-x: auto; position: relative;}
#cards .project-stages:after {content: ' '; width: 30px; display: block; right: -16px; top: 16px; bottom: -6px; z-index: 1; background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%); position: absolute;}
#cards .project-stages-row {position: relative; height: 30px; z-index: 0;}
#cards .project-stage-item {white-space: nowrap; position: absolute; top: 0; min-width: 48px; padding-top: 13px; color: #838A9D;}
#cards .project-stage-item > div {white-space: nowrap; overflow: visible; text-align: center; text-overflow: ellipsis;}
#cards .project-stage-item:before {content: ' '; display: block; width: 8px; height: 8px; border-radius: 50%; background: #D1D1D1; position: absolute; left: 50%; margin-left: -4px; top: 0; z-index: 1;}
#cards .project-stage-item + .project-stage-item:after {content: ' '; display: block; left: -50%; right: 50%; height: 2px; background-color: #D1D1D1; top: 3px; position: absolute; z-index: 0;}
#cards .project-stage-item.is-going {color: #333;}
#cards .project-stage-item.is-going::before {background-color: #0C64EB;}
#dashboard .block-recentproject .panel-body {padding: 0;}
</style>
<div class="panel-body">
  <div class='row' id='cards'>
    <?php foreach ($projects as $projectID => $project):?>
    <div class='col' data-id='<?php echo $projectID?>'>
      <div class='panel' data-url='<?php echo $this->createLink('project', 'index', "projectID=$project->id");?>'>
        <div class='panel-heading not-move-handler'>
          <strong class='project-name' title='<?php echo $project->name;?>'> <?php echo html::a($this->createLink('project', 'index', "projectID=$project->id", '', '', $project->id), $project->name);?> </strong>
          <?php if($project->model === 'waterfall'): ?>
          <span class='project-type-label label label-warning label-outline'><?php echo $lang->program->waterfall; ?></span>
          <?php else: ?>
          <span class='project-type-label label label-info label-outline'><?php echo $lang->program->scrum; ?></span>
          <?php endif; ?>
          <nav class='panel-actions nav nav-default'>
            <li class='dropdown'>
              <a href='javascript:;' data-toggle='context-dropdown' class='panel-action'><i class='icon icon-ellipsis-v'></i></a>
              <ul class='dropdown-menu pull-right cards-menu'>
                <li><?php common::printIcon('program', 'PRJGroup', "projectID=$project->id", $project, 'button', 'group');?></li>
                <li><?php common::printIcon('program', 'PRJManageMembers', "projectID=$project->id", $project, 'button', 'persons');?></li>
                <li><?php common::printicon('program', 'PRJActivate', "projectid=$project->id", $project, 'button', '', '', 'iframe', true);?></li>
                <li><?php common::printIcon('program', 'PRJEdit',    "projectID=$project->id", $project, 'button', ' icon-edit');?></li>
                <li><?php common::printIcon('program', 'PRJStart',   "projectID=$project->id", $project, 'button', ' icon-play', '', 'iframe', true);?></li>
                <li><?php common::printIcon('program', 'PRJSuspend', "projectID=$project->id", $project, 'button', ' icon-pause', '', 'iframe', true);?></li>
                <li><?php common::printIcon('program', 'PRJClose',   "projectID=$project->id", $project, 'button', ' icon-off', '', 'iframe', true);?></li>
                <li><?php common::printIcon('program', 'PRJDelete',  "projectID=$project->id", $project, 'button', ' icon-trash', 'hiddenwin');?></li>
              </ul>
            </li>
          </nav>
        </div>
        <div class='panel-body'>
          <div class='project-infos'>
            <span><i class='icon icon-group'></i> <?php printf($lang->program->membersUnit, $project->teamCount); ?></span>
            <span><i class='icon icon-clock'></i> <?php printf($lang->program->hoursUnit, $project->estimate); ?></span>
          </div>
          <?php if($project->model === 'waterfall'): ?>
          <div class='project-detail project-stages'>
            <p class='text-muted'><?php echo $lang->program->ongoingStage;?></p>
            <?php
            $projectProjects = array();
            foreach($project->executions as $project)
            {
                if($project->grade == 1) $projectProjects[] = $project;
            }
            ?>
            <?php if(empty($projectProjects)): ?>
            <div class='label label-outline'><?php echo zget($lang->project->statusList, $project->status);?></div>
            <?php else: ?>
            <div class='project-stages-container scrollbar-hover'>
              <div class='project-stages-row'>
                <?php foreach ($projectProjects as $project): ?>
                <div class='project-stage-item is-<?php echo $project->status;?><?php if($project->status !== 'wait') echo ' is-going'; ?>'>
                  <div><?php echo $project->name; ?></div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>
          </div>
          <?php else: ?>
          <?php $project = empty($project->executions ) ? '' : end($project->executions);?>
          <div class='project-detail project-iteration'>
            <p class='text-muted'><?php echo $lang->program->lastIteration; ?></p>
            <?php if($project):?>
            <div class='row'>
              <div class='col-xs-5'><?php echo $project->name;?></div>
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
  </div>
</div>
<script>
/* Auto resize stages */
$('.block-recentprogram #cards .project-stages-container').each(function()
{
    var $container = $(this);
    var $row = $container.children();
    var totalWidth = 0;
    $row.children().each(function()
    {
        var $item = $(this);
        $item.css('left', totalWidth);
        totalWidth += $item.width();
    });
    $row.css('minWidth', totalWidth);
});
</script>
