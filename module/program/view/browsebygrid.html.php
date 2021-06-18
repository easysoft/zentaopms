<div class='row cell' id='cards'>
  <?php foreach ($programs as $programID => $program):?>
  <div class='col' data-id='<?php echo $programID?>'>
    <div class='panel' data-url='<?php echo $this->createLink('program', 'index', "programID=$program->id", '', '', $program->id);?>'>
      <div class='panel-heading'>
        <strong class='program-name' title='<?php echo $program->name;?>'><?php echo $program->name;?></strong>
        <?php if($program->model === 'waterfall'): ?>
        <span class='program-type-label label label-warning label-outline'><?php echo $lang->program->waterfall; ?></span>
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
        <?php if($program->model === 'waterfall'): ?>
        <div class='program-detail program-stages'>
          <p class='text-muted'><?php echo $lang->program->ongoingStage; ?></p>
          <?php
          $programProjects = array();
          foreach($program->projects as $project)
          {
              if(!$project->parent) $programProjects[] = $project;
          }
          ?>
          <?php if(empty($programProjects)): ?>
          <div class='label label-outline'><?php echo zget($lang->project->statusList, $program->status);?></div>
          <?php else: ?>
          <div class='program-stages-container scrollbar-hover'>
            <div class='program-stages-row'>
              <?php foreach ($programProjects as $project): ?>
              <div class='program-stage-item is-<?php echo $project->status;?><?php if($project->status !== 'wait') echo ' is-going'; ?>'>
                <div><?php echo $project->name; ?></div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>
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
<style>
#mainMenu {padding-left: 10px; padding-right: 10px;}

#cards {margin: 0 10px;}
#cards > .col {width: 20%;}
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
#cards .pager {margin: 0; float: right;}
#cards .pager .btn {border: none}
#cards .program-stages-container {margin: 0 -16px -16px -16px; padding: 0 4px; height: 46px; overflow-x: auto; position: relative;}
#cards .program-stages:after {content: ' '; width: 30px; display: block; right: -16px; top: 16px; bottom: -6px; z-index: 1; background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%); position: absolute;}
#cards .program-stages-row {position: relative; height: 30px; z-index: 0;}
#cards .program-stage-item {white-space: nowrap; position: absolute; top: 0; min-width: 48px; padding-top: 13px; color: #838A9D;}
#cards .program-stage-item > div {white-space: nowrap; overflow: visible; text-align: center; text-overflow: ellipsis;}
#cards .program-stage-item:before {content: ' '; display: block; width: 8px; height: 8px; border-radius: 50%; background: #D1D1D1; position: absolute; left: 50%; margin-left: -4px; top: 0; z-index: 1;}
#cards .program-stage-item + .program-stage-item:after {content: ' '; display: block; left: -50%; right: 50%; height: 2px; background-color: #D1D1D1; top: 3px; position: absolute; z-index: 0;}
#cards .program-stage-item.is-going {color: #333;}
#cards .program-stage-item.is-going::before {background-color: #0C64EB;}
</style>
<script>
$(function()
{
    /* Auto resize cards size */
    var minCardWidth = 280;
    var $cards = $('#cards');
    var resizeCards = function()
    {
        var cardsWidth = $cards.width();
        var bestColsSize = 1;
        while((cardsWidth / (bestColsSize + 1)) > minCardWidth)
        {
            bestColsSize++;
        }
        $cards.children('.col').css('width', (100 / bestColsSize) + '%');
    };
    resizeCards();
    $(window).on('resize', resizeCards);

    /* Auto resize stages */
    $cards.find('.program-stages-container').each(function()
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

    /* Make cards clickable */
    $cards.on('click', '.panel', function(e)
    {
        if(!$(e.target).closest('.panel-actions').length)
        {
            window.location.href = $(this).data('url');
        }
    });
});
</script>
