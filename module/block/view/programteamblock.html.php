<?php if(empty($programs)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<?php
$maxTeamCount = 0;
$maxConsumed  = 0;
?>
<div class='panel-body'>
  <div class='table-row'>
    <div class='table-col chart-titles'>
      <?php foreach($programs as $program):?>
      <?php
      $maxTeamCount = max($maxTeamCount, $program->teamCount);
      $maxConsumed  = max($maxConsumed, $program->consumed);
      ?>
      <div class='chart-title text-ellipsis'><span><?php echo $program->name;?></span></div>
      <?php endforeach;?>
    </div>
    <div class='table-col chart-rows'>
      <div class='row'>
        <div class='col col-6 program-team'>
          <div class='chart-col-title strong'><?php echo $lang->program->teamCount;?></div>
          <?php foreach($programs as $program):?>
          <div class='chart-col-item'>
            <div class='progress'>
              <div class='progress-bar' style='width: <?php echo $maxTeamCount ? (100 * $program->teamCount / $maxTeamCount) : 0; ?>%'>
                <div class='progress-text'><?php echo $program->teamCount;?></div>
              </div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
        <div class='col col-6 program-consumed'>
          <div class='chart-col-title strong'><?php echo $lang->task->consumed;?></div>
          <?php foreach($programs as $program):?>
          <div class='chart-col-item'>
            <div class='progress'>
              <div class='progress-bar' style='width: <?php echo $maxConsumed ? (100 * $program->consumed / $maxConsumed) : 0; ?>%'>
                <div class='progress-text'><?php echo $program->consumed;?></div>
              </div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
      </div>
    </div>
  </div>
  <style>
  .block-programteam .panel-body {margin-top: -10px}
  .chart-titles {width: 110px; padding-right: 10px; padding-top: 30px; position: relative; z-index: 1}
  .chart-rows {position: relative; z-index: 0}
  .chart-title {width: 100px; line-height: 20px; padding: 5px 0; height: 30px}
  .chart-title > span {background: #fff}
  .chart-col-title {line-height: 20px; margin-bottom: 10px; padding: 0 10px}
  .program-team, .program-consumed {padding: 0}
  .program-team {border-right: 1px solid #e5e8ec}
  .program-team > .chart-col-title {text-align: right}
  .chart-col-item {height: 30px; padding: 5px 0; position: relative}
  .chart-col-text {width: 50px; position: absolute; top: 5px; line-height: 20px; left: 0}
  .program-team .chart-col-item {padding-left: 50px; position: relative}
  .program-team .chart-col-item:before {position: absolute; content: ' '; z-index: 0; left: -100px; top: 14px; right: 0; border-top: 1px dotted #e5e8ec}
  .program-consumed .chart-col-item {padding-right: 50px}
  .chart-col-item .progress {background: none; border-radius: 0; overflow: visible; margin: 6px 0 0 0; position: relative; z-index: 1}
  .chart-col-item .progress > .progress-bar {border-radius: 0; position: relative; min-width: 1px}
  .program-team .chart-col-item .progress > .progress-bar {float: right; background-color: #39cfff}
  .chart-col-item .progress .progress-text {position: absolute; top: -6px; color: #333; width: 50px; line-height: 20px; white-space: nowrap; overflow: visible}
  .program-team .progress .progress-text {text-align: right; left: -50px; padding-right: 5px}
  .program-consumed .progress .progress-text {text-align: left; right: -50px; padding-left: 5px}
  </style>
</div>
<?php endif;?>
