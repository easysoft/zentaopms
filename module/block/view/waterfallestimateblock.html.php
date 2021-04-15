<style> .block-waterfallestimate table{margin-bottom: 15px} </style>
<table class='table table-data'>
  <tr>
    <th class='w-100px'><?php echo $lang->durationestimation->people;?></th>
    <td><?php echo $people ? $people : 0;?></td>
    <th class='w-100px'><?php echo $lang->workestimation->duration;?></th>
    <td><?php echo zget($budget, 'duration', 0);?></td>
  </tr>
  <tr>
    <th class='w-100px'><?php echo $lang->durationestimation->members;?></th>
    <td><?php echo $members;?></td>
    <th class='w-100px'><?php echo $lang->workestimation->consumed;?></th>
    <td><?php echo $consumed ? $consumed : 0;?></td>
  </tr>
  <tr>
    <th class='w-100px'><?php echo $lang->workestimation->totalLaborCost;?></th>
    <td><?php echo '￥' . zget($budget, 'totalLaborCost', 0);?></td>
    <th class='w-100px'><?php echo $lang->block->remain;?></th>
    <td><?php echo $totalLeft ? $totalLeft : 0;?></td>
  </tr>
</table>
