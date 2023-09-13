<?php include $app->getModuleRoot() . 'common/view/m.header.html.php';?>
<section id='page' class='section list-with-actions list-with-pager'>
  <table class="table bordered">
    <thead>
    <tr>
      <?php $vars = "browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
      <th class='text-left'><?php common::printOrderLink('group',     $orderBy, $vars, $lang->host->group);?></th>
      <th class='text-left'><?php common::printOrderLink('name',      $orderBy, $vars, $lang->host->name);?></th>
      <th class='w-110px'>  <?php common::printOrderLink('intranet', $orderBy, $vars, $lang->host->intranet);?></th>
      <th class='w-70px'>   <?php common::printOrderLink('status',    $orderBy, $vars, $lang->host->status);?></th>
    </tr>
    </thead>
    <?php if(!empty($hostList)):?>
    <tbody>
    <?php foreach($hostList as $host):?>
    <tr class='text-left' data-url="<?php echo $this->inlink('view', "id=$host->id");?>">
      <td class='hidden-xs hidden-sm'><?php echo zget($optionMenu, $host->group, '');?></td>
      <td><?php echo $host->name;?></td>
      <td><?php echo $host->intranet;?></td>
      <td class='hidden-xs hidden-sm'><?php echo $lang->host->statusList[$host->status];?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
    <?php endif;?>
  </table>
  <?php if(!empty($hostList)):?>
  <nav class='nav justify pager'>
    <?php $pager->show($align = 'justify');?>
  </nav>
  <?php endif;?>
</section>
<?php include $app->getModuleRoot() . 'common/view/m.footer.html.php';?>
