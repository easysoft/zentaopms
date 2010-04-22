<?php
/**
 * The html template file of login method of user module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     ZenTaoMS
 * @version     $Id$
 */
$clientTheme = $this->app->getClientTheme();
$webRoot     = $this->app->getWebRoot();
$jsRoot      = $webRoot . "js/";
$themeRoot   = $webRoot . "theme/";
?>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <?php
  $header = (object)$header;
  if(isset($header->title))   echo "<title>$header->title - $lang->zentaoMS</title>\n";
  if(isset($header->keyword)) echo "<meta name='keywords' content='$header->keyword'>\n";
  if(isset($header->desc))    echo "<meta name='description' content='$header->desc'>\n";
  ?>
<?php echo js::exportConfigVars();?>
<script language='Javascript'>var needPing=false</script>
<script src="<?php echo $jsRoot;?>jquery/lib.js" type="text/javascript"></script>
<script src="<?php echo $jsRoot;?>my.js"         type="text/javascript"></script>
<link rel='stylesheet' href='<?php echo $clientTheme . 'yui.css';?>' type='text/css' media='screen' />
<link rel='stylesheet' href='<?php echo $clientTheme . 'style.css';?>' type='text/css' media='screen' />
<link rel='icon'          href='<?php echo $webRoot;?>favicon.ico' type="image/x-icon" />
<link rel='shortcut icon' href='<?php echo $webRoot;?>favicon.ico' type='image/x-icon' />
<script language='Javascript'>loadFixedCSS();</script>
<style>
html{background-color:#06294e;}
body{background-image:url(theme/default/images/main/loginbg.png); background-position:center top; background-repeat:no-repeat;}
table, tr, td, th, input{ border:none;}
.rowhead{width:280px; font-weight:normal; font-size:14px; text-align:right; color:#fff;}
.text-2 {width:160px; height:22px; background-color:#a4c5e0; border:1px solid #035793; font-size:16px; font-weight:bold}
.pt-20px {padding-top:20px}
.pt-200px{padding-top:200px}
.pt-25px {padding-top:25px}
#debugbar, .helplink{display:none}
#welcome{background:none; border:none; color:#FFF; padding-top:8px;}
#poweredby{color:#fff; margin-top:35px; text-align:center; line-height:1}
#poweredby a {color:#fff}
.button-s, .button-c {padding:3px 5px 3px 5px; width:80px; font-size:14px; font-weight:bold}
</style>
<body onLoad="document.getElementById('account').focus();">
<div class='yui-d0 pt-200px'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-4'> 
      <caption id='welcome'><?php printf($lang->welcome, $app->company->name);?></caption>
      <tr>
        <td class='rowhead pt-25px'><?php echo $lang->user->account;?>：</td>  
        <td class='pt-25px'><input class='text-2' type='text' name='account' id='account' /></td>
      </tr>  
      <tr>
        <td class='rowhead'><?php echo $lang->user->password;?>：</td>  
        <td><input class='text-2' type='password' name='password' /></td>
      </tr>
      <tr>
        <td colspan='2' class='a-center pt-20px'>
        <?php 
        echo html::submitButton($lang->login);
        if($app->company->guest) echo html::linkButton($lang->user->asGuest, $this->createLink($config->default->module));
        echo html::hidden('referer', $referer);
        ?>
        </td>
      </tr>  
    </table>
    <div id='poweredby'>
    powered by <a href='http://www.zentao.cn' target='_blank'>ZenTaoPMS</a>(<?php echo $config->version;?>). <br />
    <script src='http://www.zentao.cn/check.php?v=<?php echo $config->version;?>'></script>
    </div>
  </form>
</div>  
<div class='yui-d0' id='debugbar'><iframe frameborder='0' name='hiddenwin' id='hiddenwin' class='hidden'></iframe></div>
</body>
</html>
