<style> .block-waterfallestimate table{margin-bottom: 15px} </style>
<table class='table table-data'>
  <?php if(isset($config->maxVersion)):?>
  <tr>
    <th class='w-100px'><?php echo $lang->durationestimation->people;?></th>
    <td><?php echo $people ? $people : 0;?></td>
    <th class='w-80px'><?php echo $lang->durationestimation->members;?></th>
    <td><?php echo $members;?></td>
  </tr>
  <?php endif;?>
  <tr>
    <th><?php echo $lang->workestimation->duration;?></th>
    <td><?php echo zget($budget, 'duration', 0);?></td>
    <th><?php echo $lang->workestimation->consumed;?></th>
    <td><?php echo $consumed ? $consumed : 0;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->workestimation->totalLaborCost;?></th>
    <td><?php echo '￥' . zget($budget, 'totalLaborCost', 0);?></td>
  </tr>
</table>
