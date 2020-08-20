<table class="table table-bordered">
  <thead>
    <tr><th colspan="6"><?php echo $lang->milestone->projectRisk;?></th></tr>
    <tr>
      <th><?php echo $lang->milestone->riskCountermove;?></th>
      <th><?php echo $lang->milestone->riskDescriptio;?></th>
      <th><?php echo $lang->milestone->riskPossibility;?></th>
      <th><?php echo $lang->milestone->riskSeriousness;?></th>
      <th><?php echo $lang->milestone->riskFactor;?></th>
      <th><?php echo $lang->milestone->riskMeasures;?></th>
    </tr>
  </thead>
  <tbody>
    <?php $totalRisk = count($projectRisk);?>
    <?php foreach($projectRisk as $risk):?>
    <tr>
      <?php if($totalRisk):?>
      <td rowspan="<?php echo $totalRisk;?>"><strong><?php echo $lang->milestone->riskAccumulate;?></strong></td>
      <?php endif;?>
      <td><?php echo $risk->name;?></td>
      <td><?php echo $risk->impact;?></td>
      <td><?php echo $risk->probability;?></td>
      <td><?php echo $risk->riskindex;?></td>
      <td><?php echo $risk->prevention;?></td>
    </tr>
    <?php $totalRisk = 0;?>
    <?php endforeach;?>
  </tbody>
</table>
