<?php
/**
 * The html template file of step3 method of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author	  Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package	 ZenTaoPMS
 * @version	 $Id$
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
\$config->webRoot         = '$webRoot';
\$config->default->domain = '$domain';
\$config->default->lang   = '$defaultLang';
EOT;
}
?>
<?php if(isset($error)):?>
<table class='table-6' align='center'>
<caption><?php echo $lang->install->error;?></caption>
  <tr><td><?php echo $error;?></td></tr>
  <tr><td><?php echo html::commonButton($lang->install->pre, "onclick='javascript:history.back(-1)'");?></td></tr>
</table>
<?php else:?>
<table class='table-6' align='center'>
  <caption><?php echo $lang->install->saveConfig;?></caption>
  <tr>
    <td class='a-center'><?php echo html::textArea('config', $configContent, "rows='15' class='area-1 f-12px'");?></td>
  </tr>
  <tr>
    <td>
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
    echo "<br />";
    echo "<div class='a-center'>" . html::a($this->createLink('install', 'step4'), $lang->install->next) . '</div>';
    ?>
    </td>
  </tr>
</table>
<?php endif;?>
<?php include '../../common/view/footer.lite.html.php';?>
