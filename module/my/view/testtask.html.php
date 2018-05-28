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
<?php js::set('confirmDelete', $lang->testtask->confirmDelete)?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    echo html::a(inlink('testtask', "type=wait"),       "<span class='text'>{$lang->testtask->wait}</span>",       '', "class='btn btn-link" . ($type == 'wait' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('testtask', "type=done"),       "<span class='text'>{$lang->testtask->done}</span>",       '', "class='btn btn-link" . ($type == 'done' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('testcase', "type=assigntome"), "<span class='text'>{$lang->testcase->assignToMe}</span>", '', "class='btn btn-link'");
    echo html::a(inlink('testcase', "type=openedbyme"), "<span class='text'>{$lang->testcase->openedByMe}</span>", '', "class='btn btn-link'");
    ?>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <table class="table has-sort-head table-fixed" id='taskList'>
    <?php $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
    <thead>
      <tr>
        <th class='w-id'>   <?php common::printOrderLink('id',      $orderBy, $vars, $lang->idAB);?></th>
        <th>                <?php common::printOrderLink('name',    $orderBy, $vars, $lang->testtask->name);?></th>
        <th>                <?php common::printOrderLink('project', $orderBy, $vars, $lang->testtask->project);?></th>
        <th>                <?php common::printOrderLink('build',   $orderBy, $vars, $lang->testtask->build);?></th>
        <th class='w-90px'> <?php common::printOrderLink('begin',   $orderBy, $vars, $lang->testtask->begin);?></th>
        <th class='w-90px'> <?php common::printOrderLink('end',     $orderBy, $vars, $lang->testtask->end);?></th>
        <th class='w-80px'> <?php common::printOrderLink('status',  $orderBy, $vars, $lang->statusAB);?></th>
        <th class='c-actions-3'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($tasks as $task):?>
      <tr>
        <td class="c-id"><?php printf('%03d', $task->id);?></td>
        <td class='text-left nobr'><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id"), $task->name);?></td>
        <td class='nobr'><?php echo $task->projectName?></td>
        <td class='nobr'><?php $task->build == 'trunk' ? print($lang->trunk) : print(html::a($this->createLink('build', 'view', "buildID=$task->build"), $task->buildName));?></td>
        <td><?php echo $task->begin?></td>
        <td><?php echo $task->end?></td>
        <td class='status-<?php echo $task->status?>'><?php echo $lang->testtask->statusList[$task->status];?></td>
        <td class='c-actions'>
          <?php
          common::printIcon('testtask', 'cases',    "taskID=$task->id", 'play', 'list', 'sitemap');
          common::printIcon('testtask', 'linkCase', "taskID=$task->id", '', 'list', 'link');
          common::printIcon('testtask', 'edit',     "taskID=$task->id", '', 'list');
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php if($tasks):?>
  <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
