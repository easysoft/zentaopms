<?php
/**
 * The html template file of step3 method of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author	  Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package	 ZenTaoPMS
 * @version	 $Id: step3.html.php 4297 2013-01-27 07:51:45Z wwccss $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php
if(!isset($error))
{
    $configContent = <<<EOT
<?php
\$config->installed       = true;
\$config->debug           = false;
\$config->requestType     = '$requestType';
\$config->db->host        = '$dbHost';
\$config->db->port        = '$dbPort';
\$config->db->name        = '$dbName';
\$config->db->user        = '$dbUser';
\$config->db->password    = '$dbPassword';
\$config->db->prefix      = '$dbPrefix';
\$config->webRoot         = getWebRoot();
\$config->default->lang   = '$defaultLang';
EOT;
}
?>
<div class='container'>
  <div class='modal-dialog'>
  <?php if(isset($error)):?>
    <div class='modal-header'>
      <strong><?php echo $lang->install->error;?></strong>
    </div>
    <div class='modal-body'>
      <div class='alert alert-danger alert-pure with-icon'>
        <i class='icon-info-sign'></i>
        <div class='content'><?php echo $error;?></div>
      </div>
    </div>
    <div class='modal-footer'>
      <?php echo html::commonButton($lang->install->pre, "onclick='javascript:history.back(-1)'");?>
    </div>
  <?php else:?>
    <div class='modal-header'>
      <strong><?php echo $lang->install->saveConfig;?></strong>
    </div>
    <div class='modal-body'>
      <div class='form-group'>
        <?php echo html::textArea('config', $configContent, "rows='15' class='form-control'");?>
      </div>
      <div class='help-block text-center'>
        <?php
        $configRoot   = $this->app->getConfigRoot();
        $myConfigFile = $configRoot . 'my.php';
        if(is_writable($configRoot))
        {
            if(@file_put_contents($myConfigFile, $configContent))
            {
                printf($lang->install->saved2File, $myConfigFile);
            }
            else
            {
                printf($lang->install->save2File, $this->app->getConfigRoot() . 'my.php');
            }
        }
        else
        {
            printf($lang->install->save2File, $this->app->getConfigRoot() . 'my.php');
        }
        ?>
      </div>
    </div>
    <div class='modal-footer'><?php echo html::a($this->createLink('install', 'step4'), $lang->install->next, '', "class='btn btn-primary'");?></div>
  <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
