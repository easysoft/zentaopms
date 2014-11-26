<?php
/**
 * The testtask view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->testtask->confirmDelete)?>
<div id='featurebar'>
  <nav class='nav'>
    <?php
    echo "<li id='waitTesttask'>"  . html::a($this->createLink('my', 'testtask', 'type=wait'),  $lang->testtask->wait) . "</li>";
    echo "<li id='doneTesttask'>"  . html::a($this->createLink('my', 'testtask', 'type=done'),  $lang->testtask->done) . "</li>";
    echo "<li id='assigntomeTab'>" . html::a($this->createLink('my', 'testcase', "type=assigntome"),  $lang->testcase->assignToMe) . "</li>";
    echo "<li id='closedbymeTab'>" . html::a($this->createLink('my', 'testcase', "type=openedbyme"),  $lang->testcase->openedByMe) . "</li>";
    ?>
  </nav>
</div>
<table class='table table-condensed table-hover table-striped tablesorter' id='taskList'>
  <?php $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
  <thead>
  <tr class='text-center'>
    <th class='w-id'>  <?php common::printOrderLink('id',      $orderBy, $vars, $lang->idAB);?></th>
    <th>               <?php common::printOrderLink('name',    $orderBy, $vars, $lang->testtask->name);?></th>
    <th>               <?php common::printOrderLink('project', $orderBy, $vars, $lang->testtask->project);?></th>
    <th>               <?php common::printOrderLink('build',   $orderBy, $vars, $lang->testtask->build);?></th>
    <th class='w-80px'><?php common::printOrderLink('begin',   $orderBy, $vars, $lang->testtask->begin);?></th>
    <th class='w-80px'><?php common::printOrderLink('end',     $orderBy, $vars, $lang->testtask->end);?></th>
    <th class='w-50px'><?php common::printOrderLink('status',  $orderBy, $vars, $lang->statusAB);?></th>
    <th class='w-100px'><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($tasks as $task):?>
  <tr class='text-center'>
    <td><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id"), sprintf('%03d', $task->id));?></td>
    <td class='text-left nobr'><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id"), $task->name);?></td>
    <td class='nobr'><?php echo $task->projectName?></td>
    <td class='nobr'><?php $task->build == 'trunk' ? print('Trunk') : print(html::a($this->createLink('build', 'view', "buildID=$task->build"), $task->buildName));?></td>
    <td><?php echo $task->begin?></td>
    <td><?php echo $task->end?></td>
    <td class='status-<?php echo $task->status?>'><?php echo $lang->testtask->statusList[$task->status];?></td>
    <td class='text-right'>
      <?php
      common::printIcon('testtask', 'cases',    "taskID=$task->id", 'play', 'list', 'smile');
      common::printIcon('testtask', 'linkCase', "taskID=$task->id", '', 'list', 'link');
      common::printIcon('testtask', 'edit',     "taskID=$task->id", '', 'list');

      if(common::hasPriv('testtask', 'delete'))
      {
          $deleteURL = $this->createLink('testtask', 'delete', "taskID=$task->id&confirm=yes");
          echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"taskList\",confirmDelete)", '<i class="icon-remove"></i>', '', "title='{$lang->testtask->delete}' class='btn-icon'");
      }
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='8'><?php $pager->show();?></td></tr></tfoot>
</table>
<script language="Javascript">$("#<?php echo $type;?>Testtask").addClass('active'); </script>
<?php include '../../common/view/footer.html.php';?>
