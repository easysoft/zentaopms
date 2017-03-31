<?php
/**
 * The testtask view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include './featurebar.html.php';?>
<div class='sub-featurebar'>
  <ul class='nav'>
    <?php
    echo "<li id='testtaskTab'>"  . html::a($this->createLink('user', 'testtask', "account=$account"),  $lang->user->testTask2Him) . "</li>";
    echo "<li id='case2HimTab'>"  . html::a($this->createLink('user', 'testcase', "account=$account&type=case2Him"),  $lang->user->case2Him) . "</li>";
    echo "<li id='caseByHimTab'>" . html::a($this->createLink('user', 'testcase', "account=$account&type=caseByHim"),  $lang->user->caseByHim) . "</li>";
    ?>
  </ul>
</div>
<table class='table tablesorter table-fixed'>
  <?php $vars = "account=$account&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
  <thead>
  <tr class='colhead'>
    <th class='w-id'>  <?php common::printOrderLink('id',      $orderBy, $vars, $lang->idAB);?></th>
    <th>               <?php common::printOrderLink('name',    $orderBy, $vars, $lang->testtask->name);?></th>
    <th>               <?php common::printOrderLink('project', $orderBy, $vars, $lang->testtask->project);?></th>
    <th>               <?php common::printOrderLink('build',   $orderBy, $vars, $lang->testtask->build);?></th>
    <th class='w-80px'><?php common::printOrderLink('begin',   $orderBy, $vars, $lang->testtask->begin);?></th>
    <th class='w-80px'><?php common::printOrderLink('end',     $orderBy, $vars, $lang->testtask->end);?></th>
    <th class='w-50px'><?php common::printOrderLink('status',  $orderBy, $vars, $lang->statusAB);?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($tasks as $task):?>
  <tr class='text-center'>
    <td><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id"), sprintf('%03d', $task->id));?></td>
    <td class='text-left nobr'><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id"), $task->name);?></td>
    <td class='nobr'><?php echo $task->projectName?></td>
    <td class='nobr'><?php $task->build == 'trunk' ? print($lang->trunk) : print(html::a($this->createLink('build', 'view', "buildID=$task->build"), $task->buildName));?></td>
    <td><?php echo $task->begin?></td>
    <td><?php echo $task->end?></td>
    <td class='task-<?php echo $task->status?>'><?php echo $lang->testtask->statusList[$task->status];?></td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='7'><?php $pager->show();?></td></tr></tfoot>
</table>
<script language="Javascript">$("#testTab").addClass('active'); $("#testtaskTab").addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
