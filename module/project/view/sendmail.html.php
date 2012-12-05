<?php
/**
 * The mail file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi<shiyangyang@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<table width='98%' align='center'>
  <tr class='header'>
    <td>
      TASK #<?php echo $task->id . "=>$task->assignedTo " . html::a(common::getSysURL() . $this->createLink('task', 'view', "taskID=$task->id"), $task->name);?>
    </td>
  </tr>
  <tr>
    <td>
    <fieldset>
      <legend><?php echo $lang->task->legendDesc;?></legend>
      <div class='content'>
      <?php 
      if(strpos($task->desc, 'src="data/upload'))
      {
        $task->desc = str_replace('<img src="', '<img src="http://' . $this->server->http_host . $this->config->webRoot, $task->desc);
        $task->desc = str_replace('<img alt="" src="', '<img src="http://' . $this->server->http_host . $this->config->webRoot, $task->desc);
      }
      echo $task->desc;
      ?>
      </div>
    </fieldset>
    </td>
  </tr>
  <tr>
    <td><?php include '../../common/view/mail.html.php';?></td>
  </tr>
</table>
