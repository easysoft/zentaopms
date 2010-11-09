<?php
/**
 * The html template file of step3 method of install module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author	  Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package	 ZenTaoMS
 * @version	 $Id$
 */
?>
<?php include './header.html.php';?>
<?php
if(!isset($error))
{
    $configContent = <<<EOT
<?php
\$config->installed       = true;	//标志是否已经安装。
\$config->debug           = false;	//是否打开debug功能。如果系统运行不正常，可将其设为true。
\$config->requestType     = '$requestType';	//如何获取当前请求的信息，可选值：PATH_INFO|GET。
\$config->db->host        = '$dbHost';	//mysql主机。
\$config->db->port        = '$dbPort';	//mysql主机端口号。
\$config->db->name        = '$dbName';	//数据库名称。
\$config->db->user        = '$dbUser';	//数据库用户名。
\$config->db->password    = '$dbPassword';		//密码。
\$config->db->prefix      = '$dbPrefix';	//表前缀。
\$config->webRoot         = '{$this->post->webRoot}';		//web网站的根目录。如果后面pms的目录有变化，需要修改此选项。
\$config->default->domain = '$domain';	//默认域名。
EOT;
}
?>
<div class='yui-d0'>
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
</div>
<?php include './footer.html.php';?>
