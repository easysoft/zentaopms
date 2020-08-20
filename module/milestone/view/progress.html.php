<table class="table table-bordered">
  <thead>
    <tr>
      <th rowspan="2"><?php echo $lang->milestone->paogressForecast;?></th>
      <th colspan="3" class="text-center"><?php echo $lang->milestone->duration;?></th>
      <th colspan="3" class="text-center"><?php echo $lang->milestone->cost;?></th>
      <th colspan="3" rowspan="2"><?php echo $lang->milestone->forecastResults;?></th>
    </tr>
    <tr>
      <th><?php echo $lang->milestone->plannedValue;?></th>
      <th><?php echo $lang->milestone->predictedValue;?>
        <i class="icon icon-help" title="<?php echo $lang->milestone->predictedValueDesc;?>"></i>
      </th>
      <th><?php echo $lang->milestone->periodDeviation;?></th>
      <th><?php echo $lang->milestone->plannedValue;?></th>
      <th><?php echo $lang->milestone->predictedValue;?></th>
      <th><?php echo $lang->milestone->costDeviation;?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong><?php echo $lang->milestone->nextStage;?></strong></td>
      <td><?php echo $nextMilestone->nextDays;?></td>
      <td>
      <?php
      $nextDuration = empty($process->milestoneSPI) || empty($nextMilestone->nextDays) ? 0 : round($nextMilestone->nextDays/$process->milestoneSPI, 2);
      echo $nextDuration;
      $nextDurationValue = $nextMilestone->nextDays - $nextDuration;
      ?>
      </td>
      <td><?php echo $nextDurationValue;?></td>
      <td><?php echo $nextMilestone->nextHours;?></td>
      <td>
      <?php
      $nextCost = empty($process->nowCPI) || empty($nextMilestone->nextHours) ? 0 : round($nextMilestone->nextHours/$process->milestoneCPI);
      echo $nextCost;
      $nextCostValue = $nextMilestone->nextHours - $nextCost;
      ?>
      </td>
      <td><?php echo $nextCostValue;?></td>
      <td colspan="3">
      <?php
      if($nextDurationValue < 0) echo sprintf($lang->milestone->timeOverrun, abs($nextDurationValue));
      if($nextCostValue < 0) echo sprintf($lang->milestone->costOverrun, abs($nextCostValue));
      ?>
      </td>
    </tr>
    <tr>
      <td><strong><?php echo $lang->milestone->overallProject;?></strong></td>
      <td><?php echo $nextMilestone->totalDays;?></td>
      <td>
      <?php
      $totalDuration = empty($process->milestoneSPI) || empty($nextMilestone->totalDays) ? 0 : round($nextMilestone->totalDays/$process->milestoneSPI, 2);
      echo $totalDuration;
      $totalDurationValue = $nextMilestone->totalDays - $totalDuration;
      ?>
      </td>
      <td><?php echo $totalDurationValue;?></td>
      <td><?php echo $nextMilestone->totalHours;?></td>
      <td>
      <?php
      $totalCost = empty($process->nowCPI) || empty($nextMilestone->totalHours) ? 0 : round($nextMilestone->totalHours/$process->nowCPI, 2);
      echo $totalCost;
      $totalCostValue = $nextMilestone->totalHours - $totalCost;
      ?>
      </td>
      <td><?php echo $totalCostValue;?></td>
      <td colspan="3">
      <?php
      if($totalDurationValue < 0) echo sprintf($lang->milestone->timeOverrun, abs($totalDurationValue));
      if($totalCostValue < 0) echo sprintf($lang->milestone->costOverrun, abs($totalCostValue));
      ?>
      </td>
    </tr>
  </tbody>
</table>
