<?php
/**
 * The html template file of login method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: login.html.php 5084 2013-07-10 01:31:38Z wyd621@gmail.com $
 */
include '../../common/view/header.lite.html.php';
if(empty($config->notMd5Pwd))js::import($jsRoot . 'md5.js');
?>
<div id='container'>
  <div id='login-panel'>
    <div class='panel-head'>
      <h4><?php printf($lang->welcome, $app->company->name);?></h4>
      <div class='panel-actions'>
        <div class='dropdown' id='langs'>
          <button class='btn' data-toggle='dropdown' title='Change Language/更换语言/更換語言'><?php echo $config->langs[$this->app->getClientLang()]; ?> <span class="caret"></span></button>
          <ul class='dropdown-menu'>
            <?php foreach($config->langs as $key => $value):?>
            <li class="<?php echo $key==$this->app->getClientLang()?'active':''; ?>"><a href="###" data-value="<?php echo $key; ?>"><?php echo $value; ?></a></li>
            <?php endforeach;?>
          </ul>
        </div>
      </div>
    </div>
    <div class="panel-content" id="login-form">
      <form method='post' target='hiddenwin' class='form-condensed'>
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->user->account;?></th>
            <td><input class='form-control' type='text' name='account' id='account' /></td>
          </tr>
          <tr>
            <th><?php echo $lang->user->password;?></th>
            <td><input class='form-control' type='password' name='password' /></td>
          </tr>
          <tr>
            <th></th>
            <td id="keeplogin"><?php echo html::checkBox('keepLogin', $lang->user->keepLogin, $keepLogin);?></td>
          </tr>
          <tr>
            <th></th>
            <td>
            <?php 
            echo html::submitButton($lang->login);
            if($app->company->guest) echo '&nbsp; ' . html::linkButton($lang->user->asGuest, $this->createLink($config->default->module));
            echo '&nbsp; ' . html::hidden('referer', $referer);
            echo '&nbsp; ' . html::a(inlink('reset'), $lang->user->resetPassword);
            ?>
            </td>
          </tr>
        </table>
      </form>
    </div>

    <?php if(isset($demoUsers)):?>  
    <div id='demoUsers' class="panel-foot">
      <span><?php echo $lang->user->loginWithDemoUser; ?></span>
      <?php
      $sign = $config->requestType == 'GET' ? '&' : '?';
      if(isset($demoUsers['productManager'])) echo html::a(inlink('login') . $sign . "account=productManager&password=123456", $demoUsers['productManager'], 'hiddenwin');
      if(isset($demoUsers['projectManager'])) echo html::a(inlink('login') . $sign . "account=projectManager&password=123456", $demoUsers['projectManager'], 'hiddenwin');
      if(isset($demoUsers['testManager']))    echo html::a(inlink('login') . $sign . "account=testManager&password=123456",    $demoUsers['testManager'],    'hiddenwin');
      if(isset($demoUsers['dev1']))           echo html::a(inlink('login') . $sign . "account=dev1&password=123456",           $demoUsers['dev1'],           'hiddenwin');
      if(isset($demoUsers['tester1']))        echo html::a(inlink('login') . $sign . "account=tester1&password=123456",        $demoUsers['tester1'],        'hiddenwin');
      ?>  
    </div>  
    <?php endif;?>
  </div>
  <div id="poweredby">
    <?php if($config->checkVersion):?>
    <iframe id='updater' class='hidden' frameborder='0' width='100%' scrolling='no' allowtransparency='true' src="http://api.zentao.net/updater-isLatest-<?php echo $config->version;?>-<?php echo $s;?>.html?lang=<?php echo str_replace('-', '_', $this->app->getClientLang())?>"></iframe>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
