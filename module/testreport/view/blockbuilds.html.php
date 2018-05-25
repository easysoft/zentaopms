<?php $sysURL = $this->session->notHead ? common::getSysURL() : '';?>
<table class='table main-table' id='builds'>
  <thead>
    <tr>
      <th class='w-id'>  <?php echo $lang->build->id;?></th>
      <th class='text-left'><?php echo $lang->build->name;?></th>
      <th class='w-user'><?php echo $lang->build->builder;?></th>
      <th class='w-100px'><?php echo $lang->build->date;?></th>
    </tr>
  </thead>
  <?php if($builds):?>
  <tbody>
    <?php foreach($builds as $build):?>
    <tr>
      <td><?php echo sprintf('%03d', $build->id) . html::hidden('builds[]', $build->id)?></td>
      <td class='text-left' title='<?php echo $build->name?>'><?php echo html::a($sysURL . $this->createLink('build', 'view', "buildID=$build->id", '', true), $build->name, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
      <td><?php echo zget($users, $build->builder);?></td>
      <td><?php echo $build->date;?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
  <?php else:?>
  <tr><td class='none-data' colspan='4'><?php echo 'Trunk'?></td></tr>
  <?php endif;?>
</table>
