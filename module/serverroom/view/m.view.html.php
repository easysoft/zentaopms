<?php include $app->getModuleRoot() . 'common/view/m.header.html.php';?>
<section id='page' class='section list-with-actions list-with-pager'>
  <div class='heading gray'>
    <div class='title'>
      <strong><?php echo $lang->serverroom->view;?></strong>
    </div>
    <nav class='nav'><a href="javascript:history.go(-1);" class='btn primary'><?php echo $lang->goback;?></a></nav>
  </div>
  <div class='box'>
    <table class="table bordered">
      <tr>
        <th class='w-100px'><?php echo $lang->serverroom->name;?></th>
        <td class='w-p25-f'><?php echo $serverRoom->name;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->serverroom->city;?></th>
        <td><?php echo zget($lang->serverroom->cityList, $serverRoom->city)?></td>
      </tr>
      <tr>
        <th><?php echo $lang->serverroom->line;?></th>
        <td><?php echo zget($lang->serverroom->lineList, $serverRoom->line);?></td>
      </tr>
      <tr>
        <th><?php echo $lang->serverroom->bandwidth;?></th>
        <td><?php echo $serverRoom->bandwidth;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->serverroom->provider;?></th>
        <td><?php echo zget($lang->serverroom->providerList, $serverRoom->provider);?></td>
      </tr>
      <tr>
        <th><?php echo $lang->serverroom->owner;?></th>
        <td><?php echo zget($users, $serverRoom->owner);?></td>
      </tr>
      <tr>
        <th><?php echo $lang->serverroom->createdBy;?></th>
        <td><?php echo zget($users, $serverRoom->createdBy);?></td>
      </tr>
      <tr>
        <th><?php echo $lang->serverroom->createdDate;?></th>
        <td><?php echo $serverRoom->createdDate;?></td>
      </tr>
    </table>
  </div>
    <?php include $app->getModuleRoot() . 'common/view/m.action.html.php'?>
</section>
<?php include $app->getModuleRoot() . 'common/view/m.footer.html.php';?>
