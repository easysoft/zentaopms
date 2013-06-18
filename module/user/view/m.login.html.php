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
  <h1><?php echo $lang->user->mobile->login;?></h1>
</div>
<div data-role="content" >
<form method='post' target='hiddenwin'>
  <?php
  echo html::input('account', '', "placeholder='{$lang->user->account}'");
  echo html::password('password', '', "placeholder='{$lang->user->password}'");
  echo html::select('lang', $config->langs, $this->app->getClientLang(), 'onchange=selectLang(this.value)');
  echo html::checkBox('keepLogin', $lang->user->keepLogin, $keepLogin);
  echo html::submitButton($lang->login, "data-inline='true' data-theme='b'");
  if($app->company->guest) echo html::linkButton($lang->user->asGuest, $this->createLink($config->default->module), '', "data-inline='true'");
  echo html::hidden('referer', $referer);
  ?>
</form>
</div>
<?php include '../../common/view/m.footer.lite.html.php';?>
