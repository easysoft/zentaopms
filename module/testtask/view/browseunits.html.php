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
<style>
#action-divider{display: inline-block; line-height: 0px; border-right: 2px solid #ddd}
</style>
<div id="mainMenu" class='clearfix'>
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->testtask->unitTag as $key => $label):?>
    <?php echo html::a(inlink('browseUnits', "productID=$productID&browseType=$key&orderBy=$orderBy"), "<span class='text'>$label</span>", '', "id='{$key}Tab' class='btn btn-link'");?>
    <?php endforeach;?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('testtask', 'importUnitResult', "product=$productID", "<i class='icon icon-import'></i> " . $lang->testtask->importUnitResult, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='mainContent' class='main-table' data-ride='table'>
  <?php if(empty($tasks)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->testtask->noTesttask;?></span>
    </p>
  </div>
  <?php else:?>
  <table class='table has-sort-head' id='taskList'>
    <thead>
    <?php $vars = "productID=$productID&browseType=$browseType&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <tr>
        <th class='c-id text-left'>   <?php common::printOrderLink('id',      $orderBy, $vars, $lang->idAB);?></th>
        <th class='w-300px text-left'><?php common::printOrderLink('name',    $orderBy, $vars, $lang->testtask->name);?></th>
        <?php if($config->global->flow != 'onlyTest'):?>
        <th class='text-left'>        <?php common::printOrderLink('project', $orderBy, $vars, $lang->testtask->project);?></th>
        <?php endif;?>
        <th class='text-left'>        <?php common::printOrderLink('build',   $orderBy, $vars, $lang->testtask->build);?></th>
        <th class='c-user text-left'> <?php common::printOrderLink('owner',   $orderBy, $vars, $lang->testtask->owner);?></th>
        <th class='w-90px text-left'> <?php common::printOrderLink('begin',   $orderBy, $vars, $lang->testtask->execTime);?></th>
        <th class='w-50px text-center'><?php echo $lang->testtask->caseCount;?></th>
        <th class='w-40px text-center'><?php echo $lang->testtask->passCount;?></th>
        <th class='w-40px text-center'><?php echo $lang->testtask->failCount;?></th>
        <th class='c-actions-3 text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($tasks as $task):?>
    <tr class='text-left'>
      <td><?php printf('%03d', $task->id);?></td>
      <td class='c-name' title="<?php echo $task->name?>"><?php echo html::a(inlink('unitCases', "taskID=$task->id"), $task->name);?></td>
      <?php if($config->global->flow != 'onlyTest'):?>
      <td class='c-name' title="<?php echo $task->projectName?>"><?php echo $task->projectName?></td>
      <?php endif;?>
      <td class='c-name'><?php echo ($task->build == 'trunk' || empty($task->buildName)) ? $lang->trunk : html::a($this->createLink('build', 'view', "buildID=$task->build",'',true), $task->buildName);?></td>
      <td><?php echo zget($users, $task->owner);?></td>
      <td><?php echo $task->end?></td>
      <td class='text-center'><?php echo $task->caseCount?></td>
      <td class='text-center pass'><?php echo $task->passCount?></td>
      <td class='text-center fail'><?php echo $task->failCount?></td>
      <td class='c-actions'>
        <?php
        common::printIcon('testtask',  'unitCases', "taskID=$task->id", '', 'list', 'list-alt','','',true);
        common::printIcon('testtask',  'edit', "taskID=$task->id", $task, 'list','','','',true);
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
  <?php if($browseType != 'newest'):?>
  <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
  <?php endif;?>
  <?php endif;?>
</div>
<script>
$(function()
{
    $('#<?php echo $browseType?>Tab').addClass('btn-active-text').append(" <span class='label label-light label-badge'><?php echo ($browseType == 'newest' and $pager->recTotal >= $pager->recPerPage) ? $pager->recPerPage : $pager->recTotal;?></span>");
    <?php if($this->config->global->flow == 'full'):?>
    $('#subNavbar [data-id=testcase]').addClass('active');
    <?php else:?>
    $('#navbar li.active').removeClass('active');
    $('#navbar li[data-id=unit]').addClass('active');
    <?php endif;?>
})
</script>
<?php include '../../common/view/footer.html.php';?>
