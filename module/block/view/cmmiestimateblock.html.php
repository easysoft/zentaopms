<style> .block-cmmiestimate table{margin-bottom: 15px} </style>
<table class='table table-data'>
  <tr>
    <th class='w-100px'><?php echo $lang->durationestimation->people;?></th>
    <td><?php echo $people;?></td>
    <th class='w-80px'><?php echo $lang->durationestimation->members;?></th>
    <td><?php echo $members;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->workestimation->duration;?></th>
    <td><?php echo zget($budget, 'duration', 0) . ' ' . $lang->workestimation->hour;?></td>
    <th><?php echo $lang->workestimation->consumed;?></th>
    <td><?php echo zget($budget, 'productivity', '') . ' ' . $lang->workestimation->hour;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->workestimation->totalLaborCost;?></th>
    <td><?php echo '￥' . zget($budget, 'totalLaborCost', 0);?></td>
  </tr>
</table>
