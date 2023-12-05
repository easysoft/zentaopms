<?php
/**
 * The html template file of forgetpassword method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     ZenTaoPMS
 * @version     $Id: forgetpassword.html.php 5084 2022-06-20 15:00:38Z $
 */
include '../../common/view/header.lite.html.php';
?>
<main id="main" class="fade no-padding">
  <div class="container" id="forget">
    <div id="forgetPanel">
      <h2 id='title'><?php echo $lang->admin->resetPWDByMail;?></h2>
      <div class="table-row">
        <form method='post' id='forgetForm'>
          <table class='table table-form'>
            <tbody>
              <div class='form-group'>
                <label for='account'><?php echo $lang->user->account;?></label>
                <?php echo html::input('account', '', "class='form-control' required placeholder='{$lang->user->placeholder->loginAccount}'");?>
              </div>
              <div class='form-group'>
                <label for='email'><?php echo $lang->user->email;?></label>
                <?php echo html::input('email', '', "class='form-control' required placeholder='{$lang->user->placeholder->email}'");?>
              </div>
              <tr><?php echo html::a(inlink('reset'), $lang->user->resetTitle, '', "class='resetBox'");?></tr>
              <tr>
                <td colspan='2' class="form-actions text-center">
                  <?php
                  echo html::submitButton($lang->user->submit);
                  echo html::a(inlink('login'), $lang->goback, '', 'class="btn btn-wide"');
                  ?>
                </td>
              </tr>
            </tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
