<?php
/**
 * The html template file of deny method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 */
include '../../common/view/header.lite.html.php';
?>
<table align='center' class='table-3'> 
  <caption><?php echo $app->user->account, ' ', $lang->user->deny;?></caption>
  <tr>
    <td>
      <?php
      $moduleName = isset($lang->$module->common)  ? $lang->$module->common:  $module;
      $methodName = isset($lang->$module->$method) ? $lang->$module->$method: $method;

      printf($lang->user->errorDeny, $moduleName, $methodName);
      echo "<br />";
      echo html::a($this->createLink($config->default->module), $lang->index->common);
      if($refererBeforeDeny) echo html::a(helper::safe64Decode($refererBeforeDeny), $lang->user->goback);
      echo html::a($this->createLink('user', 'logout', "referer=" . helper::safe64Encode($denyPage)), $lang->user->relogin);
      ?>
    </td>
  </tr>  
</table>
</body>
</html>
