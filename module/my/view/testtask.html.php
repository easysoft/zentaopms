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
<?php js::set('mode', $mode);?>
<?php js::set('total', $pager->recTotal);?>
<?php js::set('rawMethod', $app->rawMethod);?>
<?php js::set('confirmDelete', $lang->testtask->confirmDelete)?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    if($app->rawMethod == 'contribute')
    {
        $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
        foreach($lang->my->featureBar[$app->rawMethod]['testtask'] as $typeKey => $name)
        {
            echo html::a(inlink($app->rawMethod, "mode=$mode&type=$typeKey"), "<span class='text'>{$name}</span>" . ($type == $typeKey ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == $typeKey ? ' btn-active-text' : '') . "'");
        }
    }
    ?>
  </div>
</div>
<div id="mainContent" class='main-table' data-ride='table'>
  <?php if(empty($tasks)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->testtask->noTesttask;?></span></p>
  </div>
  <?php else:?>
  <table class="table has-sort-head table-fixed" id='taskList'>
    <?php $vars = "mode=$mode&type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
    <thead>
      <tr>
        <th class='c-id'>       <?php common::printOrderLink('id',        $orderBy, $vars, $lang->idAB);?></th>
        <th>                    <?php common::printOrderLink('name',      $orderBy, $vars, $lang->testtask->name);?></th>
        <th class='c-build'>    <?php common::printOrderLink('build',     $orderBy, $vars, $lang->testtask->build);?></th>
        <th class='c-execution'><?php common::printOrderLink('execution', $orderBy, $vars, $lang->testtask->execution);?></th>
        <th class='c-status'>   <?php common::printOrderLink('status',    $orderBy, $vars, $lang->statusAB);?></th>
        <th class='c-date'>     <?php common::printOrderLink('begin',     $orderBy, $vars, $lang->testtask->begin);?></th>
        <th class='c-date'>     <?php common::printOrderLink('end',       $orderBy, $vars, $lang->testtask->end);?></th>
        <th class='c-actions-6 text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      $waitCount    = 0;
      $testingCount = 0;
      $blockedCount = 0;
      ?>
      <?php foreach($tasks as $task):?>
      <?php if($task->status == 'wait')    $waitCount ++;?>
      <?php if($task->status == 'doing')   $testingCount ++;?>
      <?php if($task->status == 'blocked') $blockedCount ++;?>
      <tr>
        <td class="c-id"><?php printf('%03d', $task->id);?></td>
        <td class='text-left nobr' title='<?php echo $task->name;?>'><?php echo html::a($this->createLink('testtask', 'view', "taskID=$task->id"), $task->name);?></td>
        <td class='nobr' title='<?php echo $task->build == 'trunk' ? $lang->trunk : $task->buildName;?>'><?php $task->build == 'trunk' ? print($lang->trunk) : print(html::a($this->createLink('build', 'view', "buildID=$task->build"), $task->buildName));?></td>
        <?php
        $executionName = $task->executionName;
        if(empty($task->executionMultiple)) $executionName = $task->projectName . "({$this->lang->project->disableExecution})";
        ?>
        <td class='nobr' title='<?php echo $executionName;?>'><?php echo $executionName;?></td>
        <td title='<?php echo $this->processStatus('testtask', $task);?>'><span class="status-task status-<?php echo $task->status;?>"><?php echo $this->processStatus('testtask', $task);?></span></td>
        <td><?php echo $task->begin?></td>
        <td><?php echo $task->end?></td>
        <td class='c-actions'>
          <?php
          common::printIcon('testtask',   'cases',    "taskID=$task->id", $task, 'list', 'sitemap', '', '', '', "data-app='qa'");
          common::printIcon('testtask',   'view',     "taskID=$task->id", '', 'list', 'list-alt', '', 'iframe', true, "data-width='90%'");
          common::printIcon('testtask',   'linkCase', "taskID=$task->id", $task, 'list', 'link', '', '', false, "data-app='qa'");
          common::printIcon('testreport', 'browse',   "objectID=$task->product&objectType=product&extra=$task->id", $task, 'list', 'summary', '', '', false, "data-app='qa'");
          common::printIcon('testtask',   'edit',     "taskID=$task->id", $task, 'list', '', '', 'iframe', true, "data-width='90%'");
          if(common::hasPriv('testtask', 'delete', $task))
          {
              $deleteURL = $this->createLink('testtask', 'delete', "taskID=$task->id&confirm=yes");
              echo html::a("javascript:ajaxDelete(\"$deleteURL\", \"taskList\", confirmDelete)", '<i class="icon-common-delete icon-trash"></i>', '', "title='{$lang->testtask->delete}' class='btn'");
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class="table-footer">
    <div class="table-statistic"><?php echo $app->rawMethod == 'work' ? sprintf($lang->testtask->mySummary, count($tasks), $waitCount, $testingCount, $blockedCount) : sprintf($lang->testtask->pageSummary, count($tasks));?></div>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
