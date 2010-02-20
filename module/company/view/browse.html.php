<?php
/**
 * The browse view file of product dept of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php 
include '../../common/header.html.php';
include '../../common/treeview.html.php';
include '../../common/tablesorter.html.php';
?>
<div class="yui-d0 yui-t7">
  <div class="yui-b">
    <div class='box-title'><?php echo $lang->dept->common;?></div>
    <div class='box-content'>
      <?php echo $deptTree;?>
      <div class='a-right'>
        <?php 
        common::printLink('dept', 'browse', '', $lang->dept->manage);
        common::printLink('user', 'create', "companyID={$this->app->company->id}&from=company", $lang->user->create);
        common::printLink('company', 'browse', '', $lang->user->allUsers);
        ?>
      </div>
    </div>
  </div>
  <div class="yui-main">
    <div class="yui-b">
      <table class='table-1 tablesorter'>
        <thead>
        <tr class='colhead'>
          <th><?php echo $lang->user->id;?></th>
          <th><?php echo $lang->user->realname;?></th>
          <th><?php echo $lang->user->account;?></th>
          <th><?php echo $lang->user->nickname;?></th>
          <th><?php echo $lang->user->email;?></th>
          <th><?php echo $lang->user->gendar;?></th>
          <th><?php echo $lang->user->phone;?></th>
          <th><?php echo $lang->user->join;?></th>
          <th><?php echo $lang->user->visits;?></th>
          <th><?php echo $lang->user->status;?></th>
          <th><?php echo $lang->actions;?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($users as $user):?>
        <tr class='a-center'>
          <td><?php echo $user->id;?></td>
          <td><?php if(!common::printLink('user', 'view', "account=$user->account", $user->realname)) echo $user->realname;?></td>
          <td><?php echo $user->account;?></td>
          <td><?php echo $user->nickname;?></td>
          <td><?php echo html::mailto($user->email);?></td>
          <td><?php if(isset($lang->user->gendarList->{$user->gendar})) echo $lang->user->gendarList->{$user->gendar};?></td>
          <td><?php echo $user->phone;?></td>
          <td><?php echo $user->join;?></td>
          <td><?php echo $user->visits;?></td>
          <td><?php echo $lang->user->statusList[$user->status];?></td>
          <td>
            <?php 
            common::printLink('user', 'edit',   "userID=$user->id&from=company", $lang->edit);
            if($user->status == 'active') common::printLink('user', 'delete',   "userID=$user->id", $lang->delete,   "hiddenwin");
            if($user->status == 'delete') common::printLink('user', 'activate', "userID=$user->id", $lang->activate, "hiddenwin");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>  
<script lanugage='Javascript'>$('#dept<?php echo $deptID;?>').addClass('active');</script>
<?php include '../../common/footer.html.php';?>
