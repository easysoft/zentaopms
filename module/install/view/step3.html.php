<?php
/**
 * The html template file of step3 method of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: step3.html.php 4297 2013-01-27 07:51:45Z wwccss $
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
\$config->timezone        = '$timezone';
\$config->db->driver      = '$dbDriver';
\$config->db->host        = '$dbHost';
\$config->db->port        = '$dbPort';
\$config->db->name        = '$dbName';
\$config->db->user        = '$dbUser';
\$config->db->encoding    = '$dbEncoding';
\$config->db->password    = '$dbPassword';
\$config->db->prefix      = '$dbPrefix';
\$config->webRoot         = getWebRoot();
\$config->default->lang   = '$defaultLang';
EOT;
    if($customSession) $configContent .= "\n\$config->customSession = true;";
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
        <i class='icon-exclamation-sign'></i>
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
        <?php echo html::textarea('config', $configContent, "rows='15' class='form-control'");?>
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
                printf($lang->install->save2File, $myConfigFile);
            }
        }
        else
        {
            printf($lang->install->save2File, $myConfigFile);
        }
        ?>
      </div>
    </div>
    <div class='modal-footer'><?php echo html::a($this->createLink('install', 'step4'), $lang->install->next, '', "class='btn btn-wide btn-primary'");?></div>
  <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
