<?php
/**
 * The html template file of login method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: login.html.php 5084 2013-07-10 01:31:38Z wyd621@gmail.com $
 */
include '../../common/view/header.lite.html.php';
if(empty($config->notMd5Pwd))js::import($jsRoot . 'md5.js');
?>
<?php js::set('loginTimeoutTip', $lang->user->error->loginTimeoutTip);?>
<?php $imgBasePath = $config->webRoot . 'theme/default/images/main/';?>
<?php $zentaodirName = basename($this->app->getBasePath());?>
<main id="main" class="fade no-padding">
  <div class="container" id="login">
    <div id="loginPanel">
      <div class="table-row">
        <div class="col-5 text-center" id='logo-box' style='background-image: url(<?php echo $imgBasePath . $config->user->loginImg['bg'];?>)'>
          <?php
          $logoVerticalMargin = !empty($this->config->safe->loginCaptcha) ? 'top: 80px;' : 'top: 60px;';
          $aiVerticalMargin   = !empty($this->config->safe->loginCaptcha) ? 'bottom: 64px;' : 'bottom: 48px;';
          ?>
          <img id='login-logo' style="<?php echo $logoVerticalMargin;?>" src="<?php echo $imgBasePath . $config->user->loginImg['logo'];?>">
          <img id='login-ai' style="<?php echo $aiVerticalMargin;?>"     src="<?php echo $imgBasePath . $config->user->loginImg['ai'];?>">
        </div>
        <div class="col-7">
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
          <form method='post' target='hiddenwin' id='loginForm'>
            <table class='table table-form'>
              <tbody>
                <?php if($loginExpired):?>
                <p class='text-red'><?php echo $lang->user->loginExpired;?></p>
                <?php endif;?>
                <tr>
                  <td colspan='2'><?php echo $lang->user->account;?></td>
                </tr>
                <tr>
                  <td colspan='2'><input class='form-control' type='text' name='account' id='account' autocomplete='off' autofocus /></td>
                </tr>
                <tr>
                  <td colspan='2'><?php echo $lang->user->password;?></td>
                </tr>
                <tr>
                  <td colspan='2'><input class='form-control' type='password' name='password' autocomplete='off' /></td>
                </tr>
                <?php if(!empty($this->config->safe->loginCaptcha)):?>
                <tr>
                  <td colspan='2'><?php echo $lang->user->captcha;?></td>
                </tr>
                <tr>
                  <td class='captchaBox' colspan='2'>
                    <div class='input-group'>
                      <?php echo html::input('captcha', '', "class='form-control'");?>
                      <span class='input-group-addon'><img src="<?php echo $this->createLink('misc', 'captcha', "sessionVar=captcha");?>" /></span>
                    </div>
                  </td>
                </tr>
                <?php endif;?>
                <tr>
                  <td id="keeplogin"><?php echo html::checkBox('keepLogin', $lang->user->keepLogin, $keepLogin);?></td>
                  <td id='resetPassword'>
                    <?php
                    $resetLink = (isset($this->config->resetPWDByMail) and $this->config->resetPWDByMail) ? inlink('forgetPassword') : inlink('reset');
                    echo html::a($resetLink, $lang->user->resetPassword);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td class="form-actions" colspan='2'>
                  <?php
                  echo html::submitButton($lang->login, '', 'btn btn-primary');
                  if($app->company->guest) echo html::linkButton($lang->user->asGuest, $this->createLink($config->default->module));
                  echo html::hidden('referer', $referer);
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
          <?php if($unsafeSites and !empty($unsafeSites[$zentaodirName])):?>
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
if($unsafeSites and !empty($unsafeSites[$zentaodirName]))
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
