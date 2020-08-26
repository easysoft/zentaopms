<div class='panel-body scroll-table' style='padding: 0;'>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th rowspan='2'><?php echo $lang->milestone->workHours;?></th>
        <th class='text-center' colspan='<?php echo count($workhours) - 1;?>'><?php echo $lang->milestone->allStage;?></th>
        <th rowspan='2' class='text-center'><?php echo $lang->milestone->colSummary;?></th>
        <th rowspan='2' class='text-center'><?php echo $lang->milestone->colPercent;?></th>
      </tr>
      <tr>
        <?php foreach($workhours as $id => $stage):?>
        <?php if($id == 'count') continue;?>
        <th><?php echo $stage['name'];?></th>
        <?php endforeach;?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th><?php echo $lang->milestone->devHours;?></th>
        <?php foreach($workhours as $id => $stage):?>
        <?php if($id == 'count') continue;?>
        <td><?php echo $stage['dev'];?></td>
        <?php endforeach;?>
        <td class='text-center'><?php echo $workhours['count']['dev'];?></td>
        <td class='text-center'><?php echo $workhours['count']['total'] == '0' ? '0%' : round($stage['dev'] / $workhours['count']['total'], 2) * 100 . '%';?> </td>
      </tr>
      <tr>
        <th><?php echo $lang->milestone->toHours;?></th>
        <?php foreach($workhours as $id => $stage):?>
        <?php if($id == 'count') continue;?>
        <td><?php echo $stage['to'];?></td>
        <?php endforeach;?>
        <td class='text-center'><?php echo $workhours['count']['to'];?></td>
        <td class='text-center'><?php echo $workhours['count']['total'] == '0' ? '0%' : round($stage['to'] / $workhours['count']['total'], 2) * 100 . '%';?> </td>
      </tr>
      <tr>
        <th><?php echo $lang->milestone->reviewHours;?></th>
        <?php foreach($workhours as $id => $stage):?>
        <?php if($id == 'count') continue;?>
        <td><?php echo $stage['review'];?></td>
        <?php endforeach;?>
        <td class='text-center'><?php echo $workhours['count']['review'];?></td>
        <td class='text-center'><?php echo $workhours['count']['total'] == '0' ? '0%' : round($stage['review'] / $workhours['count']['total'], 2) * 100 . '%';?> </td>
      </tr>
      <tr>
        <th><?php echo $lang->milestone->qaHours;?></th>
        <?php foreach($workhours as $id => $stage):?>
        <?php if($id == 'count') continue;?>
        <td><?php echo $stage['qa'];?></td>
        <?php endforeach;?>
        <td class='text-center'><?php echo $workhours['count']['qa'];?></td>
        <td class='text-center'><?php echo $workhours['count']['total'] == '0' ? '0%' : round($stage['qa'] / $workhours['count']['total'], 2) * 100 . '%';?> </td>
      </tr>
      <tr>
        <th><?php echo $lang->milestone->rowSummary;?></th>
        <?php foreach($workhours as $id => $stage):?>
        <?php if($id == 'count') continue;?>
        <td><?php echo $stage['count'];?></td>
        <?php endforeach;?>
        <td class='text-center'><?php echo $workhours['count']['total'];?></td>
        <td class='text-center'><?php echo $workhours['count']['total'] == '0' ? '0%' : round($stage['total'] / $workhours['count']['total'], 2) * 100 . '%';?> </td>
      </tr>
      <tr>
        <th><?php echo $lang->milestone->rowPercent;?></th>
        <?php foreach($workhours as $id => $stage):?>
        <?php if($id == 'count') continue;?>
        <td><?php echo $workhours['count']['total'] == 0 ? '0%' : round($stage['count'] / $workhours['count']['total'] * 100, 2) . '%';?></td>
        <?php endforeach;?>
        <td class='text-center'>100%</td>
        <td class='text-center'>100%</td>
      </tr>
      <tr>
        <th><?php echo $lang->milestone->qatoDev;?></th>
        <?php foreach($workhours as $id => $stage):?>
        <?php if($id == 'count') continue;?>
        <td><?php echo $stage['qaToDev'];?></td>
        <?php endforeach;?>
        <td></td>
        <td></td>
      </tr>
    </tbody>
  </table>
</div>
<div class='cell chart-row'>
  <div class='main-col'>
    <div class='chart-wrapper text-center'>
      <h4><?php echo $lang->milestone->chart->workhour;?></h4>      
      <div class='chart-canvas'><canvas id='chart-workhour' width='400' height='140' data-responsive='true'></canvas></div>
    </div>
  </div>
  <table class='table table-chart hidden' data-chart='bar' data-target='#chart-workhour' data-animation='false'>
    <tbody>
    <?php foreach($workhours as $id => $stage):?>
    <?php if($id == 'count') continue;?>
    <tr>
      <td class='chart-label text-left'><?php echo $stage['name'];?></td>
      <td class='chart-value text-right'><?php echo $workhours['count']['total'] == 0 ? '0%' : round($stage['count'] / $workhours['count']['total'] * 100, 2) . '%';?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>
