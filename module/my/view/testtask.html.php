<?php
/**
 * The testtask view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='featurebar'>
  <div class='f-left'>
    <?php
    echo "<span id='testtaskTab'>"   . html::a($this->createLink('my', 'testtask'),  $lang->my->testTask) . "</span>";
    echo "<span id='assigntomeTab'>" . html::a($this->createLink('my', 'testcase', "type=assigntome"),  $lang->testcase->assignToMe) . "</span>";
    //echo "<span id='donebymeTab'>"   . html::a($this->createLink('my', 'testcase', "type=donebyme"),    $lang->testcase->doneByMe)   . "</span>";
    echo "<span id='closedbymeTab'>" . html::a($this->createLink('my', 'testcase', "type=openedbyme"),  $lang->testcase->openedByMe) . "</span>";
    ?>
  </div>
</div>
<table class='table-1 colored tablesorter fixed'>
  <?php $vars = "orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
  <thead>
  <tr class='colhead'>
    <th class='w-id'> <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
    <th> <?php common::printOrderLink('name', $orderBy, $vars, $lang->testtask->name);?></th>
    <th> <?php common::printOrderLink('project', $orderBy, $vars, $lang->testtask->project);?></th>
    <th> <?php common::printOrderLink('build', $orderBy, $vars, $lang->testtask->build);?></th>
    <th class='w-80px'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->testtask->begin);?></th>
    <th class='w-80px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->testtask->end);?></th>
    <th class='w-50px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->statusAB);?></th>
    <th class='w-80px'><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($tasks as $task):?>
  <tr class='a-center'>
    <td><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id"), sprintf('%03d', $task->id));?></td>
    <td class='a-left nobr'><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id"), $task->name);?></td>
    <td class='nobr'><?php echo $task->projectName?></td>
    <td class='nobr'><?php $task->build == 'trunk' ? print('Trunk') : print(html::a($this->createLink('build', 'view', "buildID=$task->build"), $task->buildName));?></td>
    <td><?php echo $task->begin?></td>
    <td><?php echo $task->end?></td>
    <td><?php echo $lang->testtask->statusList[$task->status];?></td>
    <td class='a-right'>
      <?php
      common::printLink('testtask', 'cases',    "taskID=$task->id", $lang->testtask->cases);
      common::printIcon('testtask', 'edit',     "taskID=$task->id", '', 'list');
      common::printIcon('testtask', 'delete',   "taskID=$task->id", '', 'list', '', 'hiddenwin');
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='8'><?php $pager->show();?></td></tr></tfoot>
</table>
<script language="Javascript">$("#testtaskTab").addClass('active'); </script>
<?php include '../../common/view/footer.html.php';?>
