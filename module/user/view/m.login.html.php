<?php
/**
 * The html template file of login method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: login.html.php 4612 2013-03-18 07:46:16Z wwccss $
 */
include '../../common/view/m.header.lite.html.php';
?>
<div data-role="header" data-position="fixed">
  <h1><?php echo $lang->user->mobileWeb->login;?></h1>
</div>
<div data-role="content" >
<form method='post' target='hiddenwin'>
  <table align='center'> 
    <tr>
      <td class='rowhead'><?php echo $lang->user->account;?>：</td>  
      <td><input type='text' name='account' id='account' /></td>
    </tr>  
    <tr>
      <td class='rowhead'><?php echo $lang->user->password;?>：</td>  
      <td><input type='password' name='password' /></td>
    </tr>
    <tr><td></td><td id='keeplogin'><?php echo html::checkBox('keepLogin', $lang->user->keepLogin, $keepLogin);?></td></tr>
    <tr>
      <td colspan='2' align='center'>
      <?php 
      echo html::submitButton($lang->login, "data-inline='true' data-theme='b'");
      if($app->company->guest) echo html::linkButton($lang->user->asGuest, $this->createLink($config->default->module), '', "data-inline='true'");
      echo html::hidden('referer', $referer);
      ?>
      </td>
    </tr>  
  </table>
</form>
</div>
<?php include '../../common/view/m.footer.lite.html.php';?>
