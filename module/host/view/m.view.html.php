<?php include $app->getModuleRoot() . 'common/view/m.header.html.php';?>
<section id='page' class='section list-with-actions list-with-pager'>
  <div class='heading gray'>
    <div class='title'>
      <strong><?php echo $lang->host->view;?></strong>
    </div>
    <nav class='nav'><a href="javascript:history.go(-1);" class='btn primary'><?php echo $lang->goback;?></a></nav>
  </div>
  <div class='box'>
    <table class="table bordered">
      <tr>
        <th class='w-110px'><?php echo $lang->host->name;?></th>
        <td><?php echo $host->name;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->host->group;?></th>
        <td><?php echo zget($optionMenu, $host->group, '');?></td>
      </tr>
      <tr>
        <th><?php echo $lang->host->serverRoom;?></th>
        <td><?php echo zget($rooms, $host->serverRoom, "")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->host->serverModel;?></th>
        <td><?php echo $host->serverModel;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->host->hostType;?></th>
        <td><?php echo $lang->host->hostTypeList[$host->hostType];?></td>
      </tr>
      <tr>
        <th><?php echo $lang->host->cpuBrand;?></th>
        <td><?php echo $host->cpuBrand;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->host->cpuModel;?></th>
        <td><?php echo $host->cpuModel;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->host->cpuNumber;?></th>
        <td><?php echo $host->cpuNumber;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->host->cpu;?></th>
        <td><?php echo $host->cpu;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->host->memory;?></th>
        <td><?php if($host->memory) echo $host->memory . ' GB';?></td>
      </tr>
      <tr>
        <th><?php echo $lang->host->disk;?></th>
        <td><?php if($host->disk) echo $host->disk . ' GB';?></td>
      </tr>
      <tr>
        <th><?php echo $lang->host->intranet;?></th>
        <td><?php echo $host->intranet;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->host->extranet;?></th>
        <td><?php echo $host->extranet;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->host->osName;?></th>
        <td><?php echo $host->osName;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->host->osVersion;?></th>
        <td><?php echo $lang->host->{$host->osName.'List'}[$host->osVersion];?>
      </tr>
      <tr>
        <th><?php echo $lang->host->status;?></th>
        <td><?php echo $lang->host->statusList[$host->status];?></td>
      </tr>
    </table>
  </div>
    <?php include $app->getModuleRoot() . 'common/view/m.action.html.php'?>
</section>
<?php include $app->getModuleRoot() . 'common/view/m.footer.html.php';?>
