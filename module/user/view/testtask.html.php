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
          <th class='w-id'>   <?php common::printOrderLink('id',      $orderBy, $vars, $lang->idAB);?></th>
          <th>                <?php common::printOrderLink('name',    $orderBy, $vars, $lang->testtask->name);?></th>
          <th>                <?php common::printOrderLink('project', $orderBy, $vars, $lang->testtask->execution);?></th>
          <th>                <?php common::printOrderLink('build',   $orderBy, $vars, $lang->testtask->build);?></th>
          <th class='w-100px'><?php common::printOrderLink('begin',   $orderBy, $vars, $lang->testtask->begin);?></th>
          <th class='w-100px'><?php common::printOrderLink('end',     $orderBy, $vars, $lang->testtask->end);?></th>
          <th class='w-80px'> <?php common::printOrderLink('status',  $orderBy, $vars, $lang->statusAB);?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($tasks as $task):?>
        <tr>
          <td><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id", '', false, '', true), sprintf('%03d', $task->id));?></td>
          <td class='text-left nobr'><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id", '', false, '', true), $task->name);?></td>
          <td class='nobr'><?php echo $task->executionName?></td>
          <td class='nobr'><?php $task->build == 'trunk' ? print($lang->trunk) : print(html::a($this->createLink('build', 'view', "buildID=$task->build", '', false, $task->project), $task->buildName));?></td>
          <td><?php echo $task->begin?></td>
          <td><?php echo $task->end?></td>
          <td class='task-<?php echo $task->status?>'><?php echo $this->processStatus('testtask', $task);?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($tasks):?>
    <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
