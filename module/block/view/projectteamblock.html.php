<?php if(empty($projects)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<?php
$maxTeamCount = 0;
$maxConsumed  = 0;
?>
<div class='panel-body'>
  <div class='table-row'>
    <div class='table-col chart-titles'>
      <?php foreach($projects as $project):?>
      <?php
      $maxTeamCount = max($maxTeamCount, $project->teamCount);
      $maxConsumed  = max($maxConsumed, $project->consumed);
      ?>
      <div class='chart-title text-ellipsis' title="<?php echo $project->name;?>"><span><?php echo $project->name;?></span></div>
      <?php endforeach;?>
    </div>
    <div class='table-col chart-rows'>
      <div class='row'>
        <div class='col col-6 project-team'>
          <div class='chart-col-title strong'><?php echo $lang->program->teamCount;?></div>
          <?php foreach($projects as $project):?>
          <div class='chart-col-item'>
            <div class='progress'>
              <div class='progress-bar' style='width: <?php echo $maxTeamCount ? (100 * $project->teamCount / $maxTeamCount) : 0; ?>%'>
                <div class='progress-text'><?php echo $project->teamCount;?></div>
              </div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
        <div class='col col-6 project-consumed'>
          <div class='chart-col-title strong'><?php echo $lang->task->consumed;?></div>
          <?php foreach($projects as $project):?>
          <div class='chart-col-item'>
            <div class='progress'>
              <div class='progress-bar' style='width: <?php echo $maxConsumed ? (100 * $project->consumed / $maxConsumed) : 0; ?>%'>
                <div class='progress-text' title="<?php echo $project->consumed . $lang->execution->workHour;?>"><?php echo $project->consumed . $lang->execution->workHourUnit;?></div>
              </div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
      </div>
    </div>
  </div>
  <style>
  .block-projectteam .panel-body {margin-top: -10px}
  .chart-titles {top: 30px; width: 150px; padding-right: 10px; position: relative; z-index: 1}
  .chart-rows {position: relative; z-index: 0}
  .chart-title {width: 100px; line-height: 20px; padding: 5px 0; height: 30px}
  .chart-title > span {background: #fff}
  .chart-col-title {line-height: 20px; margin-bottom: 10px; padding: 0 10px}
  .project-team, .project-consumed {padding: 0; border-left: 1px solid #e5e8ec}
  .chart-col-item {height: 30px; padding: 5px 0}
  .chart-col-text {width: 50px; position: absolute; top: 5px; line-height: 20px; left: 0}
  .project-team .chart-col-item {padding-right: 50px}
  .project-team .chart-col-item:before {content: ' '; z-index: 0; left: -100px; top: 14px; right: 0; border-top: 1px dotted #e5e8ec}
  .project-consumed .chart-col-item {padding-right: 50px}
  .chart-col-item .progress {background: none; border-radius: 0; overflow: visible; margin: 6px 0 0 0; position: relative; z-index: 1}
  .chart-col-item .progress > .progress-bar {border-radius: 0; position: relative; min-width: 1px}
  .project-team .chart-col-item .progress > .progress-bar {background-color: #39cfff}
  .chart-col-item .progress .progress-text {position: absolute; top: -6px; color: #333; width: 50px; line-height: 20px; white-space: nowrap; overflow: visible}
  .project-team .progress .progress-text {text-align: left; right: -55px; padding-right: 5px}
  .project-consumed .progress .progress-text {text-align: left; right: -50px; padding-left: 5px}
  </style>
</div>
<?php endif;?>
