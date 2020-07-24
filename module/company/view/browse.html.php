<?php
/**
 * The browse view file of product dept of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
js::set('deptID', $deptID);
js::set('confirmDelete', $lang->user->confirmDelete);
?>
<div id='mainMenu' class='clearfix'>
  <div id='sidebarHeader'>
    <div class="title">
      <?php echo empty($dept->name) ? $lang->dept->common : $dept->name;?>
      <?php if($deptID) echo html::a(inlink('browse', "deptID=0"), "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");?>
    </div>
  </div>
  <div class='btn-toolbar pull-left'>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->user->search;?></a>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('user', 'batchCreate', "dept={$deptID}", "<i class='icon icon-plus'></i> " . $lang->user->batchCreate, '', "class='btn btn-secondary'");?>
    <?php
      if(commonModel::isTutorialMode())
      {
          $wizardParams = helper::safe64Encode("dept=$deptID");
          $link = $this->createLink('tutorial', 'wizard', "module=user&method=create&params=$wizardParams");
          echo html::a($link, "<i class='icon icon-plus'></i> {$lang->user->create}", '', "class='btn btn-primary create-user-btn'");
      }
      else
      {
          common::printLink('user', 'create', "dept={$deptID}", "<i class='icon icon-plus'></i> " . $lang->user->create, '', "class='btn btn-primary'");
      }
    ?>
  </div>
</div>
<div id='mainContent' class='main-row fade'>
  <div class='side-col' id='sidebar'>
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class='cell'>
      <?php echo $deptTree;?>
      <div class='text-center'>
        <?php common::printLink('dept', 'browse', '', $lang->dept->manage, '', "class='btn btn-info btn-wide'");?>
      </div>
    </div>
  </div>
  <div class='main-col'>
    <div class="cell" id="queryBox" data-module='user'></div>
    <form class='main-table table-user' data-ride='table' action='<?php echo $this->createLink('user', 'batchEdit', "deptID=$deptID")?>' method='post' id='userListForm'>
      <?php $canBatchEdit = common::hasPriv('user', 'batchEdit');?>
      <table class='table has-sort-head' id='userList'>
        <thead>
        <tr>
          <?php $vars = "param=$param&type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
          <th class='c-id'>
            <?php if($canBatchEdit):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th><?php common::printorderlink('realname', $orderBy, $vars, $lang->user->realname);?></th>
          <th><?php common::printOrderLink('account', $orderBy, $vars, $lang->user->account);?></th>
          <th class="w-90px"><?php common::printOrderLink('role', $orderBy, $vars, $lang->user->role);?></th>
          <th class="c-url"><?php common::printOrderLink('email', $orderBy, $vars, $lang->user->email);?></th>
          <th class="c-type"><?php common::printOrderLink('gender', $orderBy, $vars, $lang->user->gender);?></th>
          <th><?php common::printOrderLink('phone', $orderBy, $vars, $lang->user->phone);?></th>
          <th><?php !empty($this->config->isINT) ? common::printOrderLink('skype', $orderBy, $vars, $lang->user->skype) : common::printOrderLink('qq', $orderBy, $vars, $lang->user->qq);?></th>
          <th class="c-date"><?php common::printOrderLink('last', $orderBy, $vars, $lang->user->last);?></th>
          <th class="w-90px"><?php common::printOrderLink('visits', $orderBy, $vars, $lang->user->visits);?></th>
          <th class='c-actions'><?php echo $lang->actions;?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($users as $user):?>
        <tr>
          <td class='c-id'>
            <?php if($canBatchEdit):?>
            <?php echo html::checkbox('users', array($user->account => '')) . html::a(helper::createLink('user', 'view', "account=$user->account"), sprintf('%03d', $user->id));?>
            <?php else:?>
            <?php printf('%03d', $user->id);?>
            <?php endif;?>
          </td>
          <td><?php if(!common::printLink('user', 'view', "account=$user->account", $user->realname, '', "title='$user->realname'")) echo $user->realname;?></td>
          <td><?php echo $user->account;?></td>
          <td class="w-90px" title='<?php echo zget($lang->user->roleList, $user->role, '');?>'><?php echo zget($lang->user->roleList, $user->role, '');?></td>
          <td class="c-url" title="<?php echo $user->email;?>"><?php echo html::mailto($user->email);?></td>
          <td class="c-type"><?php echo zget($lang->user->genderList, $user->gender, $user->gender);?></td>
          <td><?php echo $user->phone;?></td>
          <td><?php echo !empty($this->config->isINT) ? $user->skype : ($user->qq ? html::a("tencent://message/?uin=$user->qq", $user->qq) : '');?></td>
          <td class='c-date'><?php if($user->last) echo date('Y-m-d', $user->last);?></td>
          <td class='c-num text-center'><?php echo $user->visits;?></td>
          <td class='c-actions'>
            <?php
            if(!empty($config->sso->turnon)) common::printIcon('user', 'unbind', "userID=$user->account", $user, 'list', 'unlink', "hiddenwin");
            common::printIcon('user', 'unlock', "userID=$user->account", $user, 'list', 'unlock', "hiddenwin");
            common::printIcon('user', 'edit', "userID=$user->id&from=company", '', 'list');

            $deleteClass = (strpos($this->app->company->admins, ",{$user->account},") === false and common::hasPriv('user', 'delete')) ? 'btn iframe' : 'btn disabled';
            echo html::a($this->createLink('user', 'delete', "userID=$user->id"), '<i class="icon-trash"></i>', '', "title='{$lang->user->delete}' class='{$deleteClass}'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <?php if($users):?>
      <div class='table-footer'>
        <?php if($canBatchEdit):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class="table-actions btn-toolbar"><?php echo html::submitButton($lang->edit, '', 'btn');?></div>
        <?php endif;?>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
  </div>
</div>
<script lanugage='javascript'>$('#dept<?php echo $deptID;?>').addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
