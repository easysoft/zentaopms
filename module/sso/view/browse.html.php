<?php include '../../common/view/header.html.php';?>
<table class='table-1 fixed colored tablesorter datatable'>
  <caption class='caption-tl pb-10px'>
    <div class='f-left'><?php echo $lang->sso->browse;?></div>
    <div class='f-right'>
      <?php common::printIcon('sso', 'create');?>
    </div>
  </caption>
  <thead>
    <tr class='colhead'>
      <th class='w-100px'><?php echo $lang->sso->title;?></th>
      <th class='w-80px'><?php echo $lang->sso->code;?></th>
      <th width='350'><?php echo $lang->sso->key;?></th>
      <th><?php echo $lang->sso->ip;?></th>
      <th class='w-100px'><?php echo $lang->actions;?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($auths as $code => $auth):?>
    <tr class='a-left'>
      <td><?php echo $auth->title?></td>
      <td><?php echo $code?></td>
      <td><?php echo $auth->key?></td>
      <td><?php echo $auth->ip?></td>
      <td class='a-center'>
        <?php
        common::printIcon('sso', 'edit',   "code=$code");
        common::printIcon('sso', 'delete', "code=$code", '', 'list', '', 'hiddenwin');
        ?>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
<?php include '../../common/view/footer.html.php';?>
