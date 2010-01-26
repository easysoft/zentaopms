<?php
/**
 * The html template file of deny method of user module of ZenTaoMS.
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
  <link rel='stylesheet' href='<?php echo $app->getClientTheme() . 'yui.css';?>'   type='text/css' media='screen' />
  <link rel='stylesheet' href='<?php echo $app->getClientTheme() . 'style.css';?>' type='text/css' media='screen' />
  </head>
<body>
<div class='yui-d0' style='margin-top:100px'>
    <table align='center' class='table-3'> 
      <caption><?php echo $lang->user->deny;?></caption>
      <tr>
        <td>
          <?php
          $moduleName = isset($lang->$module->common)  ? $lang->$module->common:  $module;
          $methodName = isset($lang->$module->$method) ? $lang->$module->$method: $method;

          printf($lang->user->errorDeny, $moduleName, $methodName);
          echo "<br />";
          echo html::a($this->createLink($config->default->module), $lang->index->common);
          echo html::a(helper::safe64Decode($refererBeforeDeny), $lang->user->goback);
          echo html::a($this->createLink('user', 'logout', "referer=" . helper::safe64Encode($denyPage)), $lang->user->relogin);
          ?>
        </td>
      </tr>  
    </table>
  </form>
</div>  
</body>
</html>
