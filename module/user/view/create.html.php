<?php
/**
 * The create view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::import($jsRoot . 'md5.js');?>
<?php if(!empty($config->safe->mode)) $lang->user->placeholder->password1 = $lang->user->placeholder->passwordStrength[$config->safe->mode]?>
<?php js::set('holders', $lang->user->placeholder);?>
<?php js::set('roleGroup', $roleGroup);?>
<div id="mainContent" class="main-content">
  <div class="center-block">
    <div class="main-header">
      <h2><i class='icon icon-plus'></i> <?php echo $lang->user->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" id="createForm" method="post" target='hiddenwin'>
      <table align='center' class="table table-form">
        <tr>
          <th class='w-verifyPassword'><?php echo $lang->user->dept;?></th>
          <td class='w-p50'><?php echo html::select('dept', $depts, $deptID, "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->account;?></th>
          <td><?php echo html::input('account', '', "class='form-control' autocomplete='off'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->realname;?></th>
          <td><?php echo html::input('realname', '', "class='form-control' autocomplete='off'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->password;?></th>
          <td>
            <input type='password' style="display:none"> <!-- for disable autocomplete all browser -->
            <span class='input-group'>
              <?php echo html::password('password1', '', "class='form-control' autocomplete='off' onmouseup='checkPassword(this.value)' onkeyup='checkPassword(this.value)'");?>
              <span class='input-group-addon' id='passwordStrength'></span>
            </span>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->user->password2;?></th>
          <td><?php echo html::password('password2', '', "class='form-control' autocomplete='off'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->join;?></th>
          <td><?php echo html::input('join', date('Y-m-d'), "class='form-control form-date'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->role;?></th>
          <td><?php echo html::select('role', $lang->user->roleList, '', "class='form-control' onchange='changeGroup(this.value)'");?></td>
          <td><?php echo $lang->user->placeholder->role?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->group;?></th>
          <td><?php echo html::select('group', $groupList, '', "class='form-control chosen'");?></td>
          <td><?php echo $lang->user->placeholder->group?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->email;?></th>
          <td><?php echo html::input('email', '', "class='form-control' autocomplete='off'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->commiter;?></th>
          <td><?php echo html::input('commiter', '', "class='form-control' autocomplete='off'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->gender;?></th>
          <td><?php echo html::radio('gender', (array)$lang->user->genderList, 'm');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->verifyPassword;?></th>
          <td>
            <div class="required required-wrapper"></div>
            <?php echo html::password('verifyPassword', '', "class='form-control disabled-ie-placeholder' autocomplete='off' placeholder='{$lang->user->placeholder->verify}'");?>
          </td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?>
            <?php echo html::backButton('', '', 'btn btn-wide');?>
          </td>
        </tr>
      </table>
    </form>
    <?php echo html::hidden('verifyRand', $rand);?>
  </div>
</div>
<?php js::set('passwordStrengthList', $lang->user->passwordStrengthList)?>
<?php include '../../common/view/footer.html.php';?>
