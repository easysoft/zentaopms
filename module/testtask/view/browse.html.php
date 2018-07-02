<?php
/**
 * The browse view file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: browse.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('confirmDelete', $lang->testtask->confirmDelete)?>
<?php js::set('flow', $config->global->flow);?>
<?php
$scope  = $this->session->testTaskVersionScope;
$status = $this->session->testTaskVersionStatus;
?>
<?php js::set('status', $status);?>
<?php if($config->global->flow != 'onlyTest'):?>
<div id="mainMenu" class='clearfix'>
  <div class="btn-toolbar pull-left">
    <div class='btn-group'>
      <?php $viewName = $scope == 'local'? $productName : $lang->testtask->all;?>
      <a href='javascript:;' class='btn btn-link btn-limit' data-toggle='dropdown'><span class='text' title='<?php echo $viewName;?>'><?php echo $viewName;?></span> <span class='caret'></span></a>
      <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
        <?php
          echo "<li>" . html::a(inlink('browse', "productID=$productID&branch=0&type=all,$status"), $lang->testtask->all) . "</li>";
          echo "<li>" . html::a(inlink('browse', "productID=$productID&branch=$branch&type=local,$status"), $productName, '', "title='{$productName}' class='text-ellipsis'") . "</li>";
        ?>
      </ul>
    </div>
    <?php echo html::a(inlink('browse', "productID=$productID&branch=$branch&type=$scope,totalStatus"), "<span class='text'>{$lang->testtask->totalStatus}</span>", '', "id='totalStatusTab' class='btn btn-link'");?>
    <?php echo html::a(inlink('browse', "productID=$productID&branch=$branch&type=$scope,wait"), "<span class='text'>{$lang->testtask->wait}</span>", '', "id='waitTab' class='btn btn-link'");?>
    <?php echo html::a(inlink('browse', "productID=$productID&branch=$branch&type=$scope,doing"), "<span class='text'>{$lang->testtask->testing}</span>", '', "id='doingTab' class='btn btn-link'");?>
    <?php echo html::a(inlink('browse', "productID=$productID&branch=$branch&type=$scope,blocked"), "<span class='text'>{$lang->testtask->blocked}</span>", '', "id='blockedTab' class='btn btn-link'");?>
    <?php echo html::a(inlink('browse', "productID=$productID&branch=$branch&type=$scope,done"), "<span class='text'>{$lang->testtask->done}</span>", '', "id='doneTab' class='btn btn-link'");?>
    <?php $condition = "productID=$productID&branch=$branch&type=$scope,$status&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}&pageID=1"?>
    <div class='input-group w-300px input-group-sm'>
      <span class='input-group-addon'><?php echo $lang->testtask->beginAndEnd;?></span>
      <div class='datepicker-wrapper datepicker-date'><?php echo html::input('date', $beginTime, "class='form-control form-date' onchange='changeDate(this.value, \"$endTime\", \"$condition\")'");?></div>
      <span class='input-group-addon'><?php echo $lang->testtask->to;?></span>
      <div class='datepicker-wrapper datepicker-date'><?php echo html::input('date', $endTime, "class='form-control form-date' onchange='changeDate(\"$beginTime\", this.value, \"$condition\")'");?></div>
    </div>
  </div>
  <div class="btn-toolbar pull-right"><?php common::printLink('testtask', 'create', "product=$productID", "<i class='icon icon-plus'></i> " . $lang->testtask->create, '', "class='btn btn-primary'");?></div>
</div>
<?php endif;?>
<div id='mainContent' class='main-table'>
  <?php if(empty($tasks)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->testtask->noTesttask;?></span>
      <?php if(common::hasPriv('testtask', 'create')):?>
      <span class="text-muted"><?php echo $lang->youCould;?></span>
      <?php echo html::a($this->createLink('testtask', 'create', "product=$productID"), "<i class='icon icon-plus'></i> " . $lang->testtask->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <table class='table has-sort-head' id='taskList'>
    <thead>
    <?php $vars = "productID=$productID&branch=$branch&type=$scope,$status&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <tr>
        <th class='c-id text-left'>   <?php common::printOrderLink('id',      $orderBy, $vars, $lang->idAB);?></th>
        <th class='w-200px text-left'><?php common::printOrderLink('name',    $orderBy, $vars, $lang->testtask->name);?></th>
        <th class='text-left'>        <?php common::printOrderLink('product', $orderBy, $vars, $lang->testtask->product);?></th>
        <?php if($config->global->flow != 'onlyTest'):?>
        <th class='text-left'>        <?php common::printOrderLink('project', $orderBy, $vars, $lang->testtask->project);?></th>
        <?php endif;?>
        <th class='text-left'>        <?php common::printOrderLink('build',   $orderBy, $vars, $lang->testtask->build);?></th>
        <th class='c-user text-left'> <?php common::printOrderLink('owner',   $orderBy, $vars, $lang->testtask->owner);?></th>
        <th class='w-100px text-left'><?php common::printOrderLink('begin',   $orderBy, $vars, $lang->testtask->begin);?></th>
        <th class='w-100px text-left'><?php common::printOrderLink('end',     $orderBy, $vars, $lang->testtask->end);?></th>
        <th class='w-80px text-left'> <?php common::printOrderLink('status',  $orderBy, $vars, $lang->statusAB);?></th>
        <th class='c-actions-6 text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($tasks as $task):?>
    <tr class='text-left'>
      <td><?php echo html::a(inlink('cases', "taskID=$task->id"), sprintf('%03d', $task->id));?></td>
      <td class='c-name' title="<?php echo $task->name?>"><?php echo html::a(inlink('cases', "taskID=$task->id"), $task->name);?></td>
      <td class='c-name' title="<?php echo $task->productName?>"><?php echo $task->productName?></td>
      <?php if($config->global->flow != 'onlyTest'):?>
      <td class='c-name' title="<?php echo $task->projectName?>"><?php echo $task->projectName?></td>
      <?php endif;?>
      <td class='c-name'><?php echo ($task->build == 'trunk' || empty($task->buildName)) ? $lang->trunk : html::a($this->createLink('build', 'view', "buildID=$task->build",'',true), $task->buildName);?></td>
      <td><?php echo zget($users, $task->owner);?></td>
      <td><?php echo $task->begin?></td>
      <td><?php echo $task->end?></td>
      <td title='<?php echo $lang->testtask->statusList[$task->status];?>'>
        <span class='status-<?php echo $task->status?>'>
          <span class='label label-dot'></span>
          <span class='status-text'><?php echo $lang->testtask->statusList[$task->status];?></span>
        </span>
      </td>
      <td class='c-actions'>
        <?php
        common::printIcon('testtask',   'cases',    "taskID=$task->id", $task, 'list', 'sitemap');
        common::printIcon('testtask',   'view',     "taskID=$task->id", '', 'list', 'list-alt','','iframe',true, 'data-width=800px');
        common::printIcon('testtask',   'linkCase', "taskID=$task->id", $task, 'list', 'link');
        common::printIcon('testreport', 'browse',   "objectID=$task->product&objectType=product&extra=$task->id", $task, 'list','flag');
        common::printIcon('testtask',   'edit',     "taskID=$task->id", $task, 'list','','','',true);
        if(common::hasPriv('testtask', 'delete', $task))
        {
            $deleteURL = $this->createLink('testtask', 'delete', "taskID=$task->id&confirm=yes");
            echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"taskList\",confirmDelete)", '<i class="icon-common-delete icon-trash"></i>', '', "title='{$lang->testtask->delete}' class='btn'");
        }
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
  <?php endif;?>
</div>
<script>$(function(){$('#<?php echo $status?>Tab').addClass('btn-active-text').append("<span class='label label-light label-badge'><?php echo $pager->recTotal;?></span>")})</script>
<?php include '../../common/view/footer.html.php';?>
