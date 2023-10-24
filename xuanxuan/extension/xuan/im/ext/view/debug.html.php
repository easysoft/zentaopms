<?php
/**
 * The debug view file of chat module of XXB.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     chat
 * @version     $Id$
 * @link        https://xuanim.com
 */
?>
<?php
    if($source == 'x_php')
    {
        helper::cd(dirname(dirname(dirname(__FILE__))) . '/view/');
        include 'xdebug.html.php';
        helper::cd();
        return;
    }
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<?php if($this->app->user->admin != 'super'):?>
<div class='container' id='debugContainer'>
  <div class='notice text-center' style='font-size:24px;'>
    <?php $_SERVER['SCRIPT_NAME'] = '/index.php';?>
    <?php $link = $this->loadModel('user')->isLogon() ? $this->createLink('user', 'logout') : $this->createLink('user', 'login');?>
    <?php printf($lang->im->debugTips, html::a($link, $lang->login));?>
  </div>
</div>
<?php else:?>
<script>
if(!window.v) window.v = {};
window.v.showLog = <?php echo $config->xuanxuan->debug;?>;
</script>
<div class='panel'>
  <div class='panel-heading'><?php echo $lang->im->serverInfo;?></div>
  <table class='table table-form'>
    <tbody>
      <tr>
        <th class='w-80px'><?php echo $lang->im->version;?></th>
        <td><?php echo $config->xuanxuan->version;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->im->key;?></th>
        <td class='code'><?php echo $config->xuanxuan->key;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->im->url;?></th>
        <td><?php echo $domain . $this->config->webRoot . 'x.php';?></td>
      </tr>
    </body>
  </table>
</div>
<?php if(!empty($config->xuanxuan->debug)):?>
<div class='panel'>
  <div class='panel-heading'><?php echo $lang->im->log;?></div>
  <div id='log' class='panel-body code'></div>
</div>
<?php endif;?>
<?php if(!helper::isAjaxRequest()):?>
<div class='text-center'>
  <?php commonModel::printLink('setting', 'xuanxuan', '', $lang->goback, '', "class='btn btn-primary'");?></td>
</div>
<?php endif;?>
<?php endif;?>
<?php if($config->debug) js::import($jsRoot . 'jquery/form/min.js');?>
<?php if(isset($pageJS)) js::execute($pageJS);?>
</body>
</html>
