<?php
/**
 * The safe view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='featurebar'>
  <ul class='nav'>
    <li><?php common::printLink('admin', 'safe', '', $lang->admin->safe->set);?></li>
    <li class='active'><?php common::printLink('admin', 'checkWeak', '', $lang->admin->safe->checkWeak);?></li>
  </ul>
</div>
<div class='container mw-800px'>
  <table class='table table-condensed table-hover table-striped table-fixed'>
    <thead>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th><?php echo $lang->user->realname;?></th>
      <th><?php echo $lang->user->account;?></th>
      <th><?php echo $lang->user->phone;?></th>
      <th><?php echo $lang->user->mobile;?></th>
      <th><?php echo $lang->user->birthyear;?></th>
      <th><?php echo $lang->admin->safe->reason;?></th>
      <th class='w-50px'><?php echo $lang->actions;?></th>
    </thead>
    <tbody class='text-center'>
      <?php foreach($weakUsers as $user):?>
      <tr>
        <td class='text-right'><?php echo $user->id?></td>
        <td class='text-left'><?php echo $user->realname?></td>
        <td class='text-left'><?php echo $user->account?></td>
        <td><?php echo $user->phone?></td>
        <td><?php echo $user->mobile?></td>
        <td><?php echo $user->birthday?></td>
        <td><?php echo $lang->admin->safe->reasonList[$user->weakReason];?></td>
        <td><?php common::printIcon('user', 'edit', "userID=$user->id", '', 'list');?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
