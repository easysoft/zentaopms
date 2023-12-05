<?php
/**
 * The html template file of login method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: login.html.php 5084 2013-07-10 01:31:38Z wyd621@gmail.com $
 */
include '../../common/view/header.lite.html.php';
if(empty($config->notMd5Pwd))js::import($jsRoot . 'md5.js');
?>
<?php js::set('loginTimeoutTip', $lang->user->error->loginTimeoutTip);?>
<?php $zentaoDirName = basename($this->app->getBasePath());?>
<main id="main" class="fade no-padding">
  <div class="container" id="login">
    <div id="loginPanel">
      <header>
        <h2><?php printf($lang->welcome, $app->company->name);?></h2>
        <div class="actions dropdown dropdown-hover" id='langs'>
          <button type='button' class='btn' title='Change Language/更换语言/更換語言'><?php echo $config->langs[$this->app->getClientLang()]; ?> <span class="caret"></span></button>
          <ul class="dropdown-menu pull-right">
            <?php foreach($config->langs as $key => $value):?>
            <li><a class="switch-lang" data-value="<?php echo $key; ?>"><?php echo $value; ?></a></li>
            <?php endforeach;?>
          </ul>
        </div>
      </header>
      <div class="table-row">
        <div class="col-4 text-center" id='logo-box'>
          <img src="<?php echo $config->webRoot . 'theme/default/images/main/' . $this->lang->logoImg;?>" />
        </div>
        <div class="col-8">
          <form method='post' target='hiddenwin'>
            <table class='table table-form'>
              <tbody>
                <?php if($loginExpired):?>
                <p class='text-red'><?php echo $lang->user->loginExpired;?></p>
                <?php endif;?>
                <tr>
                  <th><?php echo $lang->user->account;?></th>
                  <td><input class='form-control' type='text' name='account' id='account' autocomplete='off' autofocus /></td>
                </tr>
                <tr>
                  <th><?php echo $lang->user->password;?></th>
                  <td><input class='form-control' type='password' name='password' autocomplete='off' /></td>
                </tr>
                <?php if(!empty($this->config->safe->loginCaptcha)):?>
                <tr>
                  <th><?php echo $lang->user->captcha;?></th>
                  <td class='captchaBox'>
                    <div class='input-group'>
                      <?php echo html::input('captcha', '', "class='form-control'");?>
                      <span class='input-group-addon'><img src="<?php echo $this->createLink('misc', 'captcha', "sessionVar=captcha");?>" /></span>
                    </div>
                  </td>
                </tr>
                <?php endif;?>
                <tr>
                  <th></th>
                  <td id="keeplogin"><?php echo html::checkBox('keepLogin', $lang->user->keepLogin, $keepLogin);?></td>
                </tr>
                <tr>
                  <td></td>
                  <td class="form-actions">
                  <?php
                  echo html::submitButton($lang->login, '', 'btn btn-primary');
                  if($app->company->guest) echo html::linkButton($lang->user->asGuest, $this->createLink($config->default->module));
                  echo html::hidden('referer', $referer);
                  $resetLink = (isset($this->config->resetPWDByMail) and $this->config->resetPWDByMail) ? inlink('forgetPassword') : inlink('reset');
                  echo html::a($resetLink, $lang->user->resetPassword);
                  ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
      </div>
      <?php if(count($plugins['expired']) > 0 or count($plugins['expiring']) > 0):?>
      <div class="table-row-extension">
        <div id="notice" class="alert alert-info">
        <?php $expiredPlugins  = implode('、', $plugins['expired']);?>
        <?php $expiringPlugins = implode('、', $plugins['expiring']);?>
        <?php $expiredTips     = sprintf($lang->misc->expiredPluginTips, $expiredPlugins);?>
        <?php $expiringTips    = sprintf($lang->misc->expiringPluginTips, $expiringPlugins);?>
        <?php if($expiredPlugins)  $pluginTips = $expiredTips;?>
        <?php if($expiringPlugins) $pluginTips = $expiringTips;?>
        <?php if($expiredPlugins and $expiringPlugins) $pluginTips = $expiredTips . $pluginTips;?>
        <?php $pluginTotal = count($plugins['expired']) + count($plugins['expiring']);?>
        <div class="content"><i class="icon-exclamation-sign text-blue"></i>&nbsp;<?php echo sprintf($lang->misc->expiredCountTips, $pluginTips, $pluginTotal);?></div>
        </div>
      </div>
      <?php endif;?>
      <?php if(!empty($this->config->global->showDemoUsers)):?>
      <?php
      $demoPassword = '123456';
      $md5Password  = md5('123456');
      $demoUsers    = 'productManager,projectManager,dev1,dev2,dev3,tester1,tester2,tester3,testManager';
      $demoUsers    = $this->dao->select('account,password,realname')->from(TABLE_USER)->where('account')->in($demoUsers)->andWhere('deleted')->eq(0)->andWhere('password')->eq($md5Password)->fetchAll('account');
      ?>
      <footer>
        <span><?php echo $lang->user->loginWithDemoUser;?></span>
        <?php
        $link  = inlink('login');
        $link .= strpos($link, '?') !== false ? '&' : '?';
        foreach($demoUsers as $demoAccount => $demoUser)
        {
            if($demoUser->password != $md5Password) continue;
            echo html::a($link . "account={$demoAccount}&password=" . md5($md5Password . $this->session->rand), $demoUser->realname);
        }
        ?>
      </footer>
      <?php endif;?>
    </div>
    <div id="info" class="table-row">
      <div class="table-col text-middle text-center">
        <div id="poweredby">
          <?php if($unsafeSites and !empty($unsafeSites[$zentaoDirName])):?>
          <div><a class='showNotice' href='javascript:showNotice()'><?php echo $lang->user->notice4Safe;?></a></div>
          <?php endif;?>
          <?php if($config->checkVersion):?>
          <iframe id='updater' class='hidden' frameborder='0' width='100%' height='45' scrolling='no' allowtransparency='true' src="<?php echo $this->createLink('misc', 'checkUpdate', "sn=$s");?>"></iframe>
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>
</main>
<?php
if($unsafeSites and !empty($unsafeSites[$zentaoDirName]))
{
    $paths     = array();
    $databases = array();
    $isXampp   = false;
    foreach($unsafeSites as $webRoot => $site)
    {
        $path = $site['path'];
        if(strpos($path, 'xampp') !== false) $isXampp = true;

        $paths[]     = $site['path'];
        $databases[] = $site['database'];
    }

    $process4Safe = $isXampp ? $lang->user->process4DB : $lang->user->process4DIR;
    $process4Safe = sprintf($process4Safe, join(' ', $isXampp ? $databases : $paths));
    js::set('process4Safe', $process4Safe);
}
?>
<?php include '../../common/view/footer.lite.html.php';?>
