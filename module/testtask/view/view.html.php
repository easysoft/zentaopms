<?php
/**
 * The view file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<table class='table-1'> 
  <caption>TASK #<?php echo $task->id . ' ' . $task->name;?></caption>
  <tr>
    <th class='rowhead'><?php echo $lang->testtask->name;?></th>
    <td class='<?php if($task->deleted) echo 'deleted';?>'><?php echo $task->name;?>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->testtask->project;?></th>
    <td><?php echo $task->projectName;?></td>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->testtask->build;?></th>
    <td><?php $task->buildName ? print($task->buildName) : print($task->build);?></td>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->testtask->owner;?></th>
    <td><?php echo $users[$task->owner];?></td>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->testtask->pri;?></th>
    <td><?php echo $task->pri;?></td>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->testtask->begin;?></th>
    <td><?php echo $task->begin;?></td>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->testtask->end;?></th>
    <td><?php echo $task->end;?></td>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->testtask->status;?></th>
    <td><?php echo $lang->testtask->statusList[$task->status];?></td>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->testtask->desc;?></th>
    <td class='content'><?php echo $task->desc;?></td>
  </tr>  
</table>
<div class='a-center f-16px strong'>
  <?php
  $browseLink = $this->session->testtaskList ? $this->session->testtaskList : $this->createLink('testtask', 'browse', "productID=$task->product");
  if(!$task->deleted)
  {
      common::printLink('testtask', 'cases',    "taskID=$task->id", $lang->testtask->cases);
      common::printLink('testtask', 'linkcase', "taskID=$task->id", $lang->testtask->linkCaseAB);
      common::printLink('testtask', 'edit',   "taskID=$task->id",$lang->edit);
      common::printLink('testtask', 'delete', "taskID=$task->id", $lang->delete, 'hiddenwin');
      echo html::a($browseLink, $lang->goback);
  }
  ?>
</div>
<?php include '../../common/view/action.html.php';?>
<?php include '../../common/view/footer.html.php';?>
