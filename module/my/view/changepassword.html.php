<?php
/**
 * The change password  view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     user
 * @version     $Id: editprofile.html.php 2605 2012-02-21 07:22:58Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-500px'>
  <div id='titlebar'>
    <div class='heading'><i class='icon-key'></i> <?php echo $lang->my->changePassword;?></div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin'>
    <table align='center' class='table table-form w-300px'> 
      <tr>
        <th class='rowhead w-80px'><?php echo $lang->user->account;?></th>
        <td><?php echo $user->account . html::hidden('account',$user->account);?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->user->originalPassword;?></th>
        <td><?php echo html::password('originalPassword', '', "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->user->password;?></th>
        <td>
          <span class='input-group'>
            <?php echo html::password('password1', '', "class='form-control' autocomplete='off' onmouseup='checkPassword(this.value)' onkeyup='checkPassword(this.value)' placeholder='" . (!empty($config->safe->mode) ? $lang->user->placeholder->passwordStrength[$config->safe->mode] : '') . "'");?>
            <span class='input-group-addon' id='passwordStrength'></span>
          </span>
        </td>
      </tr>  
      <tr>
        <th><?php echo $lang->user->password2;?></th>
        <td><?php echo html::password('password2', '', "class='form-control'");?></td>
      </tr>
      <tr>
        <td></td>
        <td><?php echo html::submitButton() . html::backButton();?></td>
      </tr>
    </table>
    <?php if(!empty($this->app->user->modifyPasswordReason)):?>
    <?php $this->app->loadLang('admin');?>
    <div class='alert alert-info'>
      <?php echo $lang->admin->safe->common . ' : ';?>
      <?php echo $this->app->user->modifyPasswordReason == 'weak' ? $lang->admin->safe->changeWeak : $lang->admin->safe->modifyPasswordFirstLogin;?>
    </div>
    <?php endif;?>
  </form>  
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
