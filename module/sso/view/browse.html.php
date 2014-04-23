<?php include '../../common/view/header.html.php';?>
<div class='container'>
  <div id='titlebar'>
    <div class='heading'><i class='icon-globe'></i> <?php echo $lang->sso->browse;?></div>
    <div class='actions'><?php common::printIcon('sso', 'create');?></div>
  </div>

  <table class='table table-fixed tablesorter'>
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
      <tr class='text-left'>
        <td><?php echo $auth->title?></td>
        <td><?php echo $code?></td>
        <td><?php echo $auth->key?></td>
        <td><?php echo $auth->ip?></td>
        <td class='text-center'>
          <?php
          common::printIcon('sso', 'edit',   "code=$code", '', 'list');
          common::printIcon('sso', 'delete', "code=$code", '', 'list', '', 'hiddenwin');
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
