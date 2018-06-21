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
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php common::printLink('admin', 'safe', '', "<span class='text'>{$lang->admin->safe->set}</span>", '', "class='btn btn-link'");?>
    <?php common::printLink('admin', 'checkWeak', '', "<span class='text'>{$lang->admin->safe->checkWeak}</span>", '', "class='btn btn-link btn-active-text'");?></li>
  </div>
</div>
<div id='mainContent'>
  <div class='main-table pd-0'>
    <table class='table table-condensed table-hover table-striped table-fixed'>
      <thead>
        <th class='c-id-sm text-center'><?php echo $lang->idAB;?></th>
        <th class='w-150px'><?php echo $lang->user->realname;?></th>
        <th><?php echo $lang->user->account;?></th>
        <th class='w-150px'><?php echo $lang->user->phone;?></th>
        <th class='w-150px'><?php echo $lang->user->mobile;?></th>
        <th class='w-150px'><?php echo $lang->admin->safe->reason;?></th>
        <th class='c-actions-1'><?php echo $lang->actions;?></th>
      </thead>
      <tbody>
        <?php foreach($weakUsers as $user):?>
        <tr>
          <td class='text-center'><?php echo $user->id?></td>
          <td class='text-left'><?php echo $user->realname?></td>
          <td class='text-left'><?php echo $user->account?></td>
          <td><?php echo $user->phone?></td>
          <td><?php echo $user->mobile?></td>
          <td><?php echo $lang->admin->safe->reasonList[$user->weakReason];?></td>
          <td class='c-actions'><?php common::printIcon('user', 'edit', "userID=$user->id", '', 'list');?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
