<table class="table table-borderless">
  <thead>
    <tr>
      <?php for($i = 0; $i < 6; $i++):?>
      <?php if($i == 0):?>
      <th><span class="flowchart-title"><?php echo $lang->block->lblFlowchart?></span></th>
      <?php else:?>
      <th><div><span class="flowchart-step"><?php echo $i?></span></div></th>
      <?php endif;?>
      <?php endfor;?>
    </tr>
  </thead>
  <tbody>
    <?php foreach($lang->block->flowchart as $flowchart):?>
    <tr>
      <?php for($i = 0; $i < 6; $i++):?>
      <?php if($i == 0):?>
      <th><?php echo $flowchart[$i];?></th>
      <?php else:?>
      <td><?php echo zget($flowchart, $i, '')?></td>
      <?php endif;?>
      <?php endfor;?>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
