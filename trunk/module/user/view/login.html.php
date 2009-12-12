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
 * @copyright   Copyright: 2009 Chunsheng Wang
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
  if(isset($header['title']))   echo "<title>$header[title] - $lang->zentaoMS</title>\n";
  if(isset($header['keyword'])) echo "<meta name='keywords' content='$header[keyword]'>\n";
  if(isset($header['desc']))    echo "<meta name='description' content='$header[desc]'>\n";
  ?>
<?php echo js::exportConfigVars();?>
<script src="<?php echo $jsRoot;?>jquery/lib.js" type="text/javascript"></script>
<script src="<?php echo $jsRoot;?>my.js"         type="text/javascript"></script>
<link rel='stylesheet' href='<?php echo $clientTheme . 'yui.css';?>' type='text/css' media='screen' />
<link rel='stylesheet' href='<?php echo $clientTheme . 'style.css';?>' type='text/css' media='screen' />
<script type="text/javascript">loadFixedCSS();</script>
<body onload="document.getElementById('account').focus();">
<div class='yui-d0'>
  <form method='post' style='margin-top:100px'>
    <table align='center' class='table-4'> 
      <caption><?php printf($lang->welcome, $app->company->name);?></caption>
      <tr>
        <th><?php echo $lang->user->account;?></th>  
        <td><input type='text' name='account' id='account' /></td>
      </tr>  
      <tr>
        <th><?php echo $lang->user->password;?></th>  
        <td><input type='password' name='password' /></td>
      </tr>
      <tr>
        <td colspan='2' class='a-center'>
          <?php 
          echo html::submitButton($lang->login);
          echo html::resetButton(); 
          $onclick = "onclick='location.href=\"" . $this->createLink($config->default->module) . "\"'";
          if($app->company->guest) echo html::commonButton($lang->user->asGuest, $onclick);
          echo html::hidden('referer', $referer);
          ?>
        </td>
      </tr>  
    </table>
    <div class='a-center'>powered by <a href='http://www.zentao.cn' target='_blank'>ZenTaoPMS</a>(<?php echo $config->version;?>).</div>
  </form>
</div>  
</body>
</html>
