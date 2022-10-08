<style>
.block-waterfallestimate table{margin-bottom: 15px}
.block-waterfallestimate table th{width: 135px !important}
</style>
<table class='table table-data'>
  <tr>
    <th><?php echo $lang->durationestimation->people;?></th>
    <td><?php echo $people ? $people : 0;?></td>
    <th><?php echo $lang->workestimation->duration;?></th>
    <td><?php echo zget($budget, 'duration', 0);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->durationestimation->members;?></th>
    <td><?php echo $members;?></td>
    <th><?php echo $lang->workestimation->consumed;?></th>
    <td><?php echo $consumed ? $consumed : 0;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->workestimation->totalLaborCost;?></th>
    <td><?php echo '￥' . zget($budget, 'totalLaborCost', 0);?></td>
    <th><?php echo $lang->block->remain;?></th>
    <td><?php echo $totalLeft ? $totalLeft : 0;?></td>
  </tr>
</table>
