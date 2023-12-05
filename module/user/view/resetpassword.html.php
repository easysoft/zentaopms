<?php
/**
 * The html template file of resetpassword method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     ZenTaoPMS
 * @version     $Id: resetpassword.html.php 5084 2022-06-21 09:47:38Z $
 */
include '../../common/view/header.lite.html.php';
?>
<?php js::import($jsRoot . 'md5.js');?>
<?php js::set('expired', $expired);?>
<?php if($expired):?>
<style>body {background: none;}</style>
<div class="alert alert-danger">
  <div class="content"><?php echo $lang->user->linkExpired . sprintf($lang->user->jumping, inlink('login'));?></div>
</div>
<?php else:?>
<main id="main" class="fade no-padding">
  <div class="container" id="reset">
    <div id="resetPanel">
      <h2 id='title'><?php echo $lang->user->resetPWD;?></h2>
      <div class="table-row">
        <form method='post' id='resetForm' class='form-ajax'>
          <table class='table table-form'>
            <tbody>
              <div class='form-group'>
                <label for='password1'><?php echo $lang->user->password;?></label>
                <span class='input-group'>
                  <?php echo html::password('password1', '', "class='form-control' required placeholder='" . zget($lang->user->placeholder->loginPassword, $config->safe->mode, '') . "' onkeyup='checkPassword(this.value)'");?>
                  <span class='input-group-addon' id='passwordStrength'></span>
                </span>
              </div>
              <div class='form-group'>
                <label for='password2'><?php echo $lang->user->abbr->password2;?></label>
                <?php echo html::password('password2', '', "class='form-control' required placeholder='{$lang->user->placeholder->loginPassword}'");?>
              </div>
              <tr>
                <td colspan='2' class="form-actions text-center">
                  <?php
                  echo html::hidden('account', $user->account);
                  echo html::hidden('passwordStrength', '');
                  echo html::hidden('passwordLength', 0);
                  echo html::submitButton($lang->user->submit);
                  echo html::a(inlink('login'), $lang->goback, '', 'class="btn btn-wide"');
                  ?>
                </td>
              </tr>
            </tbody>
          </table>
        </form>
        <?php echo html::hidden('verifyRand', $rand);?>
      </div>
    </div>
  </div>
</div>
<?php endif;?>
<?php js::set('passwordStrengthList', $lang->user->passwordStrengthList)?>
<?php include '../../common/view/footer.lite.html.php';?>
