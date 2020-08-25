<?php if(empty($programs)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<div class='panel-body has-table scrollbar-hover'>
  <table>
    <thead>
      <tr>
        <th class='w-200px'></th>
        <th><?php echo $lang->program->teamCount;?></th>
        <th><?php echo $lang->task->consumed;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($programs as $program):?>
      <tr>
        <td title='<?php echo $program->name?>'><?php echo $program->name;?></td>
        <td><?php echo $program->teamCount;?></td>
        <td><?php echo $program->consumed;?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
