<?php
/**
 * The browse view file of product dept of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php 
include '../../common/view/header.html.php';
include '../../common/view/treeview.html.php';
js::set('deptID', $deptID);
js::set('confirmDelete', $lang->user->confirmDelete);
?>
<div id='titlebar'>
  <div class='heading'><?php echo html::icon($lang->icons['company']);?> <?php echo $lang->company->browse;?></div>
</div>
<div id='querybox' class='show'><?php echo $searchForm?></div>
<div class='side'>
  <a class='side-handle' data-id='companyTree'><i class='icon-caret-left'></i></a>
  <div class='side-body'>
    <div class='panel panel-sm'>
      <div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']);?> <strong><?php echo $lang->dept->common;?></strong></div>
      <div class='panel-body'>
        <?php echo $deptTree;?>
        <div class='text-right'><?php common::printLink('dept', 'browse', '', $lang->dept->manage);?></div>
      </div>
    </div>
  </div>
</div>
<div class='main'>
<form action='<?php echo $this->createLink('user', 'batchEdit', "deptID=$deptID")?>' method='post' id='userListForm'>
  <table class='table table-condensed table-hover table-striped tablesorter table-fixed' id='userList'>
    <thead>
    <tr class='colhead'>
      <?php $vars = "param=$param&type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
      <th class='w-id'><?php common::printorderlink('id', $orderBy, $vars, $lang->idAB);?></th>
      <th><?php common::printorderlink('realname', $orderBy, $vars, $lang->user->realname);?></th>
      <th><?php common::printOrderLink('account',  $orderBy, $vars, $lang->user->account);?></th>
      <th><?php common::printOrderLink('role',     $orderBy, $vars, $lang->user->role);?></th>
      <th><?php common::printOrderLink('email',    $orderBy, $vars, $lang->user->email);?></th>
      <th><?php common::printOrderLink('gender',   $orderBy, $vars, $lang->user->gender);?></th>
      <th><?php common::printOrderLink('phone',    $orderBy, $vars, $lang->user->phone);?></th>
      <th><?php common::printOrderLink('qq',       $orderBy, $vars, $lang->user->qq);?></th>
      <th><?php common::printOrderLink('join',     $orderBy, $vars, $lang->user->join);?></th>
      <th><?php common::printOrderLink('last',     $orderBy, $vars, $lang->user->last);?></th>
      <th><?php common::printOrderLink('visits',   $orderBy, $vars, $lang->user->visits);?></th>
      <th class='w-80px'><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <tbody>
    
    <?php 
    $canBatchEdit      = common::hasPriv('user', 'batchEdit');
    $canManageContacts = common::hasPriv('user', 'manageContacts');
    ?>
    <?php foreach($users as $user):?>
    <tr class='text-center'>
      <td>
        <?php 
        if($canBatchEdit or $canManageContacts) echo "<input type='checkbox' name='users[]' value='$user->account'> ";
        printf('%03d', $user->id);
        ?>
      </td>
      <td><?php if(!common::printLink('user', 'view', "account=$user->account", $user->realname)) echo $user->realname;?></td>
      <td><?php echo $user->account;?></td>
      <td><?php echo $lang->user->roleList[$user->role];?></td>
      <td><?php echo html::mailto($user->email);?></td>
      <td><?php if(isset($lang->user->genderList[$user->gender])) echo $lang->user->genderList[$user->gender];?></td>
      <td><?php echo $user->phone;?></td>
      <td><?php if($user->qq) echo html::a("tencent://message/?uin=$user->qq", $user->qq);?></td>
      <td><?php echo $user->join;?></td>
      <td><?php if($user->last) echo date('Y-m-d', $user->last);?></td>
      <td><?php echo $user->visits;?></td>
      <td class='text-left'>
        <?php 
        common::printIcon('user', 'edit',      "userID=$user->id&from=company", '', 'list');
        if(strpos($this->app->company->admins, ",{$user->account},") === false and common::hasPriv('user', 'delete'))
        {
            $deleteURL = $this->createLink('user', 'delete', "userID=$user->id&confirm=yes");
            echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"userList\",confirmDelete)", '<i class="icon-remove"></i>', '', "title='{$lang->user->delete}' class='btn-icon'");
        }
        if((strtotime(date('Y-m-d H:i:s')) - strtotime($user->locked)) < $this->config->user->lockMinutes * 60) 
        {
            common::printIcon('user', 'unlock', "userID=$user->account", '', 'list', '', "hiddenwin");
        }
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
    <tfoot>
    <tr>
      <td colspan='12'>
      <div class='table-actions clearfix'>
      <?php
      if($canBatchEdit or $canManageContacts) echo "<div class='btn-group'>" . html::selectButton() . '</div>';
      if($canBatchEdit) echo html::submitButton($lang->edit, 'onclick=batchEdit()', 'btn-default');
      if($canManageContacts) echo html::submitButton($lang->user->contacts->manage, 'onclick=manageContacts()');
      ?>
      </div>
      <?php echo $pager->show();?>
      </td>
    </tr>
    </tfoot>
  </table>
</form>
</div>
<script lanugage='javascript'>$('#dept<?php echo $deptID;?>').addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
