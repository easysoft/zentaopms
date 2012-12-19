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
include '../../common/view/tablesorter.html.php';
?>
<table class='cont-lt1'>
  <tr>
    <td colspan='3'><div id='querybox'><?php echo $searchForm?></div></td>
  </tr>
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
      <table class='table-1 tablesorter'>
        <thead>
        <tr class='colhead'>
          <th class='w-id'><?php echo $lang->idAB;?></th>
          <th><?php echo $lang->user->realname;?></th>
          <th><?php echo $lang->user->account;?></th>
          <th><?php echo $lang->user->role;?></th>
          <th><?php echo $lang->user->email;?></th>
          <th><?php echo $lang->user->gender;?></th>
          <th><?php echo $lang->user->phone;?></th>
          <th><?php echo $lang->user->join;?></th>
          <th><?php echo $lang->user->last;?></th>
          <th><?php echo $lang->user->visits;?></th>
          <th class='w-80px'><?php echo $lang->actions;?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($users as $user):?>
        <tr class='a-center'>
          <td><?php echo html::checkbox('userIDList[]', $user->id) . $user->id;?></td>
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
            echo html::selectAll();
            echo html::selectReverse();
            $pager->show();
?>
</td>
</tr>
</tfoot>
      </table>
    </td>
  </tr>
</table>
<script lanugage='Javascript'>$('#dept<?php echo $deptID;?>').addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
