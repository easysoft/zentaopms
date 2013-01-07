<?php
/**
 * The browse view file of product dept of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php 
include '../../common/view/header.html.php';
include '../../common/view/treeview.html.php';
include '../../common/view/colorize.html.php';
js::set('deptID', $deptID);
?>
<table class='cont-lt1'>
  <tr><td colspan='3'><div id='querybox'><?php echo $searchForm?></div></td></tr>
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo $lang->dept->common;?></div>
      <div class='box-content'>
        <?php echo $deptTree;?>
        <div class='a-right'>
          <?php 
          common::printLink('user', 'create', "dept=$deptID&from=company", $lang->user->create);echo '<br />';
          common::printLink('company', 'browse', '', $lang->user->allUsers); echo '<br />';
          common::printLink('dept', 'browse', '', $lang->dept->manage);
          ?>
        </div>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1 tablesorter colored'>
        <thead>
        <tr class='colhead'>
          <?php $vars = "param=$param&type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
          <th class='w-id'><?php common::printorderlink('id', $orderBy, $vars, $lang->idAB);?></th>
          <th><?php common::printorderlink('realname', $orderBy, $vars, $lang->user->realname);?></th>
          <th><?php common::printOrderLink('account',  $orderBy, $vars, $lang->user->account);?></th>
          <th><?php common::printOrderLink('role',     $orderBy, $vars, $lang->user->role);?></th>
          <th><?php common::printOrderLink('email',    $orderBy, $vars, $lang->user->email);?></th>
          <th><?php common::printOrderLink('gander',   $orderBy, $vars, $lang->user->gender);?></th>
          <th><?php common::printOrderLink('phone',    $orderBy, $vars, $lang->user->phone);?></th>
          <th><?php common::printOrderLink('join',     $orderBy, $vars, $lang->user->join);?></th>
          <th><?php common::printOrderLink('last',     $orderBy, $vars, $lang->user->last);?></th>
          <th><?php common::printOrderLink('visits',   $orderBy, $vars, $lang->user->visits);?></th>
          <th class='w-80px'><?php echo $lang->actions;?></th>
        </tr>
        </thead>
        <tbody>
        <form action='<?php echo $this->createLink('user', 'batchEdit', "deptID=$deptID")?>' method='post' id='userListForm'>
        <?php foreach($users as $user):?>
        <tr class='a-center'>
          <td><?php echo "<input type='checkbox' name='users[]' value='$user->account'> "; printf('%03d', $user->id);?></td>
          <td><?php if(!common::printLink('user', 'view', "account=$user->account", $user->realname)) echo $user->realname;?></td>
          <td><?php echo $user->account;?></td>
          <td><?php echo $lang->user->roleList[$user->role];?></td>
          <td><?php echo html::mailto($user->email);?></td>
          <td><?php if(isset($lang->user->genderList[$user->gender])) echo $lang->user->genderList[$user->gender];?></td>
          <td><?php echo $user->phone;?></td>
          <td><?php echo $user->join;?></td>
          <td><?php echo date('Y-m-d', $user->last);?></td>
          <td><?php echo $user->visits;?></td>
          <td class='a-left'>
            <?php 
            common::printIcon('user', 'edit',   "userID=$user->id&from=company", '', 'list');
            common::printIcon('user', 'delete', "userID=$user->id", '', 'list', '', "hiddenwin");
            if((strtotime($user->locked) - strtotime(date('Y-m-d'))) >= 0) common::printLink('user', 'unlock', "userID=$user->account", $lang->company->unlock, "hiddenwin");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
          <td colspan='11'>
          <?php
          echo html::selectAll() . html::selectReverse();
          echo html::submitButton($lang->user->batchEdit, 'onclick=batchEdit()');
          echo html::submitButton($lang->user->contacts->manage, 'onclick=manageContacts()');
          $pager->show();
          ?>
          </td>
        </tr>
        </tfoot>
      </table>
    </td>
  </tr>
</table>
<script lanugage='javascript'>$('#dept<?php echo $deptID;?>').addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
