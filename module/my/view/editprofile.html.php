<?php
/**
 * The edit view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: editprofile.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::import($jsRoot . 'md5.js');?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><i class='icon-pencil'></i> <?php echo $lang->my->editProfile;?></h2>
  </div>
  <form method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'> 
      <caption><?php echo $lang->my->form->lblBasic;?></caption>
      <tr>
        <th class='w-90px'><?php echo $lang->user->realname;?></th>
        <td><?php echo html::input('realname', $user->realname, "class='form-control'");?></td>
        <th class='w-140px'><?php echo $lang->user->email;?></th>
        <td><?php echo html::input('email', $user->email, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->gender;?></th>
        <td><?php echo html::radio('gender', $lang->user->genderList, $user->gender);?></td>
        <th><?php echo $lang->user->birthyear;?></th>
        <td><?php echo html::input('birthday', $user->birthday,"class='form-date form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->join;?></th>
        <td class='text-middle'>
          <?php echo formatTime($user->join);?>
          <?php echo html::hidden('join',$user->join) .  html::select('groups[]', $groups, $userGroups, 'multiple=multiple class="form-control hidden"');?>
        </td>
      </tr>
    </table>
    <table class='table table-form'>
      <caption><?php echo $lang->my->form->lblAccount;?></caption>
      <tr>
        <th class='w-90px'><?php echo $lang->user->account;?></th>
        <td><?php echo html::input('account', $user->account, "class='form-control' readonly='readonly'");?></td>
        <th class='w-140px'><?php echo $lang->user->commiter;?></th>
        <td><?php echo html::input('commiter', $user->commiter, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->password;?></th>
      <td>
        <input type='password' style="display:none"> <!-- Disable input password by browser automatically. -->
        <span class='input-group'>
          <?php echo html::password('password1', '', "class='form-control disabled-ie-placeholder' onmouseup='checkPassword(this.value)' onkeyup='checkPassword(this.value)' placeholder='" . (!empty($config->safe->mode) ? $lang->user->placeholder->passwordStrength[$config->safe->mode] : '') . "'");?>
          <span class='input-group-addon' id='passwordStrength'></span>
        </span>
      </td>
        <th><?php echo $lang->user->password2;?></th>
        <td><?php echo html::password('password2', '', "class='form-control'");?></td>
      </tr>
    </table>
    <table class='table table-form'>
      <caption><?php echo $lang->my->form->lblContact;?></caption>
      <?php if(!empty($config->user->contactField)):?>
      <?php $i = 0;?>
      <?php foreach(explode(',', $config->user->contactField) as $field):?>
      <?php if($i % 2 == 0) echo '<tr>';?>
      <?php $i++;?>
        <th <?php echo $i % 2 == 0 ? "class='w-140px'" : "class='w-90px'"?>><?php echo $lang->user->$field;?></th>
        <td><?php echo html::input($field, $user->$field, "class='form-control'");?></td>
      <?php if($i % 2 == 0) echo '</tr>';?>
      <?php endforeach;?>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->user->address;?></th>
        <td><?php echo html::input('address', $user->address, "class='form-control'");?></td>
        <th><?php echo $lang->user->zipcode;?></th>
        <td><?php echo html::input('zipcode', $user->zipcode, "class='form-control'");?></td>
      </tr>
    </table>
    <table class='table table-form'>
      <caption><?php echo $lang->user->verify;?></caption>
      <tr>
        <th class='w-90px'><?php echo $lang->user->verifyPassword;?></th>
        <td>
          <div class="required required-wrapper"></div>
          <?php echo html::password('verifyPassword', '', "class='form-control disabled-ie-placeholder' placeholder='{$lang->user->placeholder->verify}'");?>
        </td>
        <th class='w-140px'></th>
        <td></td>
      </tr>
    </table>
    <div class='text-center form-actions'><?php echo html::submitButton() . html::backButton();?></div>
  </form>
  <?php echo html::hidden('verifyRand', $rand);?>
</div>
<?php js::set('passwordStrengthList', $lang->user->passwordStrengthList)?>
<script>
function checkPassword(password)
{
    $('#passwordStrength').html(password == '' ? '' : passwordStrengthList[computePasswordStrength(password)]);
    $('#passwordStrength').css('display', password == '' ? 'none' : 'table-cell');
}
</script>
<?php include '../../common/view/footer.html.php';?>
