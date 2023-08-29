<?php include $app->getModuleRoot() . 'common/view/m.header.html.php';?>
<section id='page' class='section list-with-actions list-with-pager'>
  <table class="table bordered">
    <thead>
    <?php $vars = "browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
    <tr>
      <th><?php common::printOrderLink('city', $orderBy, $vars, $lang->serverroom->city);?></th>
      <th class='w-90px'><?php common::printOrderLink('line',      $orderBy, $vars, $lang->serverroom->line);?></th>
      <th class='w-80px'><?php common::printOrderLink('bandwidth', $orderBy, $vars, $lang->serverroom->bandwidth);?></th>
      <th class='w-80px'><?php common::printOrderLink('provider',  $orderBy, $vars, $lang->serverroom->provider);?></th>
    </tr>
    </thead>
    <?php if(!empty($serverRoomList)):?>
    <tbody>
    <?php foreach($serverRoomList as $serverRoom):?>
    <tr data-url="<?php echo $this->inlink('view', "id=$serverRoom->id");?>">
      <td><?php echo $serverRoom->name?><?php if(!empty($serverRoom->city)):?>(<?php echo zget($lang->serverroom->cityList, $serverRoom->city)?>)<?php endif;?></td>
      <td><?php echo zget($lang->serverroom->lineList, $serverRoom->line);?></td>
      <td><?php echo $serverRoom->bandwidth;?></td>
      <td><?php echo zget($lang->serverroom->providerList, $serverRoom->provider);?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
    <?php endif;?>
  </table>
  <nav class='nav justify pager'>
    <?php $pager->show($align = 'justify');?>
  </nav>
</section>
<?php include $app->getModuleRoot() . 'common/view/m.footer.html.php';?>
