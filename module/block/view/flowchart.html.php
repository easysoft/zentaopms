<table class="table table-borderless">
  <thead>
    <tr>
      <th><span class="flowchart-title"><?php echo $lang->block->lblFlowchart?></span></th>
      <th><div><span class="flowchart-step">1</span></div></th>
      <th><div><span class="flowchart-step">2</span></div></th>
      <th><div><span class="flowchart-step">3</span></div></th>
      <th><div><span class="flowchart-step">4</span></div></th>
      <th><div><span class="flowchart-step">5</span></div></th>
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
