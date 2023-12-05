<?php
/**
 * The testtask view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include './featurebar.html.php';?>
<div id='mainContent'>
  <nav id='contentNav'>
    <ul class='nav nav-default'>
      <?php
      $that = zget($lang->user->thirdPerson, $user->gender);
      echo "<li class='active'>" . html::a($this->createLink('user', 'testtask', "userID={$user->id}"), sprintf($lang->user->testTask2Him, $that)) . "</li>";
      ?>
    </ul>
  </nav>

  <div class='main-table'>
    <table class='table has-sort-head'>
      <?php $vars = "userID={$user->id}&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
      <thead>
        <tr>
          <th class='c-id'>    <?php common::printOrderLink('id',      $orderBy, $vars, $lang->idAB);?></th>
          <th>                 <?php common::printOrderLink('name',    $orderBy, $vars, $lang->testtask->name);?></th>
          <th>                 <?php common::printOrderLink('project', $orderBy, $vars, $lang->testtask->execution);?></th>
          <th>                 <?php common::printOrderLink('build',   $orderBy, $vars, $lang->testtask->build);?></th>
          <th class='c-date'>  <?php common::printOrderLink('begin',   $orderBy, $vars, $lang->testtask->begin);?></th>
          <th class='c-date'>  <?php common::printOrderLink('end',     $orderBy, $vars, $lang->testtask->end);?></th>
          <th class='c-status'><?php common::printOrderLink('status',  $orderBy, $vars, $lang->statusAB);?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        $waitCount    = 0;
        $testingCount = 0;
        $blockedCount = 0;
        $doneCount    = 0;
        ?>
        <?php foreach($tasks as $task):?>
        <?php if($task->status == 'wait')    $waitCount ++;?>
        <?php if($task->status == 'doing')   $testingCount ++;?>
        <?php if($task->status == 'blocked') $blockedCount ++;?>
        <?php if($task->status == 'done')    $doneCount ++;?>
        <tr>
          <td><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id", '', true), sprintf('%03d', $task->id), '', "class='iframe'");?></td>
          <td class='text-left nobr'><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id", '', true), $task->name, '', "class='iframe'");?></td>
          <td class='nobr'><?php echo $task->executionName?></td>
          <td class='nobr'><?php $task->build == 'trunk' ? print($lang->trunk) : print(html::a($this->createLink('build', 'view', "buildID=$task->build"), $task->buildName));?></td>
          <td><?php echo $task->begin?></td>
          <td><?php echo $task->end?></td>
          <td class='status-testtask status-<?php echo $task->status?>'><?php echo $this->processStatus('testtask', $task);?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($tasks):?>
    <div class="table-footer">
      <div class="table-statistic"><?php echo sprintf($lang->testtask->allSummary, count($tasks), $waitCount, $testingCount, $blockedCount, $doneCount);?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
