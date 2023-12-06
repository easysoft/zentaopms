<?php
/**
 * The browse view file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: browse.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('confirmDelete', $lang->testtask->confirmDelete)?>
<?php js::set('flow', $config->global->flow);?>
<?php
$scope  = $this->session->testTaskVersionScope;
$status = $this->session->testTaskVersionStatus;
$status = strtolower($status);
?>
<?php js::set('status', $status);?>
<style>
#action-divider{display: inline-block; line-height: 0px;}
</style>
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
    <?php common::sortFeatureMenu();?>
    <?php foreach($lang->testtask->featureBar['browse'] as $key => $label):?>
    <?php $key = strtolower($key);?>
    <?php echo html::a(inlink('browse', "productID=$productID&branch=$branch&type=$scope,$key"), "<span class='text'>$label</span>", '', "id='{$key}Tab' class='btn btn-link'");?>
    <?php endforeach;?>
    <?php $condition = "productID=$productID&branch=$branch&type=$scope,$status&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}&pageID=1"?>
    <div class='input-group w-300px input-group-sm'>
      <span class='input-group-addon'><?php echo $lang->testtask->beginAndEnd;?></span>
      <div class='datepicker-wrapper datepicker-date'><?php echo html::input('date', $beginTime, "class='form-control form-date' onchange='changeDate(this.value, \"$endTime\", \"$condition\")' placeholder='" . $lang->testtask->begin . "'");?></div>
      <span class='input-group-addon'><?php echo $lang->testtask->to;?></span>
      <div class='datepicker-wrapper datepicker-date'><?php echo html::input('date', $endTime, "class='form-control form-date' onchange='changeDate(\"$beginTime\", this.value, \"$condition\")' placeholder='" . $lang->testtask->end . "'");?></div>
    </div>
  </div>
  <?php if(common::canModify('product', $product)):?>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('testtask', 'create', "product=$productID", "<i class='icon icon-plus'></i> " . $lang->testtask->create, '', "class='btn btn-primary'");?>
  </div>
  <?php endif;?>
</div>
<div id='mainContent' class='main-table'>
  <?php if(empty($tasks)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->testtask->noTesttask;?></span>
      <?php if(common::canModify('product', $product) and common::hasPriv('testtask', 'create')):?>
      <?php echo html::a($this->createLink('testtask', 'create', "product=$productID"), "<i class='icon icon-plus'></i> " . $lang->testtask->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <table class='table has-sort-head' id='taskList'>
    <thead>
    <?php $vars = "productID=$productID&branch=$branch&type=$scope,$status&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <tr>
        <th class='c-id text-left'>    <?php common::printOrderLink('id',        $orderBy, $vars, $lang->idAB);?></th>
        <th class='c-name text-left'>  <?php common::printOrderLink('name',      $orderBy, $vars, $lang->testtask->name);?></th>
        <th class='text-left'>         <?php common::printOrderLink('build',     $orderBy, $vars, $lang->testtask->build);?></th>
        <?php if(!$product->shadow):?>
        <th class='text-left'>         <?php common::printOrderLink('product',   $orderBy, $vars, $lang->testtask->product);?></th>
        <?php endif;?>
        <th class='text-left'>         <?php common::printOrderLink('execution', $orderBy, $vars, $lang->testtask->execution);?></th>
        <th class='c-status text-left'><?php common::printOrderLink('status',    $orderBy, $vars, $lang->statusAB);?></th>
        <th class='c-user text-left'>  <?php common::printOrderLink('owner',     $orderBy, $vars, $lang->testtask->owner);?></th>
        <th class='c-date text-left'>  <?php common::printOrderLink('begin',     $orderBy, $vars, $lang->testtask->begin);?></th>
        <th class='c-date text-left'>  <?php common::printOrderLink('end',       $orderBy, $vars, $lang->testtask->end);?></th>
        <?php
        $extendFields = $this->testtask->getFlowExtendFields();
        foreach($extendFields as $extendField) echo "<th>{$extendField->name}</th>";
        ?>
        <th class='c-actions-6 text-center'><?php echo $lang->actions;?></th>
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
    <tr class='text-left'>
      <td><?php echo html::a(inlink('cases', "taskID=$task->id"), sprintf('%03d', $task->id));?></td>
      <td class='c-name' title="<?php echo $task->name?>"><?php echo html::a(inlink('cases', "taskID=$task->id"), $task->name);?></td>
      <td class='c-name' title="<?php echo $task->buildName?>"><?php echo ($task->build == 'trunk' || empty($task->buildName)) ? $lang->trunk : html::a($this->createLink('build', 'view', "buildID=$task->build"), $task->buildName, '', "data-group=execution");?></td>
      <?php if(!$product->shadow):?>
      <td class='c-name' title="<?php echo $task->productName?>"><?php echo $task->productName?></td>
      <?php endif;?>
      <td class='c-name' title="<?php echo $task->executionName?>"><?php echo $task->executionName?></td>
      <?php $statusName = $this->processStatus('testtask', $task);?>
      <td title='<?php echo $statusName;?>'>
        <span class='status-testtask status-<?php echo $task->status?>'>
          <?php echo $statusName;?>
        </span>
      </td>
      <td title="<?php echo zget($users, $task->owner);?>"><?php echo zget($users, $task->owner);?></td>
      <td><?php echo $task->begin?></td>
      <td><?php echo $task->end?></td>
      <?php foreach($extendFields as $extendField) echo "<td>" . $this->loadModel('flow')->getFieldValue($extendField, $task) . "</td>";?>
      <td class='c-actions'>
        <?php echo $this->testtask->buildOperateMenu($task, 'browse');?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'>
    <div class="table-statistic"><?php echo $status == 'totalstatus' ? sprintf($lang->testtask->allSummary, count($tasks), $waitCount, $testingCount, $blockedCount, $doneCount) : sprintf($lang->testtask->pageSummary, count($tasks));?></div>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
  <?php endif;?>
</div>
<script>$(function(){$("#" + status + "Tab").addClass('btn-active-text').append(" <span class='label label-light label-badge'><?php echo $pager->recTotal;?></span>")})</script>
<?php include '../../common/view/footer.html.php';?>
