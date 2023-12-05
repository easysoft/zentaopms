<?php
/**
 * The change password  view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     user
 * @version     $Id: editprofile.html.php 2605 2012-02-21 07:22:58Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::import($jsRoot . 'md5.js');?>
<?php if(!isonlybody()):?>
<style>
.main-content{width: 500px; margin: 0 auto;}
</style>
<?php endif;?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><i class='icon-key'></i> <?php echo $lang->my->changePassword;?></h2>
  </div>
  <form method='post' class='form-ajax'>
    <table align='center' class='table table-form w-320px'>
      <tr>
        <th class='rowhead thWidth'><?php echo $lang->user->account;?></th>
        <td><?php echo $user->account . html::hidden('account',$user->account);?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->originalPassword;?></th>
        <td><?php echo html::password('originalPassword', '', "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->newPassword;?></th>
        <td>
          <span class='input-group'>
            <?php echo html::password('password1', '', "class='form-control' onkeyup='checkPassword(this.value)' placeholder='" . zget($lang->user->placeholder->passwordStrength, $config->safe->mode, '') . "'");?>
            <span class='input-group-addon' id='passwordStrength'></span>
          </span>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->user->password2;?></th>
        <td><?php echo html::password('password2', '', "class='form-control'");?></td>
      </tr>
      <tr>
        <td colspan='2' class='text-center form-actions'>
          <?php echo html::hidden('passwordLength', 0);?>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
    <?php if(!empty($this->app->user->modifyPasswordReason)):?>
    <?php $this->app->loadLang('admin');?>
    <div class='alert alert-info'>
      <?php $reason = $this->app->user->modifyPasswordReason;?>
      <?php echo $lang->admin->safe->common . ' : ';?>
      <?php echo $reason == 'weak' ? $lang->admin->safe->changeWeak : $lang->admin->safe->$reason;?>
    </div>
    <?php endif;?>
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
