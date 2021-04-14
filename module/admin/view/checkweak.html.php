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
<div class="main-row">
  <div class='side-col' id='sidebar'>
    <div class='cell'>
      <div class='list-group'>
        <?php
        echo html::a($this->createLink('admin', 'safe'), $lang->admin->safe->set);
        echo html::a($this->createLink('admin', 'checkWeak'), $lang->admin->safe->checkWeak, '', "class='active'");
        ?>
      </div>
    </div>
  </div>
  <div id='mainContent' class="main-col main-content">
    <div class='main-table pd-0'>
      <table class='table table-condensed table-hover table-striped table-fixed'>
        <thead>
          <th class='c-id-sm text-center'><?php echo $lang->idAB;?></th>
          <th class='w-150px'><?php echo $lang->user->realname;?></th>
          <th><?php echo $lang->user->account;?></th>
          <th class='w-150px'><?php echo $lang->user->phone;?></th>
          <th class='w-150px'><?php echo $lang->user->mobile;?></th>
          <th class='w-200px'><?php echo $lang->admin->safe->reason;?></th>
          <th class='c-actions-2'><?php echo $lang->actions;?></th>
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
            <td class='c-actions text-center'><?php common::printIcon('user', 'edit', "userID=$user->id", '', 'list');?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
