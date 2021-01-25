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
?>
<div id='mainContent' class='main-row fade'>
  <div class='main-col'>
    <div class="cell" id="queryBox" data-module='user'></div>
    <form class='main-table table-user' data-ride='table' method='post' data-nested='true' data-checkable='false' id='userListForm'>
      <table class='table has-sort-head' id='userList'>
        <thead>
        <tr>
          <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
          <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
          <th class='c-name'><?php common::printOrderlink('realname', $orderBy, $vars, $lang->user->realname);?></th>
          <th class='w-120px'><?php common::printOrderLink('account', $orderBy, $vars, $lang->user->account);?></th>
          <th class="w-80px"><?php common::printOrderLink('role', $orderBy, $vars, $lang->user->role);?></th>
          <th class="w-150px"><?php common::printOrderLink('email', $orderBy, $vars, $lang->user->email);?></th>
          <th class="w-80px"><?php common::printOrderLink('gender', $orderBy, $vars, $lang->user->gender);?></th>
          <th class='w-100px'><?php common::printOrderLink('phone', $orderBy, $vars, $lang->user->phone);?></th>
          <th class='w-100px'><?php !empty($this->config->isINT) ? common::printOrderLink('skype', $orderBy, $vars, $lang->user->skype) : common::printOrderLink('qq', $orderBy, $vars, $lang->user->qq);?></th>
          <th class="w-100px"><?php common::printOrderLink('last', $orderBy, $vars, $lang->user->last);?></th>
          <th class="w-90px"><?php common::printOrderLink('visits', $orderBy, $vars, $lang->user->visits);?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($users as $user):?>
        <tr>
          <td class='c-id'><?php printf('%03d', $user->id);?></td>
          <td><?php if(!common::printLink('user', 'view', "userID=$user->id&from=my", $user->realname, '', "title='$user->realname' data-group='my'")) echo $user->realname;?></td>
          <td><?php echo $user->account;?></td>
          <td class="w-90px" title='<?php echo zget($lang->user->roleList, $user->role, '');?>'><?php echo zget($lang->user->roleList, $user->role, '');?></td>
          <td class="c-url" title="<?php echo $user->email;?>"><?php echo html::mailto($user->email);?></td>
          <td class="c-type"><?php echo zget($lang->user->genderList, $user->gender, $user->gender);?></td>
          <td><?php echo $user->phone;?></td>
          <td><?php echo !empty($this->config->isINT) ? $user->skype : ($user->qq ? html::a("tencent://message/?uin=$user->qq#open=my", $user->qq) : '');?></td>
          <td class='c-date'><?php if($user->last) echo date('Y-m-d', $user->last);?></td>
          <td class='c-num text-center'><?php echo $user->visits;?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <?php if($users):?>
      <div class='table-footer'>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
