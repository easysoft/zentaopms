<div class='panel-body scroll-table' style='padding: 0;'>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th rowspan='2'><?php echo $lang->milestone->quality->identify;?></th>
        <th class='text-center' colspan='<?php if(isset($productQuality['stages'])) echo count($productQuality['stages']);?>'>
        <?php echo $lang->milestone->quality->injection;?></th>
        <th rowspan='2'><?php echo $lang->milestone->quality->scale;?></th>
        <th rowspan='2'><?php echo $lang->milestone->quality->identifyRate;?></th>
      </tr>
      <tr>
        <?php if(isset($productQuality['stages'])):?>
        <?php foreach($productQuality['stages'] as $stages):?>
        <th><?php echo $stages['name'];?></th>
        <?php endforeach;?>
        <?php endif;?>
      </tr>
    </thead>
    <tbody>
      <?php foreach($productQuality['reviews'] as $reviewID => $reviewName):?>
      <tr>
        <td><?php echo $reviewName;?></td>
        <?php foreach($productQuality['stages'] as $stages):?>
        <td><?php echo $stages[$reviewID]['counts'];?></td>
        <?php endforeach;?>
        <td></td>
        <td></td>
      </tr>
      <?php endforeach;?>
      <tr>
        <th><?php echo $lang->milestone->quality->total;?></th>
        <?php if(isset($productQuality['stages'])):?>
        <?php foreach($productQuality['stages'] as $stages):?>
        <td><?php echo $stages['total'];?></td>
        <?php endforeach;?>
        <?php endif;?>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->milestone->quality->injectionRate;?>
        </th>
        <?php if(isset($productQuality['stages'])):?>
        <?php foreach($productQuality['stages'] as $stages):?>
        <td></td>
        <?php endforeach;?>
        <?php endif;?>
        <td></td>
        <td></td>
      </tr>
    </tbody>
  </table>
</div>
